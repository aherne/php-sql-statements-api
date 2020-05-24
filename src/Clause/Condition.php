<?php
namespace Lucinda\Query\Clause;

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
    protected $contents = array();

    /**
     * @param string[string] $condition Sets condition group directly when conditions are all of equals type
     * @param Logical $logicalOperator Enum holding operator that will link conditions in group (default: AND)
     */
    public function __construct(array $condition=array(), int $logicalOperator=Logical::_AND_)
    {
        foreach ($condition as $key=>$value) {
            $this->set($key, $value, Comparison::EQUALS);
        }
        $this->currentLogical = $logicalOperator;
    }
    
    /**
     * Adds a field vs value comparison condition.
     *
     * @param string $columnName Name of column/field.
     * @param mixed $value Value of column/field for row.
     * @param Comparison $comparisonOperator Enum holding logical operator that will be used in condition (default: =)
     * @return Condition
     */
    public function set(string $columnName, $value, int $comparisonOperator=Comparison::EQUALS): Condition
    {
        $clause = array();
        $clause["KEY"]=$columnName;
        $clause["COMPARATOR"]=$comparisonOperator;
        $clause["VALUE"]=$value;
        $this->contents[]=$clause;
        return $this;
    }
    
    /**
     * Adds an "IN/NOT IN" condition.
     *
     * @param string $columnName Name of column/field.
     * @param string[]|Select|SelectGroup $values List of possible values for column/field in row or a Select/SelectGroup statement.
     * @param boolean $isTrue Whether or not condition is IN or NOT IN
     * @return Condition
     */
    public function setIn(string $columnName, $values, bool $isTrue=true): Condition
    {
        $clause = array();
        $clause["KEY"]=$columnName;
        $clause["COMPARATOR"]=($isTrue?Comparison::IN:Comparison::NOT_IN);
        $clause["VALUE"]=$values;
        $this->contents[]=$clause;
        return $this;
    }
    
    /**
     * Adds a "NULL/NOT NULL" condition.
     *
     * @param string $columnName Name of column/field.
     * @param boolean $isTrue Whether or not condition is NULL or NOT NULL
     * @return Condition
     */
    public function setIsNull(string $columnName, bool $isTrue=true): Condition
    {
        $clause = array();
        $clause["KEY"]=$columnName;
        $clause["COMPARATOR"]=($isTrue?Comparison::IS_NULL:Comparison::IS_NOT_NULL);
        $this->contents[]=$clause;
        return $this;
    }
    
    /**
     * Sets up a "LIKE/NOT LIKE" condition.
     *
     * @param string $columnName Name of column/field.
     * @param string $pattern Value of pattern to match
     * @param boolean $isTrue Whether or not condition is LIKE or NOT LIKE
     * @return Condition
     */
    public function setLike(string $columnName, string $pattern, bool $isTrue=true): Condition
    {
        $clause = array();
        $clause["KEY"]=$columnName;
        $clause["COMPARATOR"]=($isTrue?Comparison::LIKE:Comparison::NOT_LIKE);
        $clause["VALUE"]=$pattern;
        $this->contents[]=$clause;
        return $this;
    }
    
    /**
     * Sets up a "BETWEEN/NOT BETWEEN" condition.
     *
     * @param string $columnName Name of column/field.
     * @param mixed $valueLeft Minimal value of column/field in row
     * @param mixed $valueRight Maximal value of column/field in row
     * @param boolean $isTrue Whether or not condition is BETWEEN or NOT BETWEEN
     * @return Condition
     */
    public function setBetween(string $columnName, $valueLeft, $valueRight, bool $isTrue=true): Condition
    {
        $clause = array();
        $clause["KEY"]=$columnName;
        $clause["COMPARATOR"]=($isTrue?Comparison::BETWEEN:Comparison::NOT_BETWEEN);
        $clause["VALUE_LEFT"]=$valueLeft;
        $clause["VALUE_RIGHT"]=$valueRight;
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
                    $values = "";
                    foreach ($tmp as $string) {
                        $values .= $string.",";
                    }
                    $output .= $values["KEY"]." ".$values["COMPARATOR"]." (".substr($values, 0, -1).")";
                } else {
                    $output .= $values["KEY"]." ".$values["COMPARATOR"]." (".$tmp.")";
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
}
