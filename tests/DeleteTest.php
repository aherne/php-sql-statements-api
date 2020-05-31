<?php
namespace Test\Lucinda\Query;

use Lucinda\Query\Delete;
use Lucinda\UnitTest\Result;

class DeleteTest
{
    private $object;
    
    public function __construct()
    {
        $this->object = new Delete("x");
    }

    public function where()
    {
        $this->object->where(["a"=>"b", "c"=>"d"]);
        return new Result(true); // tested by toString
    }
        

    public function toString()
    {
        return new Result($this->object->toString()=="DELETE FROM x\r\nWHERE a = b AND c = d");
    }
}
