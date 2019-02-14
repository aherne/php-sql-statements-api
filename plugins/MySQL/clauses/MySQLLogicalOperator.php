<?php
namespace Lucinda\Query;

require_once(dirname(__DIR__, 3)."/src/clauses/LogicalOperator.php");

/**
 * Enum encapsulating possible MySQL logical operators, extending those in standard SQL
 */
interface MySQLLogicalOperator extends LogicalOperator {
	const _XOR_ = "XOR";
}