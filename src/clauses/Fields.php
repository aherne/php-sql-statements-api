<?php
namespace Lucinda\Query;

require_once("AbstractClause.php");
require_once("Alias.php");

/**
 * Encapsulates SQL select fields clause.
 */
class Fields extends AbstractClause {
    /**
     * Class constructor.
     *
     * @param string[] $contents
     */
    public function __construct($contents = array())
    {
        $this->contents = $contents;
    }

    /**
	 * Adds column to list.
	 * 
	 * @param string $columnName
	 * @return Fields
	 */
	public function add($columnName, $columnAlias = null) {
		$this->contents[]=($columnAlias?new Alias($columnName, $columnAlias):$columnName);
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractClause::toString()
	 */
	public function toString() {
		$strOutput = "";
		if(!sizeof($this->contents)) return $strOutput;
		
		foreach($this->contents as $value) {
			$strOutput .= ($value instanceof Alias?$value->toString():$value).", ";
		}
		
		return substr($strOutput,0,-2);
	} 
}