<?php

require_once(__DIR__."/../composer/vendor/autoload.php");
require_once (__DIR__."/../quantum/kernel/quantum.php");

require_once (__DIR__ . "/../quantum/kernel/system/modules/kernel/Multiton.php");
require_once (__DIR__ . "/../quantum/kernel/system/modules/kernel/AutoGetterSetter.php");

use PHPUnit\Framework\TestCase;

class MultitonTest extends TestCase
{
    public function testUniqueness()
    {
        $firstCall = \Quantum\Multiton::getInstance("Quantum\Valuetree");
        $secondCall = \Quantum\Multiton::getInstance("Quantum\Valuetree");

        $this->assertSame($firstCall, $secondCall);

        $this->assertInstanceOf(\Quantum\ValueTree::class, $firstCall);
        $this->assertSame($firstCall->get("name"), $secondCall->get("name"));

    }

    public function testUniquenessForEveryInstance()
    {
        $firstCall = \Quantum\Multiton::getInstance("Quantum\Valuetree");
        $secondCall = \Quantum\Multiton::getInstance("Quantum\AutoGetterSetter");
        $this->assertInstanceOf(\Quantum\ValueTree::class, $firstCall);
        $this->assertInstanceOf(\Quantum\AutoGetterSetter::class, $secondCall);
        $this->assertNotSame($firstCall, $secondCall);
    }

    public function testCreatedValueTreeWorks()
    {
        $v = \Quantum\Multiton::getInstance("Quantum\Valuetree");

        $v->set('name', "John");

        $this->assertEquals(
            'John',
            $v->get("name")
        );
    }


}
