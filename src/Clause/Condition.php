<?php
namespace Lucinda\Query\Clause;

use Lucinda\Query\Exception;
use Lucinda\Query\Stringable;
use Lucinda\Query\Select;
use Lucinda\Query\SelectGroup;
use Lucinda\Query\Operator\Logical;
use Lucinda\Query\Operator\Comparison;

/**
 * Encapsulates SQL WHERE/ON clauses that use a single logical operator
 */
class Condition implements Stringable
{
    protected $currentLogical;
    protected $contents = [];

    /**
     * @param string[string] $condition Sets condition group directly when conditions are all of equals type
     * @param Logical $logicalOperator Enum holding operator that will link conditions in group (default: AND)
     */
    public function __construct(array $condition=[], string $logicalOperator = Logical::_AND_)
    {
        foreach ($condition as $key=>$value) {
            $this->set($key, $value, Comparison::EQUALS);
        }
        $this->currentLogical = $logicalOperator;
    }
    
    /**
     * Adds a field vs value comparison condition.
     *
     * @param string|Stringable $columnDefinition Definition of column/field (name or expression)
     * @param mixed $value Value of column/field for row.
     * @param Comparison $comparisonOperator Enum holding logical operator that will be used in condition (default: =)
     * @return Condition
     */
    public function set($columnDefinition, $value, string $comparisonOperator=Comparison::EQUALS): Condition
    {
        $clause = [];
        $clause["KEY"]=$this->validateArgument($columnDefinition);
        $clause["COMPARATOR"]=$comparisonOperator;
        $clause["VALUE"]=$this->validateArgument($value);
        $this->contents[]=$clause;
        return $this;
    }
    
    /**
     * Adds an "IN/NOT IN" condition.
     *
     * @param string|Stringable $columnDefinition Definition of column/field (name or expression)
     * @param string[]|Select|SelectGroup $values List of possible values for column/field in row or a Select/SelectGroup statement.
     * @param boolean $isTrue Whether or not condition is IN or NOT IN
     * @return Condition
     */
    public function setIn($columnDefinition, $values, bool $isTrue=true): Condition
    {
        $clause = [];
        $clause["KEY"]=$this->validateArgument($columnDefinition);
        $clause["COMPARATOR"]=($isTrue?Comparison::IN:Comparison::NOT_IN);
        $clause["VALUE"]=$this->validateArgument($values);
        $this->contents[]=$clause;
        return $this;
    }
    
    /**
     * Adds a "NULL/NOT NULL" condition.
     *
     * @param string|Stringable $columnDefinition Definition of column/field (name or expression)
     * @param boolean $isTrue Whether or not condition is NULL or NOT NULL
     * @return Condition
     */
    public function setIsNull($columnDefinition, bool $isTrue=true): Condition
    {
        $clause = [];
        $clause["KEY"]=$this->validateArgument($columnDefinition);
        $clause["COMPARATOR"]=($isTrue?Comparison::IS_NULL:Comparison::IS_NOT_NULL);
        $this->contents[]=$clause;
        return $this;
    }
    
    /**
     * Sets up a "LIKE/NOT LIKE" condition.
     *
     * @param string|Stringable $columnDefinition Definition of column/field (name or expression)
     * @param string $pattern Value of pattern to match
     * @param boolean $isTrue Whether or not condition is LIKE or NOT LIKE
     * @return Condition
     */
    public function setLike($columnDefinition, string $pattern, bool $isTrue=true): Condition
    {
        $clause = [];
        $clause["KEY"]=$this->validateArgument($columnDefinition);
        $clause["COMPARATOR"]=($isTrue?Comparison::LIKE:Comparison::NOT_LIKE);
        $clause["VALUE"]=$pattern;
        $this->contents[]=$clause;
        return $this;
    }
    
    /**
     * Sets up a "BETWEEN/NOT BETWEEN" condition.
     *
     * @param string|Stringable $columnDefinition Definition of column/field (name or expression)
     * @param mixed $valueLeft Minimal value of column/field in row
     * @param mixed $valueRight Maximal value of column/field in row
     * @param boolean $isTrue Whether or not condition is BETWEEN or NOT BETWEEN
     * @return Condition
     */
    public function setBetween($columnDefinition, $valueLeft, $valueRight, bool $isTrue=true): Condition
    {
        $clause = [];
        $clause["KEY"]=$this->validateArgument($columnDefinition);
        $clause["COMPARATOR"]=($isTrue?Comparison::BETWEEN:Comparison::NOT_BETWEEN);
        $clause["VALUE_LEFT"]=$this->validateArgument($valueLeft);
        $clause["VALUE_RIGHT"]=$this->validateArgument($valueRight);
        $this->contents[]=$clause;
        return $this;
    }

    /**
     * Sets condition insides current condition (Eg: setting an OR condition inside an AND condition group)
     *
     * @param Condition $where Condition Encapsulates condition to set
     * @return Condition
     */
    public function setGroup(Condition $where): Condition
    {
        $this->contents[] = $where;
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
        if ($this->currentLogical==Logical::_NOT_) {
            $output="NOT (";
        }
        foreach ($this->contents as $values) {
            // create condition
            if ($values instanceof Condition) {
                $output .= "(".$values->toString().")";
            } elseif ($values["COMPARATOR"] == Comparison::IS_NULL || $values["COMPARATOR"] == Comparison::IS_NOT_NULL) {
                $output .= $values["KEY"]." ".$values["COMPARATOR"];
            } elseif ($values["COMPARATOR"] == Comparison::BETWEEN || $values["COMPARATOR"] == Comparison::NOT_BETWEEN) {
                $output .= $values["KEY"]." ".$values["COMPARATOR"]." ".$values["VALUE_LEFT"]." AND ".$values["VALUE_RIGHT"];
            } elseif ($values["COMPARATOR"] == Comparison::IN || $values["COMPARATOR"] == Comparison::NOT_IN) {
                $tmp = $values["VALUE"];
                if (is_array($tmp)) {
                    $output .= $values["KEY"]." ".$values["COMPARATOR"]." (".substr(implode(", ", $tmp), 0, -2).")";
                } else {
                    $output .= $values["KEY"]." ".$values["COMPARATOR"]." ".$tmp;
                }
            } else {
                $output .= $values["KEY"]." ".$values["COMPARATOR"]." ".$values["VALUE"];
            }
            
            $output .=" ".($this->currentLogical==Logical::_NOT_?Logical::_AND_:$this->currentLogical)." ";
        }
        return substr($output, 0, -2-strlen($this->currentLogical==Logical::_NOT_?Logical::_AND_:$this->currentLogical)).($this->currentLogical==Logical::_NOT_?")":"");
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
     * Validates argument to be used in condition (column definition or value)
     *
     * @param $columnDefinition
     * @return string
     * @throws Exception
     */
    protected function validateArgument($columnDefinition): string
    {
        if ($columnDefinition instanceof Stringable) {
            return "(".$columnDefinition->toString().")";
        } else if (is_array($columnDefinition)) {
            return "(".implode(", ", $columnDefinition).")";
        } else {
            return $columnDefinition;
        }
    }
}
