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
    
    public function __toString()
    {
        return new Result(((string) $this->object) == "a AS b");
    }

    public function toString()
    {
        return new Result($this->object->toString() == "a AS b");
    }
}
