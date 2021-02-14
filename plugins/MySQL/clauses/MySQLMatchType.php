<?php
namespace Lucinda\Query;

/**
 * Enum encapsulating possible MySQL fulltext search clauses
 */
interface MySQLMatchType
{
    const NATURAL_LANGUAGE = "IN NATURAL LANGUAGE MODE";
    const BOOLEAN = "IN BOOLEAN MODE";
    const QUERY_EXPANSION = "WITH QUERY EXPANSION";
}