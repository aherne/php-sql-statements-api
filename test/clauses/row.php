<?php
require_once("../test.php");
require_once("../../src/Stringable.php");
require_once("../../src/Clause/Row.php");

$clause = new Lucinda\Query\Row(["a","b"]);
test($clause->toString(), "(a, b)");
