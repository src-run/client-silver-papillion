<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PaymentType.
 */
class PaymentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nameOnCard', TextType::class)
            ->add('creditCardNumber', TextType::class)
            ->add('expMonth', ChoiceType::class, [
                'choices' => $this->getExpMonthChoices(),
            ])
            ->add('expYear', ChoiceType::class, [
                'choices' => $this->getExpYearChoices(),
            ])
            ->add('securityCode', TextType::class)
            ->add('billingZip', TextType::class)
            ->add('stripeToken', TextType::class, [
                'required' => false,
            ])
            ->add('submit', SubmitType::class);
    }

    private function getExpMonthChoices()
    {
        $c = [];
        for ($i = 1; $i <= 12; ++$i) {
            $c[str_pad($i, 2, '0', STR_PAD_LEFT)] = $i;
        }

        return $c;
    }

    private function getExpYearChoices()
    {
        $d = date('Y');
        $c = [];
        for ($i = $d; $i <= $d + 20; ++$i) {
            $c[$i] = $i;
        }

        return $c;
    }
}

/* EOF */
