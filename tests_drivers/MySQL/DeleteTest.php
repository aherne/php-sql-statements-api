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
    }

    public function ignore()
    {
        $this->object->ignore();
        return new Result(true); // tested by toString
    }

    public function where()
    {
        $this->object->where(["x"=>"c"]);
        return new Result(true); // tested by toString
    }


    public function toString()
    {
        return new Result($this->object->__toString()=="DELETE IGNORE FROM q\r\nWHERE x = c");
    }

    public function __toString(): string
    {
        return "OK";
    }
}
