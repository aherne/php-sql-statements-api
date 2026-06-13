<?php

namespace Test\Lucinda\Query\Vendor\PostgreSQL;

use Lucinda\Query\Vendor\PostgreSQL\Select;
use Lucinda\UnitTest\Result;

class SelectTest
{
    private $object;

    public function __construct()
    {
        $this->object = new Select("users");
        $this->object->fields(["id", "name"]);
    }

    public function where()
    {
        $this->object->where()->setILike("name", "':name'");
        return new Result(true); // tested by toString
    }

    public function having()
    {
        $select = new Select("users");
        $select->fields(["department_id", "COUNT(*) AS total"]);
        $select->groupBy(["department_id"]);
        $select->having()->setFullTextSearch("to_tsvector('english', department_name)", "plainto_tsquery('english', :q)");
        $normalized = $this->normalize($select->__toString());
        return new Result($normalized=="SELECT department_id, COUNT(*) AS total FROM users GROUP BY department_id HAVING to_tsvector('english', department_name) @@ plainto_tsquery('english', :q)");
    }

    public function toString()
    {
        return new Result($this->normalize($this->object->__toString())=="SELECT id, name FROM users WHERE name ILIKE ':name'");
    }

    private function normalize(string $string): string
    {
        return preg_replace("/\s+/", " ", str_replace(["\n", "\r"], " ", $string));
    }
}
