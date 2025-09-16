<?php

namespace Lucinda\Query\Vendor\MySQL\Operator;

/**
 * Enum encapsulating possible MySQL WHERE comparison operators, extending those in standard SQL
 */
enum Comparison: string
{
    // regular expression comparison operators
    case REGEXP = "REGEXP";
    case NOT_REGEXP = "NOT REGEXP";
    // against operator for fulltext search
    case AGAINST = "AGAINST";
}
