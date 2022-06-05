<?php

namespace Lucinda\Query\Operator;

/**
 * Enum encapsulating possible SQL join types
 */
enum Join: string
{
    case LEFT = "LEFT OUTER JOIN";
    case RIGHT = "RIGHT OUTER JOIN";
    case INNER = "INNER JOIN";
    case CROSS = "CROSS JOIN";
}
