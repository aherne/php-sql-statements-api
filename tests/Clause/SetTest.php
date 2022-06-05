<?php

namespace Test\Lucinda\Query\Clause;

use Lucinda\Query\Clause\Set;
use Lucinda\UnitTest\Result;

class SetTest
{
    public function set()
    {
        $set = new Set();
        $set->set("a", "b");
        return new Result($set->__toString()=="a = b");
    }


    public function toString()
    {
        $set = new Set(["a"=>"b", "c"=>"d"]);
        return new Result($set->__toString()=="a = b, c = d");
    }


    public function isEmpty()
    {
        $set = new Set(["a"=>"b", "c"=>"d"]);
        return new Result(!$set->isEmpty());
    }

    public function __toString(): string
    {
        return "OK";
    }
}
