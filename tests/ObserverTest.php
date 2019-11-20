<?php

require_once(__DIR__."/../composer/vendor/autoload.php");
require_once (__DIR__."/../quantum/kernel/quantum.php");

require_once (__DIR__."/../quantum/kernel/system/modules/events/Observer.php");
require_once (__DIR__."/../quantum/kernel/system/modules/events/Event.php");
require_once (__DIR__."/../quantum/kernel/system/modules/events/EventsManager.php");


use PHPUnit\Framework\TestCase;


class ObserverTest extends TestCase
{
    public function testRegisterAndDispatch()
    {
        \Quantum\Events\EventsManager::getInstance()->addObserver("test", function($event, $data)
        {
            $this->assertEquals("var", $data);
        });

        //QM::events()->addControllerObserver("test", "ApiController", 'monitor');

        //QM::observe("test", "teapot");

        //QM::observe("test", "teapot");w

        //QM::observe("test", [$this, 'listener_test']);

        // QM::observe("test", array($this, 'listener_test'));

        \Quantum\Events\EventsManager::getInstance()->dispatch("test", "var");

    }




}
