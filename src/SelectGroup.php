<?php
namespace Lucinda\Query;

require_once("Select.php");
require_once("clauses/SetOperator.php");

/**
 * Encapsulates SQL statement grouping two or more SELECT statements:
 * ({SELECT})
 * {OPERATOR}
 * ({SELECT})
 * ...
 * ORDER BY {ORDER_BY}
 * LIMIT {LIMIT}
 */
class SelectGroup implements Stringable {
	protected $operator;
	protected $orderBy;
	protected $limit;
	protected $tblContents=array();

    /**
     * @param SetOperator $operator Enum holding operator that will link SELECT statements in group (default: UNION)
     */
	public function __construct($operator = SetOperator::UNION) {
		$this->operator = $operator;
	}

    /**
     * Adds select statement to group
     *
     * @param Stringable $select Instance of Select or SelectGroup
     * @return Stringable Instance of Select or SelectGroup
     */
	public function addSelect(Stringable $select) {
		$this->tblContents[] = $select;
		return $select;
	}

    /**
     * Sets up ORDER BY clause
     *
     * @param string[] $fields Sets list of columns to order by directly in ASC mode
     * @return OrderBy Object to set further clauses on.
     */
    public function orderBy($fields = array()) {
        $orderBy = new OrderBy($fields);
        $this->orderBy = $orderBy;
        return $orderBy;
    }

    /**
     * Sets a LIMIT clause
     *
     * @param integer $limit Sets how many rows SELECT will return.
     * @param integer $offset Optionally sets offset to start limiting with.
     */
    public function limit($limit, $offset=0) {
        $this->limit = new Limit($limit, $offset);
    }

    /**
     * Converts object to SQL statement.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Compiles SQL statement based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     */
	public function toString() {
			$strOutput="";
			foreach($this->tblContents as $objValue) {
				$strOutput.="(\r\n".$objValue->toString()."\r\n)"."\r\n".$this->operator."\r\n";
			}
			$strOutput = substr($strOutput, 0, -strlen($this->operator)-2);
			return $strOutput.
					($this->orderBy?"\r\nORDER BY ".$this->orderBy->toString():"").
					($this->limit?"\r\nLIMIT ".$this->limit->toString():"");
	}
} 