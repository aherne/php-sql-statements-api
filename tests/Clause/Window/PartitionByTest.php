<?php
namespace Test\Lucinda\Query\Clause\Window;

use Lucinda\UnitTest\Result;

class PartitionByTest
{

    public function add()
    {
        return new Result(true);// tested by toString
    }


    public function toString()
    {
        $partitionBy = new \Lucinda\Query\Clause\Window\PartitionBy();
        $partitionBy->add("a");
        $partitionBy->add("b");
        return new Result((string) $partitionBy == "PARTITION BY a, b");
    }

    public function __toString(): string
    {
        return "OK";
    }
}
