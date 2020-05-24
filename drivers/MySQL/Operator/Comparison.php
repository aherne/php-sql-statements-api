<?php
namespace Lucinda\Query\Vendor\MySQL\Operator;

use Lucinda\Query\Operator\Comparison as DefaultComparison;

/**
 * Enum encapsulating possible MySQL WHERE comparison operators, extending those in standard SQL
 */
interface Comparison extends DefaultComparison
{
    // regular expression comparison operators
    const REGEXP = "REGEXP";
    const NOT_REGEXP = "NOT REGEXP";
}
