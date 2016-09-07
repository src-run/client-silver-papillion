<?php

/*
 * This file is part of the `src-run/srw-client-silverpapillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Model;

/**
 * Class Payment.
 */
class Payment
{
    /**
     * @var string
     */
    private $nameOnCard;

    /**
     * @var string
     */
    private $creditCardNumber;

    /**
     * @var string
     */
    private $expYear;

    /**
     * @var string
     */
    private $expMonth;

    /**
     * @var string
     */
    private $securityCode;

    /**
     * @var string
     */
    private $billingZip;

    /**
     * @var string
     */
    private $stripeToken;

    public function clearSensitive()
    {
        $this->creditCardNumber = null;
    }

    /**
     * @return string
     */
    public function getNameOnCard()
    {
        return $this->nameOnCard;
    }

    /**
     * @param string $nameOnCard
     */
    public function setNameOnCard(string $nameOnCard)
    {
        $this->nameOnCard = $nameOnCard;
    }

    /**
     * @param string $creditCardNumber
     */
    public function setCreditCardNumber(string $creditCardNumber)
    {
        $this->creditCardNumber = $creditCardNumber;
    }

    /**
     * @param string $expYear
     */
    public function setExpYear(string $expYear)
    {
        $this->expYear = $expYear;
    }

    /**
     * @param string $expMonth
     */
    public function setExpMonth(string $expMonth)
    {
        $this->expMonth = $expMonth;
    }

    /**
     * @param string $billingZip
     */
    public function setBillingZip(string $billingZip)
    {
        $this->billingZip = $billingZip;
    }

    /**
     * @param string $securityCode
     */
    public function setSecurityCode(string $securityCode)
    {
        $this->securityCode = $securityCode;
    }

    /**
     * @return string
     */
    public function getCreditCardNumber()
    {
        return $this->creditCardNumber;
    }

    /**
     * @return string
     */
    public function getExpYear()
    {
        return $this->expYear;
    }

    /**
     * @return string
     */
    public function getExpMonth()
    {
        return $this->expMonth;
    }

    /**
     * @return string
     */
    public function getSecurityCode()
    {
        return $this->securityCode;
    }

    /**
     * @return string
     */
    public function getBillingZip()
    {
        return $this->billingZip;
    }

    /**
     * @return string
     */
    public function getStripeToken()
    {
        return $this->stripeToken;
    }

    /**
     * @param string $stipeToken
     */
    public function setStripeToken(string $stipeToken)
    {
        $this->stripeToken = $stipeToken;
    }
}

/* EOF */
