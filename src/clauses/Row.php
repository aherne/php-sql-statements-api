<?php
namespace Lucinda\Query;

require_once("AbstractClause.php");

/**
 * Encapsulates SQL row clause to be used by INSERT INTO ... VALUES statements
 */
class Row extends AbstractClause {
    /**
     * Constructor
     * @param string[] $contents
     */
    public function __construct($contents = array()) {
        $this->contents = $contents;
    }

    /**
     * (non-PHPdoc)
     * @see AbstractClause::toString()
     */
	public function toString() {
		$output = "";		
		foreach($this->contents as $mixValue) {
			$output .= $mixValue.", ";
		}
		return "(".substr($output,0,-2).")";
	}
}