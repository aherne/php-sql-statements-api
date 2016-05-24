<?php
require_once("AbstractSQLClause.php");
require_once("../constants/SQLComparisonOperator.php");
require_once("../constants/SQLLogicalOperator.php");

/**
 * Encapsulates SQL clause: WHERE
 * Applies to WHEREs that use a single logical operator (see SQLLogicalOperator).
 */
class SQLWhereClause extends AbstractSQLClause {
	protected $strCurrentLogicalOperator;
		
	/**
	 * Sets up clause directly from constructor. 
	 * 
	 * @param	SQLLogicalOperator							$strLogicalOperator
	 * @throws	SQLLanguageException
	 */
	public function __construct($strLogicalOperator=SQLLogicalOperator::_AND_) {
		if(!defined('SQLLogicalOperator::'.$strLogicalOperator)) throw new SQLLanguageException("Invalid logical operator: ".$strLogicalOperator);
		$this->strCurrentLogicalOperator = $strLogicalOperator;
	}
	
	/**
	 * Adds a field vs value comparison condition. If comparison operator is not supplied, translates to an `equals` condition.
	 *  
	 * @param 	string 										$strColumnName
	 * @param 	mixed 										$mixValue
	 * @param 	SQLComparisonOperator						$strComparisonOperator
	 * @throws	SQLLanguageException
	 * @return 	SQLWhereClause
	 */
	public function set($strColumnName, $mixValue, $strComparisonOperator=SQLComparisonOperator::EQUALS) {
		if(!defined('SQLComparisonOperator::'.$strComparisonOperator)) throw new SQLLanguageException("Invalid comparison operator: ".$strComparisonOperator);
		$tblClause = array();
		$tblClause["KEY"]=$strColumnName;
		$tblClause["COMPARATOR"]=$strComparisonOperator;
		$tblClause["VALUE"]=$mixValue;
		$this->tblContents[]=$tblClause;
		return $this;
	}
	
	/**
	 * Adds an "IN/NOT IN" condition. 
	 * 
	 * @param 	string 										$strColumnName
	 * @param	mixed[] 									$tblValues
	 * @param 	boolean 									$blnIsTrue
	 * @return 	SQLWhereClause
	 */
	public function setIn($strColumnName, $tblValues, $blnIsTrue=true) {
		$tblClause = array();
		$tblClause["KEY"]=$strColumnName;
		$tblClause["COMPARATOR"]=($blnIsTrue?SQLComparisonOperator::IN:SQLComparisonOperator::NOT_IN);
		$tblClause["VALUE"]=$tblValues;		
		$this->tblContents[]=$tblClause;
		return $this;
	}
	
	/**
	 * Adds a "NULL/NOT NULL" condition.
	 * 
	 * @param 	string 										$strColumnName
	 * @param 	boolean 									$blnIsTrue
	 * @return 	SQLWhereClause
	 */
	public function setIsNull($strColumnName, $blnIsTrue=true) {
		$tblClause = array();
		$tblClause["KEY"]=$strColumnName;
		$tblClause["COMPARATOR"]=($blnIsTrue?SQLComparisonOperator::IS_NULL:SQLComparisonOperator::IS_NOT_NULL);
		$this->tblContents[]=$tblClause;
		return $this;
	}
	
	/**
	 * Sets up a "LIKE/NOT LIKE" condition.
	 * 
	 * @param 	string 										$strColumnName
	 * @param 	string 										$strPattern
	 * @param 	boolean 									$blnIsLike
	 * @return 	SQLWhereClause
	 */
	public function setLike($strColumnName, $strPattern, $blnIsTrue=true) {
		$tblClause = array();
		$tblClause["KEY"]=$strColumnName;
		$tblClause["COMPARATOR"]=($blnIsTrue?SQLComparisonOperator::LIKE:SQLComparisonOperator::NOT_LIKE);
		$tblClause["VALUE"]=$strPattern;
		$this->tblContents[]=$tblClause;
		return $this;
	}
	
	/**
	 * Sets up a "BETWEEN/NOT BETWEEN" condition.
	 * 
	 * @param 	string 										$strColumnName
	 * @param 	string 										$mixValueLeft
	 * @param 	string 										$mixValueRight
	 * @param 	boolean 									$blnIsBetween
	 * @return 	SQLWhereClause
	 */
	public function setBetween($strColumnName, $mixValueLeft, $mixValueRight, $blnIsTrue=true) {
		$tblClause = array();
		$tblClause["KEY"]=$strColumnName;
		$tblClause["COMPARATOR"]=($blnIsTrue?SQLComparisonOperator::BETWEEN:SQLComparisonOperator::NOT_BETWEEN);
		$tblClause["VALUE_LEFT"]=$mixValueLeft;
		$tblClause["VALUE_RIGHT"]=$mixValueRight;
		$this->tblContents[]=$tblClause;
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractSQLClause::toString()
	 */
	public function toString() {
		$output = "";		
		if($this->strCurrentLogicalOperator==SQLLogicalOperator::_NOT_) $output="NOT (";
		foreach($this->tblContents as $tblValues) {			
			// create condition
			if($tblValues["COMPARATOR"] == SQLComparisonOperator::IS_NULL || $tblValues["COMPARATOR"] == SQLComparisonOperator::IS_NOT_NULL) {
				$output .= $tblValues["KEY"]." ".$tblValues["COMPARATOR"];
			} else if($tblValues["COMPARATOR"] == SQLComparisonOperator::BETWEEN || $tblValues["COMPARATOR"] == SQLComparisonOperator::NOT_BETWEEN) {
				$output .= $tblValues["KEY"]." ".$tblValues["COMPARATOR"]." ".$tblValues["VALUE_LEFT"]." AND ".$tblValues["VALUE_RIGHT"];
			} else if($tblValues["COMPARATOR"] == SQLComparisonOperator::IN || $tblValues["COMPARATOR"] == SQLComparisonOperator::NOT_IN) {
				$tblTMP = $tblValues["VALUE"];
				$strValues = "";
				foreach($tblTMP as $strString) {
					$strValues .= $strString.",";
				}
				$output .= $tblValues["KEY"]." ".$tblValues["COMPARATOR"]." (".substr($strValues,0,-1).")";
			} else {
				$output .= $tblValues["KEY"]." ".$tblValues["COMPARATOR"]." ".$tblValues["VALUE"];
			}
			
			$output .=" ".($this->strCurrentLogicalOperator==SQLLogicalOperator::_NOT_?SQLLogicalOperator::_AND_:$this->strCurrentLogicalOperator)." ";
		}
		return substr($output,0, -2-strlen($this->strCurrentLogicalOperator==SQLLogicalOperator::_NOT_?SQLLogicalOperator::_AND_:$this->strCurrentLogicalOperator)).($this->strCurrentLogicalOperator==SQLLogicalOperator::_NOT_?")":"");
	}
}