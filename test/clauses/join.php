<?php
require_once("../test.php");
require_once("../../src/Stringable.php");
require_once("../../src/clauses/Join.php");

$join = new \Lucinda\Query\Join("asd", "d");
$join->on()
    ->set("asd", "def");
test($join->toString(), "INNER JOIN asd AS d ON asd = def");

$join = new \Lucinda\Query\Join("asd", null, \Lucinda\Query\JoinOperator::LEFT);
$join->on(["asd"=>"def", "mmm"=>"xxx"]);
test($join->toString(), "LEFT OUTER JOIN asd ON asd = def AND mmm = xxx");
