<?php
namespace Lucinda\Query;

require_once("AbstractClause.php");

/**
 * Encapsulates SQL update SET clause
 */
class Set extends AbstractClause {
    /**
     * Constructor
     * @param string[string] $contents
     */
    public function __construct($contents = array()) {
        $this->contents = $contents;
    }

	/**
	 * Sets a column value.
	 * 
	 * @param string $columnName
	 * @param mixed $value
	 * @return Set
	 */
	public function set($columnName, $value) {
		$this->contents[$columnName]=$value;
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractClause::toString()
	 */
	public function toString() {
		$output = "";		
		foreach($this->contents as $key=>$value) {
			$output .= $key." = ".$value.", ";
		}
		return substr($output,0,-2);
	}
}