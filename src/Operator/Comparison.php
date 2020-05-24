<?php
namespace Lucinda\Query\Operator;

/**
 * Enum encapsulating possible SQL WHERE comparison operators
 */
interface Comparison
{
    // simple comparison operators
    const EQUALS = "=";
    const GREATER = ">";
    const LESSER = "<";
    const DIFFERS = "!=";
    const GREATER_EQUALS = ">=";
    const LESSER_EQUALS = "<=";
    const NULL_SAFE_EQUALS = "<=>";
    
    // pattern comparison operators
    const LIKE ="LIKE";
    const NOT_LIKE ="NOT LIKE";
    
    // in comparison
    const IN = "IN";
    const NOT_IN = "NOT IN";
    
    // range comparison operators
    const BETWEEN ="BETWEEN";
    const NOT_BETWEEN = "NOT BETWEEN";
    
    // null operators
    const IS_NULL = "IS NULL";
    const IS_NOT_NULL = "IS NOT NULL";
}
