<?php
namespace Lucinda\Query\Operator;

/**
 * Enum encapsulating possible SQL logical operators
 */
enum Logical: string
{
    case _AND_ = "AND";
    case _OR_ = "OR";
    case _NOT_ = "NOT";
}
