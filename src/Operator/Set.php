<?php
namespace Lucinda\Query\Operator;

/**
 * Enum encapsulating possible SQL set operators (grouping SELECT statements)
 */
enum Set: string
{
    case UNION = "UNION";
    case UNION_ALL = "UNION ALL";
    case INTERSECT = "INTERSECT";
    case EXCEPT = "EXCEPT";
}
