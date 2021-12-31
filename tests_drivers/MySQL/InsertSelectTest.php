<?php
namespace Test\Lucinda\Query\Vendor\MySQL;

use Lucinda\Query\Vendor\MySQL\InsertSelect;
use Lucinda\Query\Vendor\MySQL\Select;
use Lucinda\UnitTest\Result;

class InsertSelectTest
{
    private $object;
    
    public function __construct()
    {
        $this->object = new InsertSelect("x");
        $this->object->columns(["a", "b"]);
        $select = new Select("y");
        $select->fields(["c", "d"]);
        $this->object->select($select);
    }

    public function ignore()
    {
        $this->object->ignore();
        return new Result(true); // tested by toString
    }
        

    public function onDuplicateKeyUpdate()
    {
        $this->object->onDuplicateKeyUpdate(["e"=>"e + 1"]);
        return new Result(true); // tested by toString
    }
        

    public function toString()
    {
        return new Result($this->object->__toString()=="INSERT IGNORE INTO x (a, b)\r\nSELECT\r\nc, d\r\nFROM y\r\nON DUPLICATE KEY UPDATE e = e + 1");
    }

    public function __toString():string
    {
        return "OK";
    }

}
