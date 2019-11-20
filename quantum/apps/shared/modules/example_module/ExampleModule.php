<?php

namespace ExampleModule;

/**
 * Class ExampleModule
 * @package ExampleModule
 */
class ExampleModule extends \Quantum\HMVC\Module
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

        qs('My name is: '.$this->helper->getConfig()->get('name'))->render();
        qs('My version is: '.$this->helper->getConfig()->get('version'))->render();
    }


    /**
     *
     */
    public function someMethod()
    {
        qs('ExampleModule::someMethod')->render();

        $this->helper->ruleTheWorld();
    }


    public function launchController()
    {
        \Quantum\ControllerFactory::create('ExampleModule\ExampleModuleController')->index();
    }

    public function helloSparxTeam()
    {
        qs('hello sparx')->render();
    }


}
