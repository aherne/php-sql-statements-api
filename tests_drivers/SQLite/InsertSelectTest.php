<?php

namespace Test\Lucinda\Query\Vendor\SQLite;

use Lucinda\Query\Vendor\SQLite\InsertSelect;
use Lucinda\Query\Vendor\SQLite\Operator\Conflict;
use Lucinda\Query\Vendor\SQLite\Select;
use Lucinda\UnitTest\Result;

class InsertSelectTest
{
    private $object;

    public function __construct()
    {
        $this->object = new InsertSelect("users");
        $this->object->columns(["id", "name"]);
        $select = new Select("legacy_users");
        $select->fields(["legacy_id", "legacy_name"]);
        $this->object->select($select);
    }

    public function or()
    {
        $object = clone $this->object;
        $object->or(Conflict::FAIL);
        return new Result($this->normalize($object->__toString())=="INSERT OR FAIL INTO users (id, name) SELECT legacy_id, legacy_name FROM legacy_users");
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
        return new Result($this->normalize($object->__toString())=="INSERT OR REPLACE INTO users (id, name) SELECT legacy_id, legacy_name FROM legacy_users");
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
        $this->object->returning(["id"]);
        return new Result(true);
    }

    public function toString()
    {
        return new Result($this->normalize($this->object->__toString())=="INSERT OR IGNORE INTO users (id, name) SELECT legacy_id, legacy_name FROM legacy_users ON CONFLICT (id) DO UPDATE SET name = excluded.name RETURNING id");
    }

    private function normalize(string $string): string
    {
        return preg_replace("/\s+/", " ", str_replace(["\n", "\r"], " ", $string));
    }
    public function __toString()
    {
    }
        

}
