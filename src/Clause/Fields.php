<?php

namespace Lucinda\Query\Clause;

use Lucinda\Query\Clause\Window\Over;
use Lucinda\Query\Exception;
use Lucinda\Query\Validator;

/**
 * Encapsulates SQL select fields clause.
 */
class Fields implements \Stringable
{
    /**
     * @var string[]
     */
    protected array $contents = [];
    private Validator $validator;

    /**
     * Class constructor.
     *
     * @param string[] $contents
     */
    public function __construct(array $contents = [])
    {
        $this->validator = new Validator();
        $this->contents = $contents;
    }

    /**
     * Adds column to list.
     *
     * @param string|\Stringable $columnDefinition Column definition (name or expression)
     * @param string $columnAlias Optional column alias
     * @return Fields
     * @throws Exception
     */
    public function add(string|\Stringable $columnDefinition, string $columnAlias = ""): Fields
    {
        $this->contents[]=$this->validator->validateSelectField($columnDefinition, $columnAlias);
        return $this;
    }

    /**
     * Adds aggregate function with windowing to list.
     *
     * @param string $aggregateFunction Aggregate function (e.g. SUM(col))
     * @param string|Over $window Window name or definition
     * @param string $columnAlias Column alias (mandatory)
     * @return Fields
     */
    public function over(string $aggregateFunction, $window, string $columnAlias): Fields
    {
        if ($window instanceof \Stringable) {
            $windowName = "(". $window .")";
        } else {
            $windowName = $window;
        }
        $this->contents[] = $aggregateFunction." OVER ".$windowName." AS ".$columnAlias;
        return $this;
    }

    /**
     * Checks if clause is empty
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return sizeof($this->contents) == 0;
    }

    /**
     * Compiles SQL clause based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     */
    public function __toString(): string
    {
        $output = "";
        if (!sizeof($this->contents)) {
            return $output;
        }

        foreach ($this->contents as $value) {
            $output .= $value.", ";
        }

        return substr($output, 0, -2);
    }
}
