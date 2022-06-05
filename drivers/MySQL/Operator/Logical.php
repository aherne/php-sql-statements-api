<?php

namespace Lucinda\Query\Vendor\MySQL\Operator;

/**
 * Enum encapsulating possible MySQL logical operators, extending those in standard SQL
 */
enum Logical : string
{
    case _XOR_ = "XOR";
}
