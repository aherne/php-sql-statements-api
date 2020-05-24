<?php
namespace Test\Lucinda\Query\Clause;
    
use Lucinda\Query\Clause\Join;
use Lucinda\UnitTest\Result;
use Lucinda\Query\Operator\Logical;
use Lucinda\Query\Operator\Comparison;

class JoinTest
{

    public function on()
    {
        $join = new Join("x", "t1");
        $join->on(["a"=>"b"]);
        return new Result($join->toString() == "INNER JOIN x AS t1 ON a = b");
    }
        

    public function toString()
    {
        $join = new Join("x", "", \Lucinda\Query\Operator\Join::LEFT);
        $condition = $join->on([], Logical::_OR_);
        $condition->set("a", "b", Comparison::GREATER);
        $condition->set("c", "d", Comparison::DIFFERS);
        return new Result($join->toString() == "LEFT OUTER JOIN x ON a > b OR c != d");
    }
        

}
