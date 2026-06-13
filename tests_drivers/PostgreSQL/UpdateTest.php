<?php

namespace Test\Lucinda\Query\Vendor\PostgreSQL;

use Lucinda\Query\Vendor\PostgreSQL\Update;
use Lucinda\UnitTest\Result;

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
        $this->object->where(["id"=>":id"])->setRegexp("name", "':prefix'");
        return new Result(true); // tested by toString
    }

    public function returning()
    {
        $this->object->returning(["id", "name"]);
        return new Result(true); // tested by toString
    }

    public function toString()
    {
        return new Result($this->object->__toString()=="UPDATE users\r\nSET name = :name\r\nWHERE id = :id AND name ~ ':prefix'\r\nRETURNING id, name");
    }
    public function __toString()
    {
    }
        

}
