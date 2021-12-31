<?php
namespace Lucinda\Query\Clause;

use Lucinda\Query\Select;
use Lucinda\Query\SelectGroup;
use Lucinda\Query\Operator\Logical AS LogicalOperator;
use Lucinda\Query\Operator\Comparison AS ComparisonOperator;

/**
 * Encapsulates SQL WHERE/ON clauses that use a single logical operator
 */
class Condition implements \Stringable
{
    protected LogicalOperator $currentLogical;
    protected array $contents = [];

    /**
     * Constructor
     *
     * @param string[string] $condition Sets condition group directly when conditions are all of equals type
     * @param LogicalOperator $logicalOperator Enum holding operator that will link conditions in group (default: AND)
     */
    public function __construct(array $condition=[], LogicalOperator $logicalOperator = LogicalOperator::_AND_)
    {
        foreach ($condition as $key=>$value) {
            $this->set($key, $value, ComparisonOperator::EQUALS);
        }
        $this->currentLogical = $logicalOperator;
    }
    
    /**
     * Adds a field vs value comparison condition.
     *
     * @param string $columnName Name of column/field.
     * @param int|string|float|Select|SelectGroup $value Value of column/field for row.
     * @param ComparisonOperator $comparisonOperator Enum holding logical operator that will be used in condition (default: =)
     * @return Condition
     */
    public function set(string $columnName, int|string|float|Select|SelectGroup $value, ComparisonOperator $comparisonOperator=ComparisonOperator::EQUALS): Condition
    {
        $clause = [];
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
     * @param boolean $isTrue Whether condition is IN or NOT IN
     * @return Condition
     */
    public function setIn(string $columnName, array|Select|SelectGroup $values, bool $isTrue=true): Condition
    {
        $clause = [];
        $clause["KEY"]=$columnName;
        $clause["COMPARATOR"]=($isTrue?ComparisonOperator::IN:ComparisonOperator::NOT_IN);
        $clause["VALUE"]=$values;
        $this->contents[]=$clause;
        return $this;
    }
    
    /**
     * Adds a "NULL/NOT NULL" condition.
     *
     * @param string $columnName Name of column/field.
     * @param boolean $isTrue Whether condition is NULL or NOT NULL
     * @return Condition
     */
    public function setIsNull(string $columnName, bool $isTrue=true): Condition
    {
        $clause = [];
        $clause["KEY"]=$columnName;
        $clause["COMPARATOR"]=($isTrue?ComparisonOperator::IS_NULL:ComparisonOperator::IS_NOT_NULL);
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
        $clause = [];
        $clause["KEY"]=$columnName;
        $clause["COMPARATOR"]=($isTrue?ComparisonOperator::LIKE:ComparisonOperator::NOT_LIKE);
        $clause["VALUE"]=$pattern;
        $this->contents[]=$clause;
        return $this;
    }
    
    /**
     * Sets up a "BETWEEN/NOT BETWEEN" condition.
     *
     * @param string $columnName Name of column/field.
     * @param int|string|float $valueLeft Minimal value of column/field in row
     * @param int|string|float $valueRight Maximal value of column/field in row
     * @param boolean $isTrue Whether condition is BETWEEN or NOT BETWEEN
     * @return Condition
     */
    public function setBetween(string $columnName, int|string|float $valueLeft, int|string|float $valueRight, bool $isTrue=true): Condition
    {
        $clause = [];
        $clause["KEY"]=$columnName;
        $clause["COMPARATOR"]=($isTrue?ComparisonOperator::BETWEEN:ComparisonOperator::NOT_BETWEEN);
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
        if ($this->currentLogical==LogicalOperator::_NOT_) {
            $output="NOT (";
        }
        foreach ($this->contents as $values) {
            // create condition
            if ($values instanceof Condition) {
                $output .= "(".$values.")";
            } elseif ($values["COMPARATOR"] == ComparisonOperator::IS_NULL || $values["COMPARATOR"] == ComparisonOperator::IS_NOT_NULL) {
                $output .= $values["KEY"]." ".$values["COMPARATOR"]->value;
            } elseif ($values["COMPARATOR"] == ComparisonOperator::BETWEEN || $values["COMPARATOR"] == ComparisonOperator::NOT_BETWEEN) {
                $output .= $values["KEY"]." ".$values["COMPARATOR"]->value." ".$values["VALUE_LEFT"]." AND ".$values["VALUE_RIGHT"];
            } elseif ($values["COMPARATOR"] == ComparisonOperator::IN || $values["COMPARATOR"] == ComparisonOperator::NOT_IN) {
                $tmp = $values["VALUE"];
                if (is_array($tmp)) {
                    $valuesText = "";
                    foreach ($tmp as $string) {
                        $valuesText .= $string.", ";
                    }
                    $output .= $values["KEY"]." ".$values["COMPARATOR"]->value." (".substr($valuesText, 0, -2).")";
                } else {
                    $output .= $values["KEY"]." ".$values["COMPARATOR"]->value." (".$tmp.")";
                }
            } else {
                $output .= $values["KEY"]." ".
                    (is_string($values["COMPARATOR"])?$values["COMPARATOR"]:$values["COMPARATOR"]->value)." ".
                    (is_object($values["VALUE"])?"(".$values["VALUE"].")":$values["VALUE"]);
            }
            
            $output .=" ".($this->currentLogical==LogicalOperator::_NOT_?LogicalOperator::_AND_->value:$this->currentLogical->value)." ";
        }
        return substr($output,
                0,
                -2-strlen($this->currentLogical==LogicalOperator::_NOT_?LogicalOperator::_AND_->value:$this->currentLogical->value)).
            ($this->currentLogical==LogicalOperator::_NOT_?")":"");
    }
}
