<?php

namespace Test\Lucinda\Query\Vendor\SQLite\Clause;

use Lucinda\Query\Vendor\SQLite\Clause\Condition;
use Lucinda\UnitTest\Result;

class ConditionTest
{
    public function setGlob()
    {
        $object = new Condition();
        $object->setGlob("name", "'Lu*'");
        return new Result($object->__toString()=="name GLOB 'Lu*'");
    }
}
