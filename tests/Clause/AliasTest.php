<?php

namespace Test\Lucinda\Query\Clause;

use Lucinda\Query\Clause\Alias;
use Lucinda\UnitTest\Validator\Strings;

class AliasTest
{
    private $object;

    public function __construct()
    {
        $this->object = new Alias("a", "b");
    }

    public function toString()
    {
        return (new Strings(((string) $this->object)))->assertEquals("a AS b");
    }

    public function __toString(): string
    {
        return "OK";
    }
}
