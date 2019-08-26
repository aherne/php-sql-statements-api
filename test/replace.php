<?php
require_once("test.php");
require_once("../plugins/MySQL/MySQLReplace.php");
require_once("../plugins/MySQL/MySQLReplaceSelect.php");
require_once("../plugins/MySQL/MySQLReplaceSet.php");

$statement = new Lucinda\Query\MySQLReplace("asd");
$statement->columns(["a","s","d"]);
$statement->values([":a",":s",":d"]);
test($statement->toString(), "REPLACE INTO asd (a, s, d) VALUES \r\n(:a, :s, :d)");


$statement = new Lucinda\Query\MySQLReplace("asd");
$statement->columns(["a","s","d"]);
$statement->values(["1","2","3"]);
$statement->values(["4","5","6"]);
test($statement->toString(), "REPLACE INTO asd (a, s, d) VALUES \r\n(1, 2, 3), (4, 5, 6)");

$subselect = new \Lucinda\Query\Select("fff");
$subselect->fields(["k","l",12]);
$statement = new Lucinda\Query\MySQLReplaceSelect("asd");
$statement->columns(["a","s","d"]);
$statement->select($subselect);
test($statement->toString(), "REPLACE INTO asd (a, s, d)\r\nSELECT\r\nk, l, 12\r\nFROM fff");

$statement = new Lucinda\Query\MySQLReplaceSet("asd");
$statement->set(["a"=>"b", "c"=>"d"]);
test($statement->toString(), "REPLACE INTO asd SET\r\na = b, c = d");
