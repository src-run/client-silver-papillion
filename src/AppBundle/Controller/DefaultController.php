<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use AppBundle\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DefaultController.
 */
class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);

        return $this->render('AppBundle:default:index.html.twig', [
            '_c' => static::class,
            'hours' => $this->get('app.manager.hours')->getAll(),
            'categories' => $this->get('app.manager.category')->getAll(),
            'featured' => $this->get('app.manager.product')->getFeatured(),
            'feed' => $this->get('app.fb.provider.page_feed')->getFeed(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function aboutAction()
    {
        list($staticMapsUrl, $streetViewUrl, $directionsUri) = $this->getMapUrls();

        return $this->render('AppBundle:default:about.html.twig', [
            '_c' => static::class,
            'hours' => $this->get('app.manager.hours')->getAll(),
            'categories' => $this->get('app.manager.category')->getAll(),
            'staticMaps' => $staticMapsUrl,
            'streetView' => $streetViewUrl,
            'directionsUri' => $directionsUri,
        ]);
    }

    private function getMapUrls()
    {
        $blockManager = $this->get('app.manager.content_block');
        $address = urlencode(strip_tags(str_replace("\r\n", '+', $blockManager->get('about.address')->getContent())));

        $staticMapsUri = $this->getParameter('google_static_maps_uri');
        $staticMapsApi = $this->getParameter('google_api_key_static_maps');
        $streetViewUri = $this->getParameter('google_street_view_uri');
        $streetViewApi = $this->getParameter('google_api_key_street_view');
        $directionUri = $this->getParameter('google_directions_uri');

        $staticMapsUri = str_replace('${api_key}', $staticMapsApi, $staticMapsUri);
        $staticMapsUri = str_replace('${address}', $address, $staticMapsUri);
        $staticMapsFile = md5($staticMapsUri);

        $streetViewUri = str_replace('${api_key}', $streetViewApi, $streetViewUri);
        $streetViewUri = str_replace('${address}', $address, $streetViewUri);
        $streetViewFile = md5($streetViewUri);

        $dirPathBase = $this->getParameter('app.sys_path.cache');
        $webPathBase = $this->getParameter('app.web_path.cache');

        foreach ([$staticMapsFile => $staticMapsUri, $streetViewFile => $streetViewUri] as $file => $uri) {
            $filePath = $dirPathBase.DIRECTORY_SEPARATOR.$file.'.png';

            if (!file_exists($filePath) || time() - filemtime($filePath) > 6000) {
                file_put_contents($filePath, file_get_contents($uri));
            }
        }

        return [
            $webPathBase.'/'.$staticMapsFile.'.png',
            $webPathBase.'/'.$streetViewFile.'.png',
            str_replace('${address}', $address, $directionUri)
        ];
    }
}

/* EOF */
