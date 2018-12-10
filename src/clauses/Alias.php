<?php
namespace Lucinda\Query;

require_once("AbstractClause.php");

/**
 * Encapsulates SQL clause: AS.
 */
class Alias extends AbstractClause {
	protected $fieldName;
	protected $fieldAlias;
	
	/**
	 * Sets up an alias clause.
	 * 
	 * @param string $fieldName
	 * @param string $fieldAlias
	 */
	public function __construct($fieldName, $fieldAlias) {
		$this->fieldName = $fieldName;
		$this->fieldAlias = $fieldAlias;
	}

	public function __toString()
    {
        return $this->toString();
    }

    /**
	 * (non-PHPdoc)
	 * @see AbstractClause::toString()
	 */
	public function toString() {
		return $this->fieldName." AS ".$this->fieldAlias;
	}
}