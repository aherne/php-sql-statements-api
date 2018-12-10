<?php
namespace Lucinda\Query;

require_once("AbstractClause.php");

/**
 * Encapsulates SQL LIMIT clause
 */
class Limit extends AbstractClause {
	protected $limit;
	protected $offset;
	
	/**
	 * Sets up clause directly from constructor. 
	 * 
	 * @param integer $limit
	 * @param integer $offset
	 */
	public function __construct($limit, $offset=0) {
		$this->limit = $limit;
		$this->offset = $offset;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractClause::toString()
	 */
	public function toString() {
		return ($this->offset?$this->limit." OFFSET ".$this->offset:$this->limit);
	}
}