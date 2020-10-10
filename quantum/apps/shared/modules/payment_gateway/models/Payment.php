<?php
namespace PaymentGateway\Entities;

/**
 * Class Payment
 * @package PaymentGateway\Entities
 */
class Payment extends \Quantum\ActiverecordModel
{

    /**
     * @var
     */
    public $amount;
    /**
     * @var
     */
    public $invoice_id;

    /**
     * Payment constructor.
     */
    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getInvoiceId()
    {
        return $this->invoice_id;
    }

    /**
     * @param mixed $invoice_id
     */
    public function setInvoiceId($invoice_id)
    {
        $this->invoice_id = $invoice_id;
    }


}