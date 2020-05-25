<?php
namespace Test\Lucinda\Query\Clause;

use Lucinda\Query\Clause\Row;
use Lucinda\UnitTest\Result;

class RowTest
{
    public function toString()
    {
        $row = new Row(["a", "b", "c"]);
        return new Result($row->toString()=="(a, b, c)");
    }
        

    public function isEmpty()
    {
        $row = new Row(["a", "b", "c"]);
        return new Result(!$row->isEmpty());
    }
}
