<?php
require_once("AbstractSQLClause.php");

/**
 * Encapsulates SQL clause: AS.
 */
class SQLAliasClause extends AbstractSQLClause {
	protected $strFieldName;
	protected $strFieldAlias;
	
	/**
	 * Sets up an alias clause.
	 * 
	 * @param string $strFieldName
	 * @param string $strFieldAlias
	 */
	public function __construct($strFieldName, $strFieldAlias) {
		$this->strFieldName = $strFieldName;
		$this->strFieldAlias = $strFieldAlias;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractSQLClause::toString()
	 */
	public function toString() {
		return $this->strFieldName." AS ".$this->strFieldAlias;
	}
}