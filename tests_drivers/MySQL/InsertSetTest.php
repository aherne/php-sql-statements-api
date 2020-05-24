<?php
namespace Test\Lucinda\Query\Vendor\MySQL;
    
use Lucinda\Query\Vendor\MySQL\InsertSet;
use Lucinda\UnitTest\Result;

class InsertSetTest
{
    private $object;
    
    public function __construct()
    {
        $this->object = new InsertSet("x");
    }

    public function ignore()
    {
        $this->object->ignore();
        return new Result(true); // tested by toString
    }
        

    public function set()
    {
        $this->object->set(["a"=>"b"]);
        return new Result(true); // tested by toString
    }
        

    public function onDuplicateKeyUpdate()
    {
        $this->object->onDuplicateKeyUpdate(["e"=>"e + 1"]);
        return new Result(true); // tested by toString
    }
        

    public function toString()
    {
        return new Result($this->object->toString()=="INSERT IGNORE INTO x SET\r\na = b\r\nON DUPLICATE KEY UPDATE e = e + 1");
    }
        

}
