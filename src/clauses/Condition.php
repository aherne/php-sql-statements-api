<?php
namespace Lucinda\Query;

require_once("AbstractClause.php");
require_once("ComparisonOperator.php");
require_once("LogicalOperator.php");

/**
 * Encapsulates SQL WHERE clauses that use a single logical operator
 */
class Condition extends AbstractClause {
	protected $currentLogicalOperator;
		
	/**
	 * Sets up clause directly from constructor. 
	 *
     * @param string[string] $condition
	 * @param LogicalOperator $logicalOperator
	 */
	public function __construct($condition=array(), $logicalOperator=LogicalOperator::_AND_) {
	    foreach($condition as $key=>$value) {
	        $this->set($key, $value, ComparisonOperator::EQUALS);
        }
		$this->currentLogicalOperator = $logicalOperator;
	}
	
	/**
	 * Adds a field vs value comparison condition. If comparison operator is not supplied, translates to an `equals` condition.
	 *  
	 * @param string $columnName
	 * @param mixed $mixValue
	 * @param ComparisonOperator $comparisonOperator
	 * @return Condition
	 */
	public function set($columnName, $mixValue, $comparisonOperator=ComparisonOperator::EQUALS) {
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
	 * @param string $columnName
	 * @param mixed[] $values
	 * @param boolean $isTrue
	 * @return Condition
	 */
	public function setIn($columnName, $values, $isTrue=true) {
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
	 * @param string $columnName
	 * @param boolean $isTrue
	 * @return Condition
	 */
	public function setIsNull($columnName, $isTrue=true) {
		$clause = array();
		$clause["KEY"]=$columnName;
		$clause["COMPARATOR"]=($isTrue?ComparisonOperator::IS_NULL:ComparisonOperator::IS_NOT_NULL);
		$this->contents[]=$clause;
		return $this;
	}
	
	/**
	 * Sets up a "LIKE/NOT LIKE" condition.
	 * 
	 * @param string $columnName
	 * @param string $strPattern
	 * @param boolean $isLike
	 * @return Condition
	 */
	public function setLike($columnName, $strPattern, $isTrue=true) {
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
	 * @param string $columnName
	 * @param string $mixValueLeft
	 * @param string $mixValueRight
	 * @param boolean $isBetween
	 * @return Condition
	 */
	public function setBetween($columnName, $mixValueLeft, $mixValueRight, $isTrue=true) {
		$clause = array();
		$clause["KEY"]=$columnName;
		$clause["COMPARATOR"]=($isTrue?ComparisonOperator::BETWEEN:ComparisonOperator::NOT_BETWEEN);
		$clause["VALUE_LEFT"]=$mixValueLeft;
		$clause["VALUE_RIGHT"]=$mixValueRight;
		$this->contents[]=$clause;
		return $this;
	}

    /**
     * Sets a sub-conditional group
     *
     * @param Condition $where
     * @return Condition
     */
	public function setGroup(Condition $where) {
	    $this->contents[] = $where;
        return $this;
    }
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractClause::toString()
	 */
	public function toString() {
		$output = "";		
		if($this->currentLogicalOperator==LogicalOperator::_NOT_) $output="NOT (";
		foreach($this->contents as $values) {
            // create condition
            if($values instanceof \Lucinda\Query\Condition) {
                $output .= "(".$values->toString().")";
            } else if($values["COMPARATOR"] == ComparisonOperator::IS_NULL || $values["COMPARATOR"] == ComparisonOperator::IS_NOT_NULL) {
				$output .= $values["KEY"]." ".$values["COMPARATOR"];
			} else if($values["COMPARATOR"] == ComparisonOperator::BETWEEN || $values["COMPARATOR"] == ComparisonOperator::NOT_BETWEEN) {
				$output .= $values["KEY"]." ".$values["COMPARATOR"]." ".$values["VALUE_LEFT"]." AND ".$values["VALUE_RIGHT"];
			} else if($values["COMPARATOR"] == ComparisonOperator::IN || $values["COMPARATOR"] == ComparisonOperator::NOT_IN) {
				$tblTMP = $values["VALUE"];
				if(is_array($tblTMP)) {
                    $strValues = "";
                    foreach($tblTMP as $strString) {
                        $strValues .= $strString.",";
                    }
                    $output .= $values["KEY"]." ".$values["COMPARATOR"]." (".substr($strValues,0,-1).")";
                } else {
                    $output .= $values["KEY"]." ".$values["COMPARATOR"]." (".$tblTMP.")";
                }
			} else {
				$output .= $values["KEY"]." ".$values["COMPARATOR"]." ".$values["VALUE"];
			}
			
			$output .=" ".($this->currentLogicalOperator==LogicalOperator::_NOT_?LogicalOperator::_AND_:$this->currentLogicalOperator)." ";
		}
		return substr($output,0, -2-strlen($this->currentLogicalOperator==LogicalOperator::_NOT_?LogicalOperator::_AND_:$this->currentLogicalOperator)).($this->currentLogicalOperator==LogicalOperator::_NOT_?")":"");
	}
}