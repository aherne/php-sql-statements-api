<?php
namespace Test\Lucinda\Query;

use Lucinda\Query\Select;
use Lucinda\UnitTest\Result;
use Lucinda\Query\Operator\Comparison;

class SelectTest
{
    private $object;
    
    public function __construct()
    {
        $this->object = new Select("q", "t1");
    }

    public function distinct()
    {
        $this->object->distinct();
        return new Result(true); // tested by toString
    }
        

    public function fields()
    {
        $this->object->fields(["a", "s"]);
        return new Result(true); // tested by toString
    }
        

    public function joinLeft()
    {
        $this->object->joinLeft("w", "t2")->on(["t1.z"=>"t2.b"]);
        return new Result(true); // tested by toString
    }
        

    public function joinRight()
    {
        $this->object->joinRight("e", "t3")->on(["t2.x"=>"t3.n"]);
        return new Result(true); // tested by toString
    }
        

    public function joinInner()
    {
        $this->object->joinInner("r", "t4")->on(["t3.c"=>"t4.m"]);
        return new Result(true); // tested by toString
    }
        

    public function joinCross()
    {
        $this->object->joinCross("t", "t5")->on(["t4.v"=>"t5.k"]);
        return new Result(true); // tested by toString
    }
        

    public function where()
    {
        $this->object->where(["t1.f"=>11]);
        return new Result(true); // tested by toString
    }
        

    public function groupBy()
    {
        $this->object->groupBy(["t1.id"]);
        return new Result(true); // tested by toString
    }
        

    public function having()
    {
        $this->object->having()->set("t2.g", 18, Comparison::GREATER);
        return new Result(true); // tested by toString
    }
        

    public function orderBy()
    {
        $this->object->orderBy(["t2.g"]);
        return new Result(true); // tested by toString
    }
        

    public function limit()
    {
        $this->object->limit(10, 20);
        return new Result(true); // tested by toString
    }
        

    public function __toString()
    {
        return new Result($this->object->toString()=="SELECT DISTINCT
a, s
FROM q AS t1
LEFT OUTER JOIN w AS t2 ON t1.z = t2.b
RIGHT OUTER JOIN e AS t3 ON t2.x = t3.n
INNER JOIN r AS t4 ON t3.c = t4.m
CROSS JOIN t AS t5 ON t4.v = t5.k
WHERE t1.f = 11
GROUP BY t1.id
HAVING t2.g > 18
ORDER BY t2.g ASC
LIMIT 10 OFFSET 20");
    }
        

    public function toString()
    {
        return new Result($this->object->toString()=="SELECT DISTINCT
a, s
FROM q AS t1
LEFT OUTER JOIN w AS t2 ON t1.z = t2.b
RIGHT OUTER JOIN e AS t3 ON t2.x = t3.n
INNER JOIN r AS t4 ON t3.c = t4.m
CROSS JOIN t AS t5 ON t4.v = t5.k
WHERE t1.f = 11
GROUP BY t1.id
HAVING t2.g > 18
ORDER BY t2.g ASC
LIMIT 10 OFFSET 20");
    }
}
