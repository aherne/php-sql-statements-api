<?php

namespace Test\Lucinda\Query\Clause;

use Lucinda\Query\Clause\Columns;
use Lucinda\UnitTest\Result;
use Lucinda\UnitTest\Validator\Strings;

class ColumnsTest
{
    private $object;

    public function __construct()
    {
        $this->object = new Columns();
    }

    public function add()
    {
        $this->object->add("a");
        $this->object->add("b");
        return new Result(true);// tested by toString
    }

    public function toString()
    {
        return (new Strings($this->object->__toString()))->assertEquals("a, b");
    }


    public function isEmpty()
    {
        return new Result(!$this->object->isEmpty());
    }

    public function __toString(): string
    {
        return "OK";
    }
}
