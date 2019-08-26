<?php
require_once("test.php");
require_once("../src/Update.php");
require_once("../plugins/MySQL/MySQLUpdate.php");

$statement = new Lucinda\Query\Update("asd");
$statement->set(["a"=>"b"]);
$statement->where(["c"=>"d"]);
test($statement->toString(), "UPDATE asd\r\nSET a = b\r\nWHERE c = d");

$statement = new Lucinda\Query\Update("asd");
$statement->set()
    ->set("a", "b");
$statement->where()
    ->set("c", "d");
test($statement->toString(), "UPDATE asd\r\nSET a = b\r\nWHERE c = d");

$statement = new Lucinda\Query\Update("asd");
$statement->set(["a"=>"b"]);
test($statement->toString(), "UPDATE asd\r\nSET a = b");


$statement = new Lucinda\Query\MySQLUpdate("asd");
$statement->ignore();
$statement->set(["a"=>"b"]);
test($statement->toString(), "UPDATE IGNORE asd\r\nSET a = b");
