<?php
namespace Lucinda\Query\Vendor\MySQL\Operator;

use Lucinda\Query\Operator\Logical as DefaultLogical;

/**
 * Enum encapsulating possible MySQL logical operators, extending those in standard SQL
 */
interface Logical extends DefaultLogical
{
    const _XOR_ = "XOR";
}
