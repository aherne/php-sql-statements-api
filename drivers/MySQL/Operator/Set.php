<?php
namespace Lucinda\Query;

use Lucinda\Query\Operator\Logical as DefaultSet;

/**
 * Enum encapsulating possible SQL set operators (grouping SELECT statements), extending standard SQL
 * WARNING: mysql doesn't support: INTERSECT & EXCEPT
 */
interface Set extends DefaultSet
{
    const UNION_DISTINCT = "UNION DISTINCT";
}
