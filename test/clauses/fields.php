<?php
require_once("../test.php");
require_once("../../src/Stringable.php");
require_once("../../src/clauses/Fields.php");

$fields = new Lucinda\Query\Fields(["a","b"]);
test($fields->toString(), "a, b");

$fields = new Lucinda\Query\Fields();
$fields->add("a");
$fields->add("b", "c");
test($fields->toString(), "a, b AS c");
