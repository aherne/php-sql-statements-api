<?php
namespace Test\Lucinda\Query;
    
use Lucinda\Query\Update;
use Lucinda\UnitTest\Result;

class UpdateTest
{
    private $object;
    
    public function __construct()
    {
        $this->object = new Update("q");
    }

    public function set()
    {
        $this->object->set(["a"=>"s"]);
        return new Result(true); // tested by toString
    }
        

    public function where()
    {
        $this->object->where(["d"=>"f"]);
        return new Result(true); // tested by toString
    }
        

    public function toString()
    {
        return new Result($this->object->toString()=="UPDATE q\r\nSET a = s\r\nWHERE d = f");
    }
        

}
