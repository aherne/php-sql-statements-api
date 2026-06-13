<?php

namespace Lucinda\Query\Vendor\SQLite\Operator;

/**
 * Enum encapsulating possible SQLite WHERE comparison operators, extending those in standard SQL
 */
enum Comparison: string
{
    case GLOB = "GLOB";
    case NOT_GLOB = "NOT GLOB";
}
