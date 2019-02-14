<?php
namespace Lucinda\Query;

require_once(dirname(__DIR__, 2)."/src/SelectGroup.php");
require_once("clauses/MySQLCondition.php");
require_once("clauses/MySQLSetOperator.php");

/**
 * Encapsulates MySQL statement grouping two or more SELECT statements:
 * ({SELECT})
 * {OPERATOR}
 * ({SELECT})
 * ...
 * ORDER BY {ORDER_BY}
 * LIMIT {LIMIT}
 */
class MySQLSelectGroup extends SelectGroup
{
}