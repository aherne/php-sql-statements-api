<?php
require_once("../test.php");
require_once("../../src/Stringable.php");
require_once("../../src/clauses/Condition.php");

$clause = new Lucinda\Query\Condition(["a"=>"b", "c"=>"d"]);
test($clause->toString(), "a = b AND c = d");

$clause = new Lucinda\Query\Condition();
$clause->set("a", "b");
$clause->set("c", "'d'", Lucinda\Query\ComparisonOperator::GREATER);
$clause->setBetween("e", "'f'", "'g'");
$clause->setBetween("h", "'i'", "'j'", false);
$clause->setIn("k", [1,2]);
$clause->setIn("l", [3,4], false);
$clause->setIsNull("m");
$clause->setIsNull("n", false);
$clause->setLike("o", "'%p%'");
$clause->setLike("r", "%s", false);
test($clause->toString(), "a = b AND c > 'd' AND e BETWEEN 'f' AND 'g' AND h NOT BETWEEN 'i' AND 'j' AND k IN (1,2) AND l NOT IN (3,4) AND m IS NULL AND n IS NOT NULL AND o LIKE '%p%' AND r NOT LIKE %s");

$clause = new Lucinda\Query\Condition();
$clause->set("a", "b");
$clause->setGroup(
    (new Lucinda\Query\Condition(array(), Lucinda\Query\LogicalOperator::_OR_))
    ->set("c", "d")
    ->set("e", "f")
);
test($clause->toString(), "a = b AND (c = d OR e = f)");
