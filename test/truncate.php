<?php
require_once("test.php");
require_once("../src/Truncate.php");

$statement = new Lucinda\Query\Truncate("asd");
test($statement->toString(), "TRUNCATE TABLE asd");
