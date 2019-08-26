<?php
require_once("test.php");
require_once("../src/Delete.php");
require_once("../plugins/MySQL/MySQLDelete.php");

$statement = new Lucinda\Query\Delete("asd");
$statement->where(["x"=>"y"]);
test($statement->toString(), "DELETE FROM asd\r\nWHERE x = y");

$statement = new Lucinda\Query\Delete("asd");
$statement->where()
    ->set("x", "y");
test($statement->toString(), "DELETE FROM asd\r\nWHERE x = y");

$statement = new Lucinda\Query\Delete("asd");
test($statement->toString(), "DELETE FROM asd");

$statement = new Lucinda\Query\MySQLDelete("asd");
$statement->ignore();
$statement->where()
    ->set("x", "y");
test($statement->toString(), "DELETE IGNORE FROM asd\r\nWHERE x = y");
