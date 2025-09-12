<?php
namespace Test\Lucinda\Query;

use Lucinda\Query\SelectGroup;
use Lucinda\Query\Operator\Set;
use Lucinda\Query\Select;
use Lucinda\UnitTest\Result;

class SelectGroupTest
{
    private $object;
    
    public function __construct()
    {
        $this->object = new SelectGroup(Set::UNION_ALL);
    }

    public function addSelect()
    {
        $select1 = new Select("t1");
        $select1->fields(["b", "c"]);
        $this->object->addSelect($select1);
        
        $select2 = new Select("t2");
        $select2->fields(["d", "e"]);
        $this->object->addSelect($select2);
        
        return new Result(true); // tested by toString
    }
        

    public function orderBy()
    {
        $this->object->orderBy(["b"]);
        return new Result(true); // tested by toString
    }
        

    public function limit()
    {
        $this->object->limit(10);
        return new Result(true); // tested by toString
    }
        

    public function __toString()
    {
        return $this->object->toString();
    }
        

    public function toString()
    {
        return new Result($this->object->toString()=="(\r\nSELECT\r\nb, c\r\nFROM t1\r\n)\r\nUNION ALL\r\n(\r\nSELECT\r\nd, e\r\nFROM t2\r\n)\r\nORDER BY b ASC\r\nLIMIT 10 OFFSET 0");
    }
}
