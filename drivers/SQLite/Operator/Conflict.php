<?php

namespace Lucinda\Query\Vendor\SQLite\Operator;

/**
 * Enum encapsulating SQLite INSERT OR conflict resolution algorithms
 */
enum Conflict: string
{
    case ROLLBACK = "ROLLBACK";
    case ABORT = "ABORT";
    case FAIL = "FAIL";
    case IGNORE = "IGNORE";
    case REPLACE = "REPLACE";
}
