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
    }

    public function ignore()
    {
        $this->object->ignore();
        return new Result(true); // tested by toString
    }
        

    public function toString()
    {
        $this->object->set(["a"=>"s"]);
        $this->object->where(["d"=>"f"]);
        return new Result($this->object->toString()=="UPDATE IGNORE q\r\nSET a = s\r\nWHERE d = f");
    }
        

}
