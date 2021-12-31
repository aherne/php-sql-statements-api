<?php
namespace Lucinda\Query\Vendor\MySQL\Operator;

/**
 * Enum encapsulating possible MySQL fulltext search clauses
 */
enum MatchType: string
{
    case NATURAL_LANGUAGE = "IN NATURAL LANGUAGE MODE";
    case BOOLEAN = "IN BOOLEAN MODE";
    case QUERY_EXPANSION = "WITH QUERY EXPANSION";
}
