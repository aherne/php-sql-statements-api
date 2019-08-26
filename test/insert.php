<?php
require_once("test.php");
require_once("../src/Insert.php");
require_once("../src/InsertSelect.php");
require_once("../plugins/MySQL/MySQLInsert.php");
require_once("../plugins/MySQL/MySQLInsertSelect.php");
require_once("../plugins/MySQL/MySQLInsertSet.php");

$statement = new Lucinda\Query\Insert("asd");
$statement->columns(["a","s","d"]);
$statement->values(["1","3","4"]);
test($statement->toString(), "INSERT INTO asd (a, s, d) VALUES \r\n(1, 3, 4)");


$statement = new Lucinda\Query\Insert("asd");
$statement->columns(["a","s","d"]);
$statement->values(["1","2","3"]);
$statement->values(["4","5","6"]);
test($statement->toString(), "INSERT INTO asd (a, s, d) VALUES \r\n(1, 2, 3), (4, 5, 6)");

$subselect = new \Lucinda\Query\Select("fff");
$subselect->fields(["k","l",12]);
$statement = new Lucinda\Query\InsertSelect("asd");
$statement->columns(["a","s","d"]);
$statement->select($subselect);
test($statement->toString(), "INSERT INTO asd (a, s, d)\r\nSELECT\r\nk, l, 12\r\nFROM fff");

$statement = new Lucinda\Query\MySQLInsert("asd");
$statement->ignore();
$statement->columns(["a","s","d"]);
$statement->values([":a",":s",":d"]);
test($statement->toString(), "INSERT IGNORE INTO asd (a, s, d) VALUES \r\n(:a, :s, :d)");

$statement = new Lucinda\Query\MySQLInsert("asd");
$statement->ignore();
$statement->columns(["a","s","d"]);
$statement->values([":a",":s",":d"]);
$statement->onDuplicateKeyUpdate(["f"=>"g"]);
test($statement->toString(), "INSERT IGNORE INTO asd (a, s, d) VALUES \r\n(:a, :s, :d)\r\nON DUPLICATE KEY UPDATE f = g");

$subselect = new \Lucinda\Query\Select("fff");
$subselect->fields(["k","l",12]);
$statement = new Lucinda\Query\MySQLInsertSelect("asd");
$statement->ignore();
$statement->columns(["a","s","d"]);
$statement->select($subselect);
test($statement->toString(), "INSERT IGNORE INTO asd (a, s, d)\r\nSELECT\r\nk, l, 12\r\nFROM fff");


$statement = new Lucinda\Query\MySQLInsertSet("asd");
$statement->set(["a"=>"b", "c"=>"d"]);
test($statement->toString(), "INSERT  INTO asd SET\r\na = b, c = d");
