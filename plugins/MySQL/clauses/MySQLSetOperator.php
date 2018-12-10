<?php
namespace Lucinda\Query;

require_once(dirname(dirname(dirname(__DIR__)))."/src/clauses/SetOperator.php");

/**
 * Enum encapsulating possible SQL set operators (grouping SELECT statements), extending standard SQL
 * WARNING: mysql doesn't support: INTERSECT & EXCEPT
 */
interface MySQLSetOperator extends SetOperator {
	const UNION_DISTINCT = "UNION DISTINCT";
}