<?php
require_once("../AbstractSQLTableStatement.php");
/**
 * Implements standard SQL constraint drop logic.
 */
abstract class AbstractSQLTableKeyDropStatement extends AbstractSQLTableStatement {
	protected $strConstraintName;

	/**
	 * Sets name constraint is known as.
	 *
	 * @param string $strConstraintName
	 */
	public function setConstraintName($strConstraintName) {
		$this->strConstraintName = $strConstraintName;
		return $this;
	}
}