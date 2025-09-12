<?php
namespace Test\Lucinda\Query\Clause\Window;
    
use Lucinda\Query\Clause\OrderBy;
use Lucinda\Query\Clause\Window\Over;
use Lucinda\Query\Clause\Window\PartitionBy;
use Lucinda\UnitTest\Result;

class OverTest
{

    public function toString()
    {
        $over = new Over(new PartitionBy(["foo", "bar"]), new OrderBy(["dfg"]));
        return new Result($over->toString() == "PARTITION BY foo, bar ORDER BY dfg ASC");
    }
        

}
