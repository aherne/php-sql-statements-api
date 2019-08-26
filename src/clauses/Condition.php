<?php
namespace Lucinda\Query;

require_once("ComparisonOperator.php");
require_once("LogicalOperator.php");
require_once(dirname(__DIR__)."/Stringable.php");

/**
 * Encapsulates SQL WHERE/ON clauses that use a single logical operator
 */
class Condition implements Stringable
{
    protected $currentLogicalOperator;
    protected $contents = array();

    /**
     * @param string[string] $condition Sets condition group directly when conditions are all of equals type
     * @param LogicalOperator $logicalOperator Enum holding operator that will link conditions in group (default: AND)
     */
    public function __construct($condition=array(), $logicalOperator=LogicalOperator::_AND_)
    {
        foreach ($condition as $key=>$value) {
            $this->set($key, $value, ComparisonOperator::EQUALS);
        }
        $this->currentLogicalOperator = $logicalOperator;
    }
    
    /**
     * Adds a field vs value comparison condition.
     *
     * @param string $columnName Name of column/field.
     * @param mixed $mixValue Value of column/field for row.
     * @param ComparisonOperator $comparisonOperator Enum holding logical operator that will be used in condition (default: =)
     * @return Condition
     */
    public function set($columnName, $mixValue, $comparisonOperator=ComparisonOperator::EQUALS)
    {
        $clause = array();
        $clause["KEY"]=$columnName;
        $clause["COMPARATOR"]=$comparisonOperator;
        $clause["VALUE"]=$mixValue;
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
    public function setIn($columnName, $values, $isTrue=true)
    {
        $clause = array();
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
     * @param boolean $isTrue Whether or not condition is NULL or NOT NULL
     * @return Condition
     */
    public function setIsNull($columnName, $isTrue=true)
    {
        $clause = array();
        $clause["KEY"]=$columnName;
        $clause["COMPARATOR"]=($isTrue?ComparisonOperator::IS_NULL:ComparisonOperator::IS_NOT_NULL);
        $this->contents[]=$clause;
        return $this;
    }
    
    /**
     * Sets up a "LIKE/NOT LIKE" condition.
     *
     * @param string $columnName Name of column/field.
     * @param string $strPattern Value of pattern to match
     * @param boolean $isTrue Whether or not condition is LIKE or NOT LIKE
     * @return Condition
     */
    public function setLike($columnName, $strPattern, $isTrue=true)
    {
        $clause = array();
        $clause["KEY"]=$columnName;
        $clause["COMPARATOR"]=($isTrue?ComparisonOperator::LIKE:ComparisonOperator::NOT_LIKE);
        $clause["VALUE"]=$strPattern;
        $this->contents[]=$clause;
        return $this;
    }
    
    /**
     * Sets up a "BETWEEN/NOT BETWEEN" condition.
     *
     * @param string $columnName Name of column/field.
     * @param mixed $mixValueLeft Minimal value of column/field in row
     * @param mixed $mixValueRight Maximal value of column/field in row
     * @param boolean $isTrue Whether or not condition is BETWEEN or NOT BETWEEN
     * @return Condition
     */
    public function setBetween($columnName, $mixValueLeft, $mixValueRight, $isTrue=true)
    {
        $clause = array();
        $clause["KEY"]=$columnName;
        $clause["COMPARATOR"]=($isTrue?ComparisonOperator::BETWEEN:ComparisonOperator::NOT_BETWEEN);
        $clause["VALUE_LEFT"]=$mixValueLeft;
        $clause["VALUE_RIGHT"]=$mixValueRight;
        $this->contents[]=$clause;
        return $this;
    }

    /**
     * Sets condition insides current condition (Eg: setting an OR condition inside an AND condition group)
     *
     * @param Condition $where Condition Encapsulates condition to set
     * @return Condition
     */
    public function setGroup(Condition $where)
    {
        $this->contents[] = $where;
        return $this;
    }

    /**
     * Compiles SQL clause based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     */
    public function toString()
    {
        $output = "";
        if ($this->currentLogicalOperator==LogicalOperator::_NOT_) {
            $output="NOT (";
        }
        foreach ($this->contents as $values) {
            // create condition
            if ($values instanceof \Lucinda\Query\Condition) {
                $output .= "(".$values->toString().")";
            } elseif ($values["COMPARATOR"] == ComparisonOperator::IS_NULL || $values["COMPARATOR"] == ComparisonOperator::IS_NOT_NULL) {
                $output .= $values["KEY"]." ".$values["COMPARATOR"];
            } elseif ($values["COMPARATOR"] == ComparisonOperator::BETWEEN || $values["COMPARATOR"] == ComparisonOperator::NOT_BETWEEN) {
                $output .= $values["KEY"]." ".$values["COMPARATOR"]." ".$values["VALUE_LEFT"]." AND ".$values["VALUE_RIGHT"];
            } elseif ($values["COMPARATOR"] == ComparisonOperator::IN || $values["COMPARATOR"] == ComparisonOperator::NOT_IN) {
                $tblTMP = $values["VALUE"];
                if (is_array($tblTMP)) {
                    $strValues = "";
                    foreach ($tblTMP as $strString) {
                        $strValues .= $strString.",";
                    }
                    $output .= $values["KEY"]." ".$values["COMPARATOR"]." (".substr($strValues, 0, -1).")";
                } else {
                    $output .= $values["KEY"]." ".$values["COMPARATOR"]." (".$tblTMP.")";
                }
            } else {
                $output .= $values["KEY"]." ".$values["COMPARATOR"]." ".$values["VALUE"];
            }
            
            $output .=" ".($this->currentLogicalOperator==LogicalOperator::_NOT_?LogicalOperator::_AND_:$this->currentLogicalOperator)." ";
        }
        return substr($output, 0, -2-strlen($this->currentLogicalOperator==LogicalOperator::_NOT_?LogicalOperator::_AND_:$this->currentLogicalOperator)).($this->currentLogicalOperator==LogicalOperator::_NOT_?")":"");
    }

    /**
     * Checks if clause is empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return sizeof($this->contents) == 0;
    }
}
