<?php
namespace Lucinda\Query;

require_once(dirname(dirname(dirname(__DIR__)))."/src/clauses/LogicalOperator.php");

interface MySQLLogicalOperator extends LogicalOperator {
	const _XOR_ = "XOR";
}