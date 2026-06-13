<?php

namespace Test\Lucinda\Query\Clause;

use Lucinda\Query\Clause\Row;
use Lucinda\UnitTest\Result;
use Lucinda\UnitTest\Validator\Strings;

class RowTest
{
    public function isEmpty()
    {
        $row = new Row(["a", "b", "c"]);
        return new Result(!$row->isEmpty());
    }

    public function toString()
    {
        $row = new Row(["a", "b", "c"]);
        return (new Strings($row->__toString()))->assertEquals("(a, b, c)");
    }

    public function __toString(): string
    {
        return "OK";
    }
}
