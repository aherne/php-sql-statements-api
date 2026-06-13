<?php

namespace Lucinda\Query\Vendor\PostgreSQL\Operator;

/**
 * Enum encapsulating possible PostgreSQL WHERE comparison operators, extending those in standard SQL
 */
enum Comparison: string
{
    case ILIKE = "ILIKE";
    case NOT_ILIKE = "NOT ILIKE";
    case REGEXP = "~";
    case NOT_REGEXP = "!~";
    case REGEXP_CASE_INSENSITIVE = "~*";
    case NOT_REGEXP_CASE_INSENSITIVE = "!~*";
    case FULL_TEXT_MATCH = "@@";
}
