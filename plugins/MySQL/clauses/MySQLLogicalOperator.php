<?php
namespace Lucinda\Query;

require_once(dirname(dirname(dirname(__DIR__)))."/src/clauses/LogicalOperator.php");

/**
 * Enum encapsulating possible MySQL logical operators, extending those in standard SQL
 */
interface MySQLLogicalOperator extends LogicalOperator
{
    const _XOR_ = "XOR";
}
