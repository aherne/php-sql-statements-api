<?php

namespace Test\Lucinda\Query\Vendor\SQLite;

use Lucinda\Query\Vendor\SQLite\Insert;
use Lucinda\Query\Vendor\SQLite\Operator\Conflict;
use Lucinda\UnitTest\Result;
use Lucinda\UnitTest\Validator\Strings;

class InsertTest
{
    private $object;

    public function __construct()
    {
        $this->object = new Insert("users");
        $this->object->columns(["id", "name"]);
        $this->object->values([":id", ":name"]);
    }

    public function or()
    {
        $object = clone $this->object;
        $object->or(Conflict::FAIL);
        return (new Strings($this->normalize($object->__toString())))->assertEquals("INSERT OR FAIL INTO users (id, name) VALUES (:id, :name)");
    }

    public function orIgnore()
    {
        $this->object->orIgnore();
        return new Result(true); // tested by toString
    }

    public function orReplace()
    {
        $object = clone $this->object;
        $object->orReplace();
        return (new Strings($this->normalize($object->__toString())))->assertEquals("INSERT OR REPLACE INTO users (id, name) VALUES (:id, :name)");
    }

    public function onConflictDoNothing()
    {
        $this->object->onConflictDoNothing(["id"]);
        return new Result(true);
    }

    public function onConflictDoUpdate()
    {
        $this->object->onConflictDoUpdate(["id"])->set("name", "excluded.name");
        return new Result(true);
    }

    public function returning()
    {
        $this->object->returning(["id", "name"]);
        return new Result(true);
    }

    public function toString()
    {
        return (new Strings($this->normalize($this->object->__toString())))->assertEquals("INSERT OR IGNORE INTO users (id, name) VALUES (:id, :name) ON CONFLICT (id) DO UPDATE SET name = excluded.name RETURNING id, name");
    }

    private function normalize(string $string): string
    {
        return preg_replace("/\s+/", " ", str_replace(["\n", "\r"], " ", $string));
    }
    public function __toString()
    {
    }
        

}
