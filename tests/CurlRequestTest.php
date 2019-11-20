<?php

require_once(__DIR__."/../composer/vendor/autoload.php");
require_once (__DIR__."/../quantum/kernel/quantum.php");

require_once (__DIR__ . "/../quantum/kernel/system/modules/kernel/CurlRequest.php");

use PHPUnit\Framework\TestCase;


class CurlRequestTest extends TestCase
{
    public function testCreate()
    {
        $req = new \Quantum\CurlRequest("https://google.com");

        $this->assertInstanceOf(\Quantum\CurlRequest::class, $req);

    }

    public function testCannotCreateEmpty()
    {
        if (class_exists('ArgumentCountError'))
            $this->expectException(ArgumentCountError::class);
        else
            $this->expectException(PHPUnit_Framework_Error_Warning::class);

        $r = new \Quantum\CurlRequest();

    }

    public function testCanExecute ()
    {
        $req = new \Quantum\CurlRequest("https://google.com");

        $req->execute();

        $this->assertNotFalse($req->getResponse());
    }








}
