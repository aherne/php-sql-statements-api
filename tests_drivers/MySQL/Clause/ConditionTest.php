<?php
namespace Test\Lucinda\Query\Vendor\MySQL\Clause;

use Lucinda\Query\Vendor\MySQL\Clause\Condition;
use Lucinda\UnitTest\Result;

class ConditionTest
{
    public function setRegexp()
    {
        $object = new Condition();
        $object->set("q", "w");
        $object->setRegexp("e", "'.*'");
        return new Result($object->toString()=="q = w AND e REGEXP '.*'");
    }
    
    public function setMatchAgainst()
    {
        $object = new Condition();
        $object->setMatchAgainst(["test"], "'me'");
        return new Result($object->toString()=="MATCH(test) AGAINST ('me' IN NATURAL LANGUAGE MODE)");
    }        

}
