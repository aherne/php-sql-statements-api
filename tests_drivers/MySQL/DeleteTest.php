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

    public function joinLeft()
    {
        $this->object->joinLeft("w")->on(["q.z"=>"w.b"]);
        return new Result(true); // tested by toString
    }


    public function joinRight()
    {
        $this->object->joinRight("e")->on(["q.x"=>"e.n"]);
        return new Result(true); // tested by toString
    }


    public function joinInner()
    {
        $this->object->joinInner("r")->on(["q.c"=>"r.m"]);
        return new Result(true); // tested by toString
    }


    public function joinCross()
    {
        $this->object->joinCross("t")->on(["q.v"=>"t.k"]);
        return new Result(true); // tested by toString
    }

    public function toString()
    {
        $normalized = $this->normalize((string) $this->object);
        return new Result($normalized == "DELETE IGNORE FROM q LEFT OUTER JOIN w ON q.z = w.b RIGHT OUTER JOIN e ON q.x = e.n INNER JOIN r ON q.c = r.m CROSS JOIN t ON q.v = t.k WHERE x = c");
    }


    private function normalize(string $string): string
    {
        return  preg_replace("/\s+/", " ", str_replace(["\n", "\r"], " ", $string));
    }

    public function __toString(): string
    {
        return "OK";
    }
}
