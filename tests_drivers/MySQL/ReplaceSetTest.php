<?php

namespace Test\Lucinda\Query\Vendor\MySQL;

use Lucinda\Query\Vendor\MySQL\ReplaceSet;
use Lucinda\UnitTest\Result;
use Lucinda\UnitTest\Validator\Strings;

class ReplaceSetTest
{
    private $object;

    public function __construct()
    {
        $this->object = new ReplaceSet("x");
    }

    public function set()
    {
        $this->object->set(["a"=>"b"]);
        return new Result(true); // tested by toString
    }

    public function toString()
    {
        return (new Strings($this->object->__toString()))->assertEquals("REPLACE INTO x SET\r\na = b");
    }

    public function __toString(): string
    {
        return "OK";
    }
}
