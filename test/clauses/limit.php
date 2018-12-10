<?php
require_once("../test.php");
require_once("../../src/clauses/Limit.php");

$clause = new Lucinda\Query\Limit("10");
test($clause->toString(), "10");

$clause = new Lucinda\Query\Limit("10", "20");
test($clause->toString(), "10 OFFSET 20");