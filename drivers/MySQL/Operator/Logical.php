<?php
namespace Lucinda\Query;

use Lucinda\Query\Operator\Logical as DefaultLogical;

/**
 * Enum encapsulating possible MySQL logical operators, extending those in standard SQL
 */
interface Logical extends DefaultLogical
{
    const _XOR_ = "XOR";
}
