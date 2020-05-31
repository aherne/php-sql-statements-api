<?php
namespace Lucinda\Query\Operator;

/**
 * Enum encapsulating possible SQL set operators (grouping SELECT statements)
 */
interface Set
{
    const UNION = "UNION";
    const UNION_ALL = "UNION ALL";
    const INTERSECT = "INTERSECT";
    const EXCEPT = "EXCEPT";
}
