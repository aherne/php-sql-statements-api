<?php
namespace Lucinda\Query;

require_once("Exception.php");
require_once("AbstractStatement.php");
require_once("clauses/Alias.php");
require_once("clauses/Fields.php");
require_once("clauses/Columns.php");
require_once("clauses/Join.php");
require_once("clauses/Limit.php");
require_once("clauses/OrderBy.php");
require_once("clauses/Condition.php");

/**
 * Encapsulates SQL statement:
 *
 * SELECT {FIELDS}
 * FROM {TABLE}
 * {TYPE} JOIN ON {CONDITION}
 * ...
 * WHERE {CONDITION}
 * GROUP BY {COLUMNS}
 * HAVING {CONDITION}
 * ORDER BY {ORDER_BY}
 * LIMIT {LIMIT}
 */
class Select extends AbstractStatement {
	protected $isDistinct=false;
	protected $columns;
	protected $joins=array();
	protected $where;
	protected $groupBy;
	protected $having;
	protected $orderBy;
	protected $limit;
    protected $table;

    public function __construct($table, $alias="") {
        $this->table = ($alias?new Alias($table, $alias):$table);
    }

	public function distinct() {
		$this->isDistinct=true;
	}

	public function fields($columns = array()) {
		$columns = new Fields($columns);
		$this->columns = $columns;
		return $columns;
	}

	public function joinLeft($tableName, $tableAlias = null) {
		$join = new Join($tableName, $tableAlias, JoinOperator::LEFT);
		$this->joins[]=$join;
		return $join;
	}

    public function joinRight($tableName, $tableAlias = null) {
        $join = new Join($tableName, $tableAlias, JoinOperator::RIGHT);
        $this->joins[]=$join;
        return $join;
    }

    public function joinInner($tableName, $tableAlias = null) {
        $join = new Join($tableName, $tableAlias, JoinOperator::INNER);
        $this->joins[]=$join;
        return $join;
    }

    public function joinCross($tableName, $tableAlias = null) {
        $join = new Join($tableName, $tableAlias, JoinOperator::CROSS);
        $this->joins[]=$join;
        return $join;
    }

	public function where($condition=array(), $logicalOperator=LogicalOperator::_AND_) {
		$where = new Condition($condition, $logicalOperator);
		$this->where=$where;
		return $where;
	}

	public function groupBy($columns = array()) {
		$columns = new Columns($columns);
		$this->groupBy = $columns;
		return $columns;
	}

	public function having($condition=array(), $logicalOperator=LogicalOperator::_AND_){
        $where = new Condition($condition, $logicalOperator);
		$this->having=$where;
		return $where;
	}

	public function orderBy($fields = array()) {
		$orderBy = new OrderBy($fields);
		$this->orderBy = $orderBy;
		return $orderBy;
	}

	public function limit($intLimit, $offset=0) {
		$this->limit = new Limit($intLimit, $offset);
	}

	public function __toString()
    {
        return $this->toString();
    }

    public function toString() {
		$strOutput = 
				"SELECT".($this->isDistinct?" DISTINCT":"").
                "\r\n".($this->columns?$this->columns->toString():"*").
                "\r\n"."FROM ".$this->table;
		if(sizeof($this->joins)>0) 	{
			foreach($this->joins as $join) {
				$strOutput .= "\r\n".$join->toString();
			}
		}
		$strOutput .=
				($this->where?"\r\nWHERE ".$this->where->toString():"").
				($this->groupBy?"\r\nGROUP BY ".$this->groupBy->toString():"").
				($this->having?"\r\nHAVING ".$this->having->toString():"").
				($this->orderBy?"\r\nORDER BY ".$this->orderBy->toString():"").
				($this->limit?"\r\nLIMIT ".$this->limit->toString():"");
		return $strOutput;
	}
} 