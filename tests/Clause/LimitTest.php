<?php

namespace Test\Lucinda\Query\Clause;

use Lucinda\Query\Clause\Limit;
use Lucinda\UnitTest\Result;

class LimitTest
{
    public function toString()
    {
        $limit = new Limit(10, 1);
        return new Result($limit->__toString()=="10 OFFSET 1");
    }

    public function __toString(): string
    {
        return "OK";
    }
}
