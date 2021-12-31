<?php
namespace Test\Lucinda\Query\Vendor\MySQL;

use Lucinda\Query\Vendor\MySQL\Insert;
use Lucinda\UnitTest\Result;

class InsertTest
{
    private $object;
    
    public function __construct()
    {
        $this->object = new Insert("x");
        $this->object->columns(["a", "b"]);
        $this->object->values([1, 2]);
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
        return new Result($this->object->__toString()=="INSERT IGNORE INTO x (a, b) VALUES\r\n(1, 2)\r\nON DUPLICATE KEY UPDATE e = e + 1");
    }

    public function __toString():string
    {
        return "OK";
    }

}
