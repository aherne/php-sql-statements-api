<?php
namespace Test\Lucinda\Query\Clause;
    
use Lucinda\Query\Clause\With;
use Lucinda\Query\Select;
use Lucinda\UnitTest\Result;

class WithTest
{

    public function addSelect()
    {
        return new Result(true); // tested by toString
    }
        

    public function toString()
    {
        $with = new With(true);

        $select1 = new Select("tbl1");
        $select1->fields()->add("a");

        $select2 = new Select("tbl2");
        $select2->fields()->add("b");

        $with->addSelect("foo", $select1);
        $with->addSelect("bar", $select2);
        $normalized = preg_replace("/\s+/", " ", str_replace(["\r", "\n"], " ", $with->toString()));
        return new Result($normalized == "WITH RECURSIVE foo AS ( SELECT a FROM tbl1 ), bar AS ( SELECT b FROM tbl2 )");
    }
        

}
