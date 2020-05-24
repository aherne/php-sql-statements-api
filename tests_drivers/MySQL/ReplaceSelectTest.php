<?php
namespace Test\Lucinda\Query\Vendor\MySQL;
    
use Lucinda\Query\Vendor\MySQL\ReplaceSelect;
use Lucinda\Query\Vendor\MySQL\Select;
use Lucinda\UnitTest\Result;

class ReplaceSelectTest
{

    public function toString()
    {
        $this->object = new ReplaceSelect("x");
        $this->object->columns(["a", "b"]);
        $select = new Select("y");
        $select->fields(["c", "d"]);
        $this->object->select($select);
        return new Result($this->object->toString()=="REPLACE INTO x (a, b)\r\nSELECT \r\nc, d\r\nFROM y");
    }
    
}
