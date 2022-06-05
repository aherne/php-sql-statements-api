<?php

namespace Lucinda\Query\Operator;

/**
 * Enum encapsulating possible SQL order by types
 */
enum OrderBy: string
{
    case ASC = "ASC";
    case DESC = "DESC";
}
