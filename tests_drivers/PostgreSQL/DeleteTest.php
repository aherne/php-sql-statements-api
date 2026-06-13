<?php

namespace Test\Lucinda\Query\Vendor\PostgreSQL;

use Lucinda\Query\Vendor\PostgreSQL\Delete;
use Lucinda\UnitTest\Result;
use Lucinda\UnitTest\Validator\Strings;

class DeleteTest
{
    private $object;

    public function __construct()
    {
        $this->object = new Delete("users");
    }

    public function where()
    {
        $this->object->where(["id"=>":id"])->setILike("name", "':name'");
        return new Result(true); // tested by toString
    }

    public function returning()
    {
        $this->object->returning(["id"]);
        return new Result(true); // tested by toString
    }

    public function toString()
    {
        return (new Strings($this->object->__toString()))->assertEquals("DELETE FROM users\r\nWHERE id = :id AND name ILIKE ':name'\r\nRETURNING id");
    }
    public function __toString()
    {
    }
        

}
