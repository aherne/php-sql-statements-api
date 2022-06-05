<?php

namespace Test\Lucinda\Query;

use Lucinda\Query\Truncate;
use Lucinda\UnitTest\Result;

class TruncateTest
{
    public function toString()
    {
        $object = new Truncate("a");
        return new Result($object->__toString()=="TRUNCATE TABLE a");
    }

    public function __toString(): string
    {
        return "OK";
    }
}
