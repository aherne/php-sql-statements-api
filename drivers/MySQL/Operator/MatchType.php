<?php
namespace Lucinda\Query\Vendor\MySQL\Operator;

/**
 * Enum encapsulating possible MySQL fulltext search clauses
 */
interface MatchType
{
    const NATURAL_LANGUAGE = "IN NATURAL LANGUAGE MODE";
    const BOOLEAN = "IN BOOLEAN MODE";
    const QUERY_EXPANSION = "WITH QUERY EXPANSION";
}
