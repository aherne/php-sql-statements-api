<?php
/**
 * Abstract class covering base clause functionality
 */
abstract class AbstractSQLClause {
	protected $tblContents = array();
	
	/**
	 * Transforms clause into an array
	 * 
	 * @return array
	 */
	public function toArray() {
		return $this->tblContents;
	}
	
	/**
	 * Transforms clause into a string
	 * 
	 * @abstract
	 * @return string
	 */
	abstract public function toString();
}