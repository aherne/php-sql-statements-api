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
        $query = str_replace("\n", " ", str_replace("\r", "", $this->object->__toString()));
        return new Result($query=="INSERT INTO x (a, b) SELECT c, d FROM y");
    }

    public function __toString(): string
    {
        return "OK";
    }

}
