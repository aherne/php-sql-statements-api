<?php
namespace Lucinda\Query\Operator;

/**
 * Enum encapsulating possible SQL logical operators
 */
interface Logical
{
    const _AND_ = "AND";
    const _OR_ = "OR";
    const _NOT_ = "NOT";
}
