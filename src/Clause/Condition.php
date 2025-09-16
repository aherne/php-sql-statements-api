<?php

namespace Lucinda\Query\Clause;

use Lucinda\Query\Select;
use Lucinda\Query\SelectGroup;
use Lucinda\Query\Operator\Logical as LogicalOperator;
use Lucinda\Query\Operator\Comparison as ComparisonOperator;
use Lucinda\Query\Stringable;
use Lucinda\Query\Validator;

/**
 * Encapsulates SQL WHERE/ON clauses that use a single logical operator
 */
class Condition implements \Stringable
{
    protected LogicalOperator $currentLogical;
    /**
     * @var array<int,mixed>
     */
    protected array $contents = [];
    protected Validator $validator;

    /**
     * Constructor
     *
     * @param array<string,string> $condition       Sets condition group directly when conditions are all of equals type
     * @param LogicalOperator      $logicalOperator Enum holding operator that will link conditions in group (default: AND)
     */
    public function __construct(array $condition=[], LogicalOperator $logicalOperator = LogicalOperator::_AND_)
    {
        $this->validator = new Validator();
        foreach ($condition as $key=>$value) {
            $this->set($key, $value, ComparisonOperator::EQUALS);
        }
        $this->currentLogical = $logicalOperator;
    }

    /**
     * Adds a field vs value comparison condition.
     *
     * @param  string|\Stringable                  $columnDefinition        Definition of column/field (name or expression)
     * @param  int|string|float|Select|SelectGroup $value              Value of column/field for row.
     * @param  ComparisonOperator                  $comparisonOperator Enum holding logical operator that will be used in condition (default: =)
     * @return Condition
     */
    public function set(
        string|\Stringable $columnDefinition,
        int|string|float|Select|SelectGroup $value,
        ComparisonOperator $comparisonOperator=ComparisonOperator::EQUALS
    ): Condition {
        $clause = [];
        $clause["KEY"]=$this->validator->validateCondition($columnDefinition);
        $clause["COMPARATOR"]=$comparisonOperator;
        $clause["VALUE"]=$this->validator->validateCondition($value);
        $this->contents[]=$clause;
        return $this;
    }

    /**
     * Adds an "IN/NOT IN" condition.
     *
     * @param  string|\Stringable          $columnDefinition Definition of column/field (name or expression)
     * @param  string[]|Select|SelectGroup $values     List of possible values for column/field in row or a Select/SelectGroup statement.
     * @param  boolean                     $isTrue     Whether condition is IN or NOT IN
     * @return Condition
     */
    public function setIn(string|\Stringable $columnDefinition, array|Select|SelectGroup $values, bool $isTrue=true): Condition
    {
        $clause = [];
        $clause["KEY"]=$this->validator->validateCondition($columnDefinition);
        $clause["COMPARATOR"]=($isTrue ? ComparisonOperator::IN : ComparisonOperator::NOT_IN);
        $clause["VALUE"]=$this->validator->validateCondition($values);
        $this->contents[]=$clause;
        return $this;
    }

    /**
     * Adds a "NULL/NOT NULL" condition.
     *
     * @param  string|\Stringable  $columnDefinition Definition of column/field (name or expression)
     * @param  boolean $isTrue     Whether condition is NULL or NOT NULL
     * @return Condition
     */
    public function setIsNull(string|\Stringable $columnDefinition, bool $isTrue=true): Condition
    {
        $clause = [];
        $clause["KEY"]=$this->validator->validateCondition($columnDefinition);
        $clause["COMPARATOR"]=($isTrue ? ComparisonOperator::IS_NULL : ComparisonOperator::IS_NOT_NULL);
        $this->contents[]=$clause;
        return $this;
    }

    /**
     * Sets up a "LIKE/NOT LIKE" condition.
     *
     * @param  string|\Stringable  $columnDefinition Definition of column/field (name or expression)
     * @param  string  $pattern    Value of pattern to match
     * @param  boolean $isTrue     Whether or not condition is LIKE or NOT LIKE
     * @return Condition
     */
    public function setLike(string|\Stringable $columnDefinition, string $pattern, bool $isTrue=true): Condition
    {
        $clause = [];
        $clause["KEY"]=$this->validator->validateCondition($columnDefinition);
        $clause["COMPARATOR"]=($isTrue ? ComparisonOperator::LIKE : ComparisonOperator::NOT_LIKE);
        $clause["VALUE"]=$pattern;
        $this->contents[]=$clause;
        return $this;
    }

    /**
     * Sets up a "BETWEEN/NOT BETWEEN" condition.
     *
     * @param  string|\Stringable $columnDefinition Name of column/field.
     * @param  int|string|float $valueLeft  Minimal value of column/field in row
     * @param  int|string|float $valueRight Maximal value of column/field in row
     * @param  boolean          $isTrue     Whether condition is BETWEEN or NOT BETWEEN
     * @return Condition
     */
    public function setBetween(
        string|\Stringable $columnDefinition,
        int|string|float $valueLeft,
        int|string|float $valueRight,
        bool $isTrue=true
    ): Condition {
        $clause = [];
        $clause["KEY"]=$this->validator->validateCondition($columnDefinition);
        $clause["COMPARATOR"]=($isTrue ? ComparisonOperator::BETWEEN : ComparisonOperator::NOT_BETWEEN);
        $clause["VALUE_LEFT"]=$this->validator->validateCondition($valueLeft);
        $clause["VALUE_RIGHT"]=$this->validator->validateCondition($valueRight);
        $this->contents[]=$clause;
        return $this;
    }

    /**
     * Sets condition insides current condition (Eg: setting an OR condition inside an AND condition group)
     *
     * @param  Condition $where Condition Encapsulates condition to set
     * @return Condition
     */
    public function setGroup(Condition $where): Condition
    {
        $this->contents[] = $where;
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
        $output = ($this->currentLogical==LogicalOperator::_NOT_ ? "NOT (" : "");
        $clauseOperator = $this->getOperator();
        $this->compile($output, $clauseOperator);
        $output = substr($output, 0, -2-strlen($clauseOperator));
        return $output.($this->currentLogical==LogicalOperator::_NOT_ ? ")" : "");
    }

    /**
     * Compiles condition based on clauses set
     *
     * @param string $output
     * @param string $clauseOperator
     * @return void
     */
    private function compile(string &$output, string $clauseOperator): void
    {
        foreach ($this->contents as $values) {
            // create condition
            if ($values instanceof Condition) {
                $output .= "(".$values.")";
            } else {
                switch ($values["COMPARATOR"]) {
                    case ComparisonOperator::IS_NULL:
                    case ComparisonOperator::IS_NOT_NULL:
                        $output .= $this->getIsNull($values);
                        break;
                    case ComparisonOperator::BETWEEN:
                    case ComparisonOperator::NOT_BETWEEN:
                        $output .= $this->getBetween($values);
                        break;
                    default:
                        $output .= $this->getSimple($values);
                        break;
                }
            }

            $output .=" ".$clauseOperator." ";
        }
    }

    /**
     * Converts IS (NOT) NULL clause to SQL
     *
     * @param  array<string,mixed> $values
     * @return string
     */
    private function getIsNull(array $values): string
    {
        return $values["KEY"]." ".$values["COMPARATOR"]->value;
    }

    /**
     * Converts (NOT) BETWEEN clause to SQL
     *
     * @param  array<string,mixed> $values
     * @return string
     */
    private function getBetween(array $values): string
    {
        return $values["KEY"]." ".$values["COMPARATOR"]->value." ".$values["VALUE_LEFT"]." AND ".$values["VALUE_RIGHT"];
    }

    /**
     * Converts simple (COLUMN OPERATOR VALUE) clause to SQL
     *
     * @param  array<string,mixed> $values
     * @return string
     */
    private function getSimple(array $values): string
    {
        return $values["KEY"]." ".$values["COMPARATOR"]->value." ".$values["VALUE"];
    }

    /**
     * Gets logical operator to bind individual clauses
     *
     * @return string
     */
    private function getOperator(): string
    {
        if ($this->currentLogical==LogicalOperator::_NOT_) {
            return LogicalOperator::_AND_->value;
        } else {
            return $this->currentLogical->value;
        }
    }
}
