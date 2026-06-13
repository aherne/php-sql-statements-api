<?php

namespace Test\Lucinda\Query\Clause;

use Lucinda\Query\Clause\Limit;
use Lucinda\UnitTest\Validator\Strings;

class LimitTest
{
    public function toString()
    {
        $limit = new Limit(10, 1);
        return (new Strings($limit->__toString()))->assertEquals("10 OFFSET 1");
    }

    public function __toString(): string
    {
        return "OK";
    }
}
