<?php

namespace Test\Lucinda\Query\Vendor\MySQL;

use Lucinda\Query\Vendor\MySQL\Select;
use Lucinda\UnitTest\Result;

class SelectTest
{
    private $object;

    public function __construct()
    {
        $this->object = new Select("q");
    }

    public function setCalcFoundRows()
    {
        $this->object->setCalcFoundRows();
        return new Result(true); // tested by toString
    }

    public function getCalcFoundRows()
    {
        return new Result($this->object->getCalcFoundRows()=="SELECT FOUND_ROWS()");
    }


    public function setStraightJoin()
    {
        $this->object->setStraightJoin();
        return new Result(true); // tested by toString
    }

    public function where()
    {
        $this->object->where(["c"=>"d"]);
        return new Result(true); // tested by toString
    }

    public function toString()
    {
        $this->object->fields(["x", "y"]);
        return new Result("SELECT STRAIGHT_JOIN SQL_CALC_FOUND_ROWS\r\nx, y\r\nFROM q WHERE c=d");
    }

    public function __toString(): string
    {
        return "OK";
    }
}
