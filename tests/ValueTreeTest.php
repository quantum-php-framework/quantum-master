<?php

require_once(__DIR__."/../composer/vendor/autoload.php");
require_once (__DIR__."/../quantum/kernel/quantum.php");

use PHPUnit\Framework\TestCase;

final class ValueTreeTest extends TestCase
{

    public function testCanBeCreatedFromArray(): void
    {
        $this->assertInstanceOf(
            \Quantum\ValueTree::class,
            $valuetree = new \Quantum\ValueTree(array("user" => "Carlos"))
        );
    }

    public function testCanBeCreatedEmpty(): void
    {
        $this->assertInstanceOf(
            \Quantum\ValueTree::class,
            $valuetree = new \Quantum\ValueTree()
        );
    }

    public function testCannotBeCreatedFromString(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $valuetree = new \Quantum\ValueTree("invalid");
    }

    public function testCanSetAndGet (): void
    {
        $v = new \Quantum\ValueTree();
        $v->set('name', "John");

        $this->assertEquals(
            'John',
            $v->get("name")
        );
    }

    public function testCanGetFromPassedArrayToConstructor()
    {
        $v = new \Quantum\ValueTree(array("name" => "Carlos"));

        $this->assertEquals(
            'Carlos',
            $v->get("name")
        );
    }

    public function testCanGetPropertyThroughMagicMethod (): void
    {
        $v = new \Quantum\ValueTree();
        $v->set('name', "John");

        $this->assertEquals(
            'John',
            $v->getName()
        );
    }

    public function testCanGetAndSetPropertyThroughMagicMethod (): void
    {
        $v = new \Quantum\ValueTree();
        $v->setName("John");

        $this->assertEquals(
            'John',
            $v->getName()
        );
    }

    public function testCheckExistenceOfProperty()
    {
        $v = new \Quantum\ValueTree();
        $v->setName("John");

        $this->assertNotFalse($v->has("name"));
    }

    public function testArrayAccessGetAndSet()
    {
        $v = new \Quantum\ValueTree();
        $v["name"] = "John";

        $this->assertEquals(
            'John',
            $v["name"]
        );
    }

    public function testExceptionTriggeredIfAutoCallFails()
    {
        $this->expectException(InvalidArgumentException::class);

        $v = new \Quantum\ValueTree();
        $v->getName();
    }

    public function testExceptionTriggeredIfValuetreeIsInmutable()
    {
        $this->expectException(InvalidArgumentException::class);

        $v = new \Quantum\ValueTree();
        $v->setUnmutable(true);
        $v->set("name", "john");
        $v->set("name", "alex");
    }

    public function testLockedEstate()
    {
        $v = new \Quantum\ValueTree();
        $v->set("name", "john");
        $v->setLocked(true);
        $v->set("name", "james");

        $this->assertEquals(
            'john',
            $v->getName()
        );
    }

    public function testFallbackPropertyIsReturned()
    {
        $v = new \Quantum\ValueTree();

        $this->assertEquals(
            'fb',
            $v->get("name", "fb")
        );
    }


    public function testIterator()
    {

    }

    /*
    public function testCannotBeCreatedFromInvalidEmailAddress(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Email::fromString('invalid');
    }

    public function testCanBeUsedAsString(): void
    {
        $this->assertEquals(
            'user@example.com',
            Email::fromString('user@example.com')
        );
    }
    */
}


