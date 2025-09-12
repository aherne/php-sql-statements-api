<?php
namespace Test\Lucinda\Query\Clause;

use Lucinda\Query\Clause\Alias;
use Lucinda\UnitTest\Result;

class AliasTest
{
    private $object;
    
    public function __construct()
    {
        $this->object = new Alias("a", "b");
    }
    
    public function toString()
    {
        return new Result($this->object->toString() == "a AS b");
    }
    public function __toString()
    {
        return $this->object->toString();
    }

}