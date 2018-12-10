<?php
namespace Lucinda\Query;

require_once("AbstractClause.php");
require_once("OrderByOperator.php");

/**
 * Encapsulates SQL ORDER BY clause
 */
class OrderBy extends AbstractClause {
    /**
     * Class constructor.
     *
     * @param string[] $fields
     */
	public function __construct($fields = array())
    {
        foreach($fields as $field) {
            $this->contents[$field] = OrderByOperator::ASC;
        }
    }

    /**
	 * Makes results sorted by input column ascendently.
	 * 
	 * @param string $columnName
     * @param OrderByOperator $operator
	 * @return OrderBy
	 */
	public function add($columnName, $operator = OrderByOperator::ASC) {
		$this->contents[$columnName] = $operator;
		return $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see AbstractClause::toString()
	 */
	public function toString() {
		$output = "";
		foreach($this->contents as $key=>$value) {
			$output .= $key." ".$value.", ";
		}
		return substr($output,0,-2);
	}
}