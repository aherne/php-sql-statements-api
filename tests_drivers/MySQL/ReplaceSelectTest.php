<?php
namespace Test\Lucinda\Query\Vendor\MySQL;

use Lucinda\Query\Vendor\MySQL\ReplaceSelect;
use Lucinda\Query\Vendor\MySQL\Select;
use Lucinda\UnitTest\Result;

class ReplaceSelectTest
{
    public function toString()
    {
        $object = new ReplaceSelect("x");
        $object->columns(["a", "b"]);
        $select = new Select("y");
        $select->fields(["c", "d"]);
        $object->select($select);
        return new Result($object->toString()=="REPLACE INTO x (a, b)\r\nSELECT \r\nc, d\r\nFROM y");
    }
}
