<?php
namespace Test\Lucinda\Query\Clause;
    
use Lucinda\Query\Clause\Fields;
use Lucinda\UnitTest\Result;

class FieldsTest
{
    private $object;
    
    public function __construct()
    {
        $this->object = new Fields();
    }

    public function add()
    {
        $this->object->add("a", "b");
        $this->object->add("c");
        return new Result(true); // tested by toString
    }
        

    public function toString()
    {
        return new Result($this->object->toString()=="a AS b, c");
    }
        

    public function isEmpty()
    {
        return new Result(!$this->object->isEmpty());
    }
        

}
