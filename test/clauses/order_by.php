<?php
require_once("../test.php");
require_once("../../src/Stringable.php");
require_once("../../src/clauses/OrderBy.php");

$fields = new Lucinda\Query\OrderBy(["a"]);
test($fields->toString(), "a ASC");

$fields = new Lucinda\Query\OrderBy(["a", "b"]);
test($fields->toString(), "a ASC, b ASC");

$fields = new Lucinda\Query\OrderBy();
$fields->add("a", \Lucinda\Query\OrderByOperator::ASC);
$fields->add("b", \Lucinda\Query\OrderByOperator::DESC);
test($fields->toString(), "a ASC, b DESC");
