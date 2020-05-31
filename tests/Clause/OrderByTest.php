<?php
namespace Test\Lucinda\Query\Clause;

use Lucinda\Query\Clause\OrderBy;
use Lucinda\UnitTest\Result;

class OrderByTest
{
    public function add()
    {
        $object = new OrderBy();
        $object->add("a", \Lucinda\Query\Operator\OrderBy::ASC);
        $object->add("b", \Lucinda\Query\Operator\OrderBy::DESC);
        return new Result($object->toString() == "a ASC, b DESC");
    }
        

    public function toString()
    {
        $object = new OrderBy(["a", "b"]);
        return new Result($object->toString() == "a ASC, b ASC");
    }
        

    public function isEmpty()
    {
        $object = new OrderBy(["a", "b"]);
        return new Result(!$object->isEmpty());
    }
}
