<?php

namespace Test\Lucinda\Query\Vendor\PostgreSQL;

use Lucinda\Query\Vendor\PostgreSQL\InsertSelect;
use Lucinda\Query\Vendor\PostgreSQL\Select;
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

    public function onConflictDoNothing()
    {
        $this->object->onConflictDoNothing(["id"]);
        return new Result(true);
    }

    public function onConflictDoUpdate()
    {
        $this->object->onConflictDoUpdate(["id"])->set("name", "EXCLUDED.name");
        return new Result(true);
    }

    public function onConstraintDoNothing()
    {
        $object = clone $this->object;
        $object->onConstraintDoNothing("users_pkey");
        return new Result($this->normalize($object->__toString())=="INSERT INTO users (id, name) SELECT legacy_id, legacy_name FROM legacy_users ON CONFLICT ON CONSTRAINT users_pkey DO NOTHING");
    }

    public function onConstraintDoUpdate()
    {
        $object = clone $this->object;
        $object->onConstraintDoUpdate("users_pkey", ["name"=>"EXCLUDED.name"]);
        return new Result($this->normalize($object->__toString())=="INSERT INTO users (id, name) SELECT legacy_id, legacy_name FROM legacy_users ON CONFLICT ON CONSTRAINT users_pkey DO UPDATE SET name = EXCLUDED.name");
    }

    public function returning()
    {
        $this->object->returning(["id"]);
        return new Result(true);
    }

    public function toString()
    {
        return new Result($this->normalize($this->object->__toString())=="INSERT INTO users (id, name) SELECT legacy_id, legacy_name FROM legacy_users ON CONFLICT (id) DO UPDATE SET name = EXCLUDED.name RETURNING id");
    }

    private function normalize(string $string): string
    {
        return preg_replace("/\s+/", " ", str_replace(["\n", "\r"], " ", $string));
    }
    public function __toString()
    {
    }
        

}
