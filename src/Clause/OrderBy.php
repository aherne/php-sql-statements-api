<?php

namespace Lucinda\Query\Clause;

use Lucinda\Query\Operator\OrderBy as OrderByOperator;
use Lucinda\Query\Validator;

/**
 * Encapsulates SQL ORDER BY clause
 */
class OrderBy implements \Stringable
{
    /**
     * @var array<string,OrderByOperator>
     */
    protected array $contents = [];
    private Validator $validator;

    /**
     * Class constructor.
     *
     * @param string[] $fields Sets list of columns to order by directly in ASC mode
     */
    public function __construct(array $fields = [])
    {
        $this->validator = new Validator();
        foreach ($fields as $field) {
            $this->contents[$field] = OrderByOperator::ASC;
        }
    }

    /**
     * Adds order by clause.
     *
     * @param  string|\Stringable $columnDefinition Name of column/field to order by with.
     * @param  OrderByOperator $operator   Enum encapsulating order by direction (default: ASC)
     * @return OrderBy Object to set further clauses on.
     */
    public function add(string|\Stringable $columnDefinition, OrderByOperator $operator = OrderByOperator::ASC): OrderBy
    {
        $this->contents[$this->validator->validateOrderByField($columnDefinition)] = $operator;
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
        foreach ($this->contents as $key=>$value) {
            $output .= $key." ".$value->value.", ";
        }
        return substr($output, 0, -2);
    }
}
