<?php

namespace PaymentGateway;

use PaymentGateway\Entities\Address;
use PaymentGateway\Entities\Payment;
use PaymentGateway\Entities\CreditCard;

/**
 * Class ExampleModule
 * @package ExampleModule
 */
class PaymentGateway extends \Quantum\HMVC\Module
{

    /**
     * @var Helper
     */
    public $helper;


    /**
     * ExampleModule constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->helper = new Helper();
    }

    public function test()
    {
        $card = new CreditCard();
        //$card->number = '4715759833141913'; correct uab
        $card->setNumber('4154278193984622');//
        $card->setExpirationYear('2020');
        $card->setExpirationMonth('02');
        $card->setSecurityCode('718');

        //$card->setNumber('4012000098765439');// test from paytrace
        //$card->setExpirationYear('2020');
        //$card->setExpirationMonth('12');
        //$card->setSecurityCode('999');

        $payment = new Payment();
        $payment->setAmount('1.00');
        $payment->setInvoiceId('90001');

        $address = new Address();
        $address->setName("ClubColors");
        $address->setStreetAddress("420 E State Parkway");
        $address->setStreetAddress2();
        $address->setCity('Schaumburg');
        $address->setState("IL");
        $address->setZip('60173');
        $address->setCountry('US');

        $provider = new \PaymentGateway\Providers\Paytrace();
        $result = $provider->authorizeCreditCardPayment($card, $payment, $address, $address);

        dd($result);
    }



}
