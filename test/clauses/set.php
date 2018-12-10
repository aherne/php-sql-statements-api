<?php
require_once("../test.php");
require_once("../../src/clauses/Set.php");

$clause = new Lucinda\Query\Set(["a"=>"b", "c"=>"d"]);
test($clause->toString(), "a = b, c = d");


$clause = new Lucinda\Query\Set();
$clause->set("a", "b");
$clause->set("c", "d");
test($clause->toString(), "a = b, c = d");