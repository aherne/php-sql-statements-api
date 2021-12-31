<?php
namespace Test\Lucinda\Query\Vendor\MySQL;

use Lucinda\Query\Vendor\MySQL\Update;
use Lucinda\UnitTest\Result;

class UpdateTest
{
    private $object;
    
    public function __construct()
    {
        $this->object = new Update("q");
        $this->object->set(["a"=>"s"]);
    }

    public function ignore()
    {
        $this->object->ignore();
        return new Result(true); // tested by toString
    }
    
    public function where()
    {
        $this->object->where(["d"=>"f"]);
        return new Result(true); // tested by toString
    }

    public function toString()
    {
        return new Result($this->object->__toString()=="UPDATE IGNORE q\r\nSET a = s\r\nWHERE d = f");
    }

    public function __toString():string
    {
        return "OK";
    }

}
