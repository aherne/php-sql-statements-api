<?php
namespace Lucinda\Query;

/**
 * Enum encapsulating possible SQL join types
 */
interface JoinOperator
{
    const LEFT = "LEFT OUTER JOIN";
    const RIGHT = "RIGHT OUTER JOIN";
    const INNER = "INNER JOIN";
    const CROSS = "CROSS JOIN";
}