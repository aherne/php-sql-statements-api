<?php
namespace Test\Lucinda\Query;

use Lucinda\Query\Insert;
use Lucinda\UnitTest\Result;

class InsertTest
{
    private $object;
    
    public function __construct()
    {
        $this->object = new Insert("x");
    }

    public function columns()
    {
        $this->object->columns(["a", "b"]);
        return new Result(true); // tested by toString
    }
        

    public function values()
    {
        $this->object->values([1, 2]);
        $this->object->values([3, 4]);
        return new Result(true); // tested by toString
    }
        

    public function toString()
    {
        return new Result($this->object->__toString()=="INSERT INTO x (a, b) VALUES\r\n(1, 2), (3, 4)");
    }

    public function __toString(): string
    {
        return "OK";
    }
}
