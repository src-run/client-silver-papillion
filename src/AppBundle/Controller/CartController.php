<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use AppBundle\Component\Location\Model\LocationCollectionModel;
use AppBundle\Entity\Category;
use AppBundle\Entity\Order;
use AppBundle\Entity\OrderItem;
use AppBundle\Entity\Product;
use AppBundle\Form\PaymentType;
use AppBundle\Form\ShipmentType;
use AppBundle\Model\Cart;
use AppBundle\Model\CartGroup;
use AppBundle\Model\Payment;
use AppBundle\Model\Shipment;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Stripe\Charge;
use Stripe\Error\ApiConnection;
use Stripe\Error\Authentication;
use Stripe\Error\Base;
use Stripe\Error\Card;
use Stripe\Error\InvalidRequest;
use Stripe\Error\RateLimit;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Category CartController.
 */
class CartController extends Controller
{
    /**
     * @param Request $request
     *
     * @return LocationCollectionModel
     */
    private function locationLookup(Request $request)
    {
        return $this->get('app.location_lookup.resolver')
            ->lookupAll($request->getClientIp());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Request $request)
    {
        return $this->render('AppBundle:cart:view.html.twig', [
            '_c'    => static::class,
            'geoip' => $this->locationLookup($request),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function checkoutAction(Request $request)
    {
        $session = $this->get('session');

        if (!$session->has('checkout-shipment')) {
            $shipment = new Shipment();
        } else {
            $shipment = $session->get('checkout-shipment');
        }

        $form = $this->createForm(ShipmentType::class, $shipment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session = $this->get('session');
            $session->set('checkout-shipment', $shipment);

            return $this->redirectToRoute('app_cart_checkout_payment');
        }

        return $this->render('AppBundle:cart:checkout.html.twig', [
            '_c'    => static::class,
            'f'     => $form->createView(),
            'geoip' => $this->locationLookup($request),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function checkoutPaymentAction(Request $request)
    {
        $session = $this->get('session');

        if (!$session->has('checkout-shipment')) {
            return $this->redirectToRoute('app_cart_checkout');
        }

        if (!$session->has('checkout-payment')) {
            $payment = new Payment();
        } else {
            $payment = $session->get('checkout-payment');
        }

        $form = $this->createForm(PaymentType::class, $payment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session->set('checkout-payment', $payment);

            return $this->redirectToRoute('app_cart_checkout_process');
        }

        return $this->render('AppBundle:cart:checkout-payment.html.twig', [
            '_c'         => static::class,
            'f'          => $form->createView(),
            'geoip'      => $this->locationLookup($request),
            'flash'      => $session->getFlashBag()->get('error'),
            'stripe_key' => $this->getParameter('stripe_publishable_key'),
        ]);
    }

    public function checkoutProcessAction(Request $request)
    {
        $session = $this->get('session');

        if (!$session->has('checkout-shipment') || !$session->has('checkout-payment')) {
            return $this->redirectToRoute('app_cart_checkout');
        }

        $cart = $this->get('app.cart');
        $shipment = $session->get('checkout-shipment');
        $payment = $session->get('checkout-payment');
        $payment->clearSensitive();

        try {
            Stripe::setApiKey($this->getParameter('stripe_secret_key'));

            $response = Charge::create([
                'amount'   => round($cart->total() * 100),
                'currency' => 'usd',
                'source'   => $payment->getStripeToken(),
                'metadata' => [
                    'email' => $shipment->getEmail(),
                ],
            ]);
        } catch (Card $e) {
            return $this->returnProcessError($e);
        } catch (RateLimit $e) {
            return $this->returnProcessError($e);
        } catch (InvalidRequest $e) {
            return $this->returnProcessError($e);
        } catch (Authentication $e) {
            return $this->returnProcessError($e);
        } catch (ApiConnection $e) {
            return $this->returnProcessError($e);
        } catch (Base $e) {
            return $this->returnProcessError($e);
        } catch (\Exception $e) {
            $session->getFlashBag()->add('error', 'An unexpected error occured. Please try again later.');

            return $this->redirectToRoute('app_cart_checkout_payment');
        }

        $em = $this->get('doctrine.orm.default_entity_manager');
        $order = $this->createAndPersistOrderEntity($em, $cart, $payment, $shipment, $response);

        $this->sendNotifications($order);
        $cart->clear();
        $cart->save();
        $session->remove('checkout-shipment');
        $session->remove('checkout-payment');

        return $this->render('AppBundle:cart:checkout-confirmation.html.twig', [
            '_c'    => static::class,
            'geoip' => $this->locationLookup($request),
            'order' => $order,
        ]);
    }

    /**
     * @param EntityManager $em
     * @param Cart          $cart
     * @param Payment       $payment
     * @param Shipment      $shipment
     * @param Charge        $charge
     *
     * @return Order
     */
    private function createAndPersistOrderEntity(EntityManager $em, Cart $cart, Payment $payment, Shipment $shipment, Charge $charge)
    {
        $order = new Order();
        $order->setName($shipment->getName());
        $order->setEmail($shipment->getEmail());
        $order->setAddress($shipment->getAddress());
        $order->setCity($shipment->getCity());
        $order->setState($shipment->getState());
        $order->setZip($shipment->getZip());
        $order->setTotal($cart->total());
        $order->setShipping($cart->shipping());
        $order->setTax($cart->tax());
        $order->setPaid($charge->paid);
        $order->setReference($charge->id);

        $items = [];
        foreach ($cart->getItemsGrouped() as $group) {
            $items[] = $i = $this->createOrderItemEntity($group);
            $em->persist($i);
        }

        $order->setItems($items);

        $em->persist($order);
        $em->flush();

        return $order;
    }

    /**
     * @param CartGroup $group
     *
     * @return OrderItem
     */
    private function createOrderItemEntity(CartGroup $group)
    {
        $item = new OrderItem();
        $item->setName($group->getProduct()->getName());
        $item->setCount($group->count());
        $item->setTotal($group->total());
        if ($group->getProduct()->hasSku()) {
            $item->setSku($group->getProduct()->getSku());
        }

        return $item;
    }

    /**
     * @param Order $order
     */
    private function sendNotifications(Order $order)
    {
        $config = $this->get('app.manager.configuration');
        $subject = sprintf('Order Confirmation (Reference ID %s)', $order->getOrderNumber());
        $storeEmail = [$config->value('contact.email', 'rmf@src.run') => 'Silver Papillon'];
        $orderEmail = [$order->getEmail() => $order->getName()];
        $adminEmail = [$config->value('contact.admin-email', 'rmf@src.run') => 'Source Consulting'];

        $storeMessage = $this->createOrderMessage($storeEmail, $adminEmail, $subject,
            'email/checkout-confirmation-store.html.twig', $this->getEmailTwigArgs($order));
        $orderMessage = $this->createOrderMessage($orderEmail, $storeEmail, $subject,
            'email/checkout-confirmation-order.html.twig', $this->getEmailTwigArgs($order));

        $this->get('mailer')->send($storeMessage);
        $this->get('mailer')->send($orderMessage);
    }

    /**
     * @param Order $order
     *
     * @return mixed[]
     */
    private function getEmailTwigArgs(Order $order)
    {
        return [
            'order'     => $order,
            'title'     => sprintf('Order Confirmation (Reference ID %s)', $order->getOrderNumber()),
            'createdOn' => $order->getCreatedOn(),
            'from'      => 'orders@silverpapillon.com',
        ];
    }

    /**
     * @param string|string[] $to
     * @param string|string[] $replyTo
     * @param string          $subject
     * @param string          $view
     * @param mixed[]         $viewArgs
     *
     * @return \Swift_Mime_MimePart
     */
    private function createOrderMessage($to, $replyTo, $subject, $view, array $viewArgs = [])
    {
        return \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom(['orders@silverpapillon.com' => 'Silver Papillon Orders'])
            ->setTo($to)
            ->setReplyTo($replyTo)
            ->setBody($this->renderView($view, $viewArgs), 'text/html');
    }

    /**
     * @param Base $e
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function returnProcessError(Base $e)
    {
        $session = $this->get('session');
        $message = 'We were unable to charge your credit card.';
        $error = $e->getJsonBody();

        if (isset($error['error']) && isset($error['error']['message'])) {
            $message .= ' '.$error['error']['message'];
        } else {
            $message .= ' '.$e->getMessage();
        }

        $session->getFlashBag()->add('error', $message);

        return $this->redirectToRoute('app_cart_checkout_payment');
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function clearAction(Request $request)
    {
        $cart = $this->get('app.cart');
        $cart->clear();
        $cart->save();

        $r = $this->getLastRoute($request);

        if (!isset($r['product'])) {
            return $this->redirect($this->generateUrl($r['_route']));
        }

        return $this->redirectBack($r);
    }

    /**
     * @ParamConverter("product")
     *
     * @param Product $product
     * @param int     $quantity
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Product $product, $quantity, Request $request)
    {
        $cart = $this->get('app.cart');
        foreach (range(1, $quantity) as $i) {
            $cart->add($product);
        }
        $cart->save();

        $r = $this->getLastRoute($request);

        if (!isset($r['product'])) {
            return $this->redirect($this->generateUrl($r['_route']));
        }

        return $this->redirectBack($r);
    }

    /**
     * @ParamConverter("product")
     *
     * @param Product $product
     * @param int     $quantity
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction(Product $product, $quantity, Request $request)
    {
        $cart = $this->get('app.cart');
        foreach (range(1, $quantity) as $i) {
            $cart->rmOne($product);
        }
        $cart->save();

        $r = $this->getLastRoute($request);

        if (!isset($r['product'])) {
            return $this->redirect($this->generateUrl($r['_route']));
        }

        return $this->redirectBack($r);
    }

    /**
     * @ParamConverter("product")
     *
     * @param Product $product
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeGroupAction(Product $product, Request $request)
    {
        $cart = $this->get('app.cart');
        $cart->rm($product);
        $cart->save();

        $r = $this->getLastRoute($request);

        if (!isset($r['product'])) {
            return $this->redirect($this->generateUrl($r['_route']));
        }

        return $this->redirectBack($r);
    }

    /**
     * @param string[] $r
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectBack($r)
    {
        return $this->redirect($this->generateUrl($r['_route'], [
            'category'    => $r['category'],
            'product'     => $r['product'],
            'productName' => $r['productName'],
        ]));
    }

    /**
     * @param Request $request
     *
     * @return string[]
     */
    private function getLastRoute(Request $request)
    {
        $referer = $request->headers->get('referer');
        $lastUrl = parse_url($referer, PHP_URL_PATH);

        return $this->get('router')->getMatcher()->match($lastUrl);
    }
}

/* EOF */
