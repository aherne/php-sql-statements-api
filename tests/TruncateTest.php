<?php

namespace Test\Lucinda\Query;

use Lucinda\Query\Truncate;
use Lucinda\UnitTest\Validator\Strings;

class TruncateTest
{
    public function toString()
    {
        $object = new Truncate("a");
        return (new Strings($object->__toString()))->assertEquals("TRUNCATE TABLE a");
    }

    public function __toString(): string
    {
        return "OK";
    }
}
