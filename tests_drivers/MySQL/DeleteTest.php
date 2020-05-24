<?php
namespace Test\Lucinda\Query\Vendor\MySQL;
    
use Lucinda\Query\Vendor\MySQL\Delete;
use Lucinda\UnitTest\Result;

class DeleteTest
{
    private $object;
    
    public function __construct()
    {
        $this->object = new Delete("q");
        $this->object->where(["x"=>"c"]);
    }

    public function ignore()
    {
        $this->object->ignore();
        return new Result(true); // tested by toString
    }
        

    public function toString()
    {
        return new Result($this->object->toString()=="DELETE IGNORE FROM q\r\nWHERE x = c");
    }
        

}
