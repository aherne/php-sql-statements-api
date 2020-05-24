<?php
namespace Test\Lucinda\Query\Clause;
    
use Lucinda\Query\Clause\Columns;
use Lucinda\UnitTest\Result;

class ColumnsTest
{
    private $object;
    
    public function __construct()
    {
        $this->object = new Columns();
    }

    public function add()
    {
        $this->object->add("a");
        $this->object->add("b");
        return new Result(true);// tested by toString
        
    }
        

    public function toString()
    {
        return new Result($this->object->toString() == "a, b");
    }
        

    public function isEmpty()
    {
        return new Result(!$this->object->isEmpty());
    }
        

}
