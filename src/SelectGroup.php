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
class SelectGroup extends AbstractStatement {
	protected $operator;
	protected $orderBy;
	protected $limit;
	protected $tblContents=array();

	public function __construct($operator = SetOperator::UNION) {
		$this->operator = $operator;
	}

	public function addSelect(AbstractStatement $select) {
		$this->tblContents[] = $select;
		return $select;
	}

    public function orderBy($fields = array()) {
        $orderBy = new OrderBy($fields);
        $this->orderBy = $orderBy;
        return $orderBy;
    }

    public function limit($intLimit, $offset=0) {
        $this->limit = new Limit($intLimit, $offset);
    }

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