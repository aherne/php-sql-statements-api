<?php

namespace Test\Lucinda\Query\Vendor\SQLite;

use Lucinda\Query\Vendor\SQLite\Update;
use Lucinda\UnitTest\Result;
use Lucinda\UnitTest\Validator\Strings;

class UpdateTest
{
    private $object;

    public function __construct()
    {
        $this->object = new Update("users");
        $this->object->set(["name"=>":name"]);
    }

    public function where()
    {
        $this->object->where(["id"=>":id"])->setGlob("name", "'Lu*'");
        return new Result(true); // tested by toString
    }

    public function returning()
    {
        $this->object->returning(["id", "name"]);
        return new Result(true); // tested by toString
    }

    public function toString()
    {
        return (new Strings($this->object->__toString()))->assertEquals("UPDATE users\r\nSET name = :name\r\nWHERE id = :id AND name GLOB 'Lu*'\r\nRETURNING id, name");
    }
    public function __toString()
    {
    }
        

}
