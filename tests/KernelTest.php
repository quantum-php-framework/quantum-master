<?php

require_once(__DIR__."/../composer/vendor/autoload.php");
require_once (__DIR__."/../quantum/kernel/quantum.php");


use PHPUnit\Framework\TestCase;

class KernelTest extends TestCase
{
    public function testCanCreate()
    {
        $this->assertInstanceOf(
            \Quantum\Kernel::class,
            $kernel = Quantum\Kernel::getInstance()
        );


    }



}
