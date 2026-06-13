<?php

namespace Test\Lucinda\Query\Clause;

use Lucinda\Query\Clause\Fields;
use Lucinda\Query\Clause\Window\Over;
use Lucinda\Query\Clause\Window\PartitionBy;
use Lucinda\UnitTest\Result;
use Lucinda\UnitTest\Validator\Strings;

class FieldsTest
{
    private $object;

    public function __construct()
    {
        $this->object = new Fields();
    }

    public function add()
    {
        $this->object->add("a", "b");
        $this->object->add("c");
        return new Result(true); // tested by toString
    }


    public function toString()
    {
        return (new Strings($this->object->__toString()))->assertEquals("a AS b, c");
    }


    public function isEmpty()
    {
        return new Result(!$this->object->isEmpty());
    }

    public function __toString(): string
    {
        return "OK";
    }

    public function over()
    {
        $fields = new Fields();
        $fields->add("a", "b");
        $fields->over("AVG(a)", "w1", "c");
        $fields->over("SUM(b)",  new Over(new PartitionBy(["foo"])), "d");
        return (new Strings($fields))->assertEquals("a AS b, AVG(a) OVER w1 AS c, SUM(b) OVER (PARTITION BY foo) AS d");
    }
        

}
