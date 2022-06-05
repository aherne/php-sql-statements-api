<?php

namespace Lucinda\Query\Vendor\MySQL\Operator;

/**
 * Enum encapsulating possible SQL set operators (grouping SELECT statements), extending standard SQL
 * WARNING: mysql doesn't support: INTERSECT & EXCEPT
 */
enum Set : string
{
    case UNION_DISTINCT = "UNION DISTINCT";
}
