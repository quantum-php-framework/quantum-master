<?php


namespace PaymentGateway\Providers;

use PaymentGateway\Entities\CreditCard;
use PaymentGateway\Entities\Payment;


class Paypal
{

    function __construct()
    {

    }

    /**
     * processCreditCardPayment
     */
    public function processCreditCardPayment(CreditCard $card, Payment $payment)
    {
        qs("Processing card: ".$card->number. " for ".$payment->amount)->render();

    }



}