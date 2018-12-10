<?php
require_once("../test.php");
require_once("../../src/Stringable.php");
require_once("../../src/clauses/Row.php");

$clause = new Lucinda\Query\Row(["a","b"]);
test($clause->toString(), "(a, b)");
