<?php
namespace Lucinda\Query;

/**
 * Enum encapsulating possible SQL logical operators
 */
interface LogicalOperator
{
    const _AND_ = "AND";
    const _OR_ = "OR";
    const _NOT_ = "NOT";
}
