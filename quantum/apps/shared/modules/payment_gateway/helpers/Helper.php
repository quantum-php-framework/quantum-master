<?php


namespace PaymentGateway;

class Helper extends \Quantum\HMVC\Helper
{

    public function __construct()
    {
        parent::__construct();

        qs('Hello world i am a module helper')->render();
    }

    function ruleTheWorld()
    {
        qs($this->getConfig()->get('name').' helper will rule the world')->render();
    }


}