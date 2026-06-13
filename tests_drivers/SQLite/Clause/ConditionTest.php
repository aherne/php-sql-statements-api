<?php

namespace Test\Lucinda\Query\Vendor\SQLite\Clause;

use Lucinda\Query\Vendor\SQLite\Clause\Condition;
use Lucinda\UnitTest\Validator\Strings;

class ConditionTest
{
    public function setGlob()
    {
        $object = new Condition();
        $object->setGlob("name", "'Lu*'");
        return (new Strings($object->__toString()))->assertEquals("name GLOB 'Lu*'");
    }
}
