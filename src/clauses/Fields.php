<?php
namespace Lucinda\Query;

require_once("Alias.php");

/**
 * Encapsulates SQL select fields clause.
 */
class Fields implements Stringable {
    protected $contents = array();

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
     * @param string $columnName Column name
     * @param string $columnAlias Optional column alias
	 * @return Fields
	 */
	public function add($columnName, $columnAlias = null) {
		$this->contents[]=($columnAlias?new Alias($columnName, $columnAlias):$columnName);
		return $this;
	}

    /**
     * Compiles SQL clause based on data collected in class fields.
     *
     * @return string SQL that results from conversion
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