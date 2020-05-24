<?php
require_once("test.php");
require_once("../src/Select.php");
require_once("../src/SelectGroup.php");
require_once("../drivers/MySQL/Select.php");

$statement = new Lucinda\Query\Select("asd", "k");
$statement->distinct();
$statement->fields(["a","s","d"]);
$statement->joinLeft("mmm", "z")
    ->on(["k.x"=>"z.y"]);
$statement->joinCross("eee");
$statement->where(["k.x"=>12]);
$statement->groupBy(["a","b"]);
$statement->having()
    ->set("x", 18, \Lucinda\Query\ComparisonOperator::GREATER);
$statement->orderBy(["m","n"]);
$statement->limit(10);
test($statement->toString(), "SELECT DISTINCT\r\na, s, d\r\nFROM asd AS k\r\nLEFT OUTER JOIN mmm AS z ON k.x = z.y\r\nCROSS JOIN eee\r\nWHERE k.x = 12\r\nGROUP BY a, b\r\nHAVING x > 18\r\nORDER BY m ASC, n ASC\r\nLIMIT 10");

$statement = new \Lucinda\Query\SelectGroup();
$statement->addSelect(new Lucinda\Query\Select("asd", "k"));
$statement->addSelect(new Lucinda\Query\Select("fgh", "h"));
$statement->orderBy(["k","z"]);
$statement->limit(10, 4);
test($statement->toString(), "(\r\nSELECT\r\n*\r\nFROM asd AS k\r\n)\r\nUNION\r\n(\r\nSELECT\r\n*\r\nFROM fgh AS h\r\n)\r\n\r\nORDER BY k ASC, z ASC\r\nLIMIT 10 OFFSET 4");


$statement = new Lucinda\Query\Select("asd", "k");
$statement->distinct();
$statement->fields(["a","s","d"]);
$statement->joinLeft("mmm", "z")
    ->on(["k.x"=>"z.y"]);
$statement->joinCross("eee");
$statement->where(["k.x"=>12]);
$statement->groupBy(["a","b"]);
$statement->having()
    ->set("x", 18, \Lucinda\Query\ComparisonOperator::GREATER);
$statement->orderBy(["m","n"]);
$statement->limit(10);
test($statement->toString(), "SELECT  DISTINCT\r\na, s, d\r\nFROM asd AS k\r\nLEFT OUTER JOIN mmm AS z ON k.x = z.y\r\nCROSS JOIN eee\r\nWHERE k.x = 12\r\nGROUP BY a, b\r\nHAVING x > 18\r\nORDER BY m ASC, n ASC\r\nLIMIT 10");
