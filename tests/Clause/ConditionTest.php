<?php
namespace Test\Lucinda\Query\Clause;
    
use Lucinda\Query\Clause\Condition;
use Lucinda\Query\Operator\Comparison;
use Lucinda\UnitTest\Result;
use Lucinda\Query\Vendor\MySQL\Operator\Logical;

class ConditionTest
{
    public function set()
    {
        $object = new Condition();
        $object->set("a", "b");
        return new Result($object->toString()=="a = b");
        
    }
        

    public function setIn()
    {
        $object = new Condition();
        $object->setIn("a", ["b", "c"]);
        return new Result($object->toString()=="a IN (b, c)");
    }
        

    public function setIsNull()
    {
        $object = new Condition();
        $object->setIsNull("a", false);
        return new Result($object->toString()=="a IS NOT NULL");
    }
        

    public function setLike()
    {
        $object = new Condition();
        $object->setLike("a", "'%b%'");
        return new Result($object->toString()=="a LIKE '%b%'");
    }
        

    public function setBetween()
    {
        $object = new Condition();
        $object->setBetween("a", "c", "d");
        return new Result($object->toString()=="a BETWEEN c AND d");
    }
        

    public function setGroup()
    {
        $object = new Condition([], Logical::_OR_);
        $object->set("a", "b");
        $subcondition = new Condition();
        $subcondition->set("c", "d");
        $subcondition->set("e", "f");
        $object->setGroup($subcondition);
        return new Result($object->toString()=="a = b OR (c = d AND e = f)");
    }
        

    public function toString()
    {
        $object = new Condition();
        $object->set("a", "b", Comparison::GREATER_EQUALS);
        $object->set("c", "d");
        $subcondition = new Condition([], Logical::_OR_);
        $subcondition->set("e", "f");
        $subcondition->set("g", "h");
        $object->setGroup($subcondition);
        return new Result($object->toString()=="a >= b AND c = d AND (e = f OR g = h)");
    }
        

    public function isEmpty()
    {
        $object = new Condition();
        $object->set("a", "b", Comparison::GREATER_EQUALS);
        return new Result(!$object->isEmpty());
    }
        

}
