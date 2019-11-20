<?php

require_once(__DIR__."/../composer/vendor/autoload.php");
require_once (__DIR__."/../quantum/kernel/quantum.php");

require_once (__DIR__ . "/../quantum/kernel/system/modules/kernel/AutoGetterSetter.php");

use PHPUnit\Framework\TestCase;


class AutoGetterSetterTest extends TestCase
{
    public function testCreate()
    {
        $auto = new \Quantum\AutoGetterSetter();

        $this->assertInstanceOf(\Quantum\AutoGetterSetter::class, $auto);

    }

    public function testCanGetAndSetPropertyThroughMagicMethod ()
    {
        $v = new \Quantum\AutoGetterSetter();
        $v->setName("John");

        $this->assertEquals(
            'John',
            $v->getName()
        );
    }

    public function testChildCanGetAndSetProperty ()
    {
        $v = new \Quantum\AutoGetterSetterTestChild();
        $v->setName("John");

        $this->assertEquals(
            'John',
            $v->getName()
        );
    }






}
