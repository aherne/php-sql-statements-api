<?php
namespace Test\Lucinda\Query\Clause;

use Lucinda\Query\Clause\Window;
use Lucinda\Query\Clause\Window\PartitionBy;
use Lucinda\UnitTest\Result;
use Lucinda\UnitTest\Validator\Strings;

class WindowTest
{

    public function add()
    {
        return new Result(true); // tested by toString
    }


    public function isEmpty()
    {
        $object = new Window();
        $object->add("foo", new Window\Over(new PartitionBy(["foo"])));
        return new Result(!$object->isEmpty());
    }


    public function toString()
    {
        $object = new Window();
        $object->add("foo", new Window\Over(new PartitionBy(["bar"])));
        return (new Strings((string) $object))->assertEquals("foo AS (PARTITION BY bar)");
    }


    public function __toString()
    {
        return "OK";
    }
        

}
