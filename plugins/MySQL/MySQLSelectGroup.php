<?php
namespace Lucinda\Query;

require_once(dirname(dirname(__DIR__))."/src/SelectGroup.php");
require_once("clauses/MySQLCondition.php");
require_once("clauses/MySQLSetOperator.php");

class MySQLSelectGroup extends SelectGroup
{
}