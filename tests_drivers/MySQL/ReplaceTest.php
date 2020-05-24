<?php
namespace Test\Lucinda\Query\Vendor\MySQL;

use Lucinda\Query\Vendor\MySQL\Replace;
use Lucinda\UnitTest\Result;

class ReplaceTest
{
    private $object;
    
    public function __construct()
    {
        $this->object = new Replace("x");
        $this->object->columns(["a", "b"]);
        $this->object->values([1, 2]);
    }
        
    public function toString()
    {
        return new Result($this->object->toString()=="REPLACE INTO x (a, b) VALUES\r\n(1, 2)");
    }
    
    
}
