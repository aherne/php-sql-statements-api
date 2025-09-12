<?php
namespace Lucinda\Query\Clause;

use Lucinda\Query\Stringable;
use Lucinda\Query\Operator\OrderBy as OrderByOperator;

/**
 * Encapsulates SQL ORDER BY clause
 */
class OrderBy implements Stringable
{
    protected $contents = [];

    /**
     * Class constructor.
     *
     * @param string[] $fields Sets list of columns to order by directly in ASC mode
     */
    public function __construct(array $fields = [])
    {
        foreach ($fields as $field) {
            $this->contents[$field] = OrderByOperator::ASC;
        }
    }

    /**
     * Adds order by clause.
     *
     * @param string|Stringable $columnDefinition Column definition (name or expression)
     * @param OrderByOperator $operator Enum encapsulating order by direction (default: ASC)
     * @return OrderBy Object to set further clauses on.
     */
    public function add($columnDefinition, string $operator = OrderByOperator::ASC): OrderBy
    {
        $column = ($columnDefinition instanceof Stringable?"(".$columnDefinition->toString().")":$columnDefinition);
        $this->contents[$column] = $operator;
        return $this;
    }

    /**
     * Compiles SQL clause based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     */
    public function toString(): string
    {
        $output = "";
        foreach ($this->contents as $key=>$value) {
            $output .= $key." ".$value.", ";
        }
        return substr($output, 0, -2);
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
}
