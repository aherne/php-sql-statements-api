<?php
namespace Test\Lucinda\Query;
    
use Lucinda\Query\InsertSelect;
use Lucinda\Query\Select;
use Lucinda\UnitTest\Result;

class InsertSelectTest
{
    private $object;
    
    public function __construct()
    {
        $this->object = new InsertSelect("x");
    }

    public function columns()
    {
        $this->object->columns(["a", "b"]);
        return new Result(true); // tested by toString
    }
        

    public function select()
    {
        $select = new Select("y");
        $select->fields(["c", "d"]);
        $this->object->select($select);
        return new Result(true); // tested by toString
    }
        

    public function toString()
    {
        return new Result($this->object->toString()=="INSERT INTO x (a, b)\r\nSELECT\r\nc, d\r\nFROM y");
    }
        

}
