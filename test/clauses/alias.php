<?php
require_once("../test.php");
require_once("../../src/clauses/Alias.php");

$clause = new Lucinda\Query\Alias("x", "y");
test($clause->toString(), "x AS y");