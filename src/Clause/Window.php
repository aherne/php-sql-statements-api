<?php

namespace Lucinda\Query\Clause;

use Lucinda\Query\Clause\Window\Over;
use Lucinda\Query\Stringable;

/**
 * Encapsulates SQL WINDOW clause
 */
class Window implements Stringable
{
    private $overClauses = [];

    /**
     * Adds a named window definition.
     *
     * @param string $name
     * @param Over $over
     * @return $this
     */
    public function add(string $name, Over $over): Window
    {
        $this->overClauses[$name] = $over;
        return $this;
    }

    /**
     * Checks whether at least one window definition has been added.
     *
     * @return bool True if no window definitions have been added, false otherwise.
     */
    public function isEmpty(): bool
    {
        return empty($this->overClauses);
    }

    /**
     * Compiles SQL clause based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     */
    public function toString(): string
    {
        $output = "";
        foreach ($this->overClauses as $name => $over) {
            $output .= $name." AS (".$over->toString()."), ";
        }
        return substr($output, 0, -2);
    }
}