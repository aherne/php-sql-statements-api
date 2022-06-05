<?php

namespace Lucinda\Query\Operator;

/**
 * Enum encapsulating possible SQL WHERE comparison operators
 */
enum Comparison: string
{
    // simple comparison operators
    case EQUALS = "=";
    case GREATER = ">";
    case LESSER = "<";
    case DIFFERS = "!=";
    case GREATER_EQUALS = ">=";
    case LESSER_EQUALS = "<=";
    case NULL_SAFE_EQUALS = "<=>";

    // pattern comparison operators
    case LIKE ="LIKE";
    case NOT_LIKE ="NOT LIKE";

    // in comparison
    case IN = "IN";
    case NOT_IN = "NOT IN";

    // range comparison operators
    case BETWEEN ="BETWEEN";
    case NOT_BETWEEN = "NOT BETWEEN";

    // null operators
    case IS_NULL = "IS NULL";
    case IS_NOT_NULL = "IS NOT NULL";
}
