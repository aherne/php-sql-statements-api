<?php
namespace Lucinda\Query;

require_once("clauses/MySQLCondition.php");
require_once(dirname(dirname(__DIR__))."/src/Select.php");

/**
 * Encapsulates MySQL statement:
 * SELECT {FIELDS}
 * FROM {TABLE}
 * {TYPE} JOIN ON {CONDITION}
 * ...
 * WHERE {CONDITION}
 * GROUP BY {COLUMNS}
 * HAVING {CONDITION}
 * ORDER BY {ORDER_BY}
 * LIMIT {LIMIT}
 */
class MySQLSelect extends Select
{
}