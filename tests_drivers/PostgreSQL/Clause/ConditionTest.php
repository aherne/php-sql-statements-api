<?php

namespace Test\Lucinda\Query\Vendor\PostgreSQL\Clause;

use Lucinda\Query\Vendor\PostgreSQL\Clause\Condition;
use Lucinda\UnitTest\Validator\Strings;

class ConditionTest
{
    public function setILike()
    {
        $object = new Condition();
        $object->setILike("name", "':name'");
        return (new Strings($object->__toString()))->assertEquals("name ILIKE ':name'");
    }

    public function setRegexp()
    {
        $object = new Condition();
        $object->setRegexp("name", "'^Lu'", false, false);
        return (new Strings($object->__toString()))->assertEquals("name !~* '^Lu'");
    }

    public function setFullTextSearch()
    {
        $object = new Condition();
        $object->setFullTextSearch("to_tsvector('english', body)", "plainto_tsquery('english', :q)");
        return (new Strings($object->__toString()))->assertEquals("to_tsvector('english', body) @@ plainto_tsquery('english', :q)");
    }
}
