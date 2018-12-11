<?php
namespace Lucinda\Query;

require_once(dirname(__DIR__, 2)."/src/Delete.php");
require_once("clauses/MySQLCondition.php");

/**
 * Encapsulates MySQL statement: DELETE {IGNORE} FROM {TABLE} WHERE {CONDITION}
 */
class MySQLDelete extends Delete {
	protected $isIgnore=false;

    /**
     * Sets statement as "IGNORE" (ignoring foreign key errors / duplicates)
     */
	public function ignore() {
		$this->isIgnore = true;
		return $this;
	}

    /**
     * Compiles SQL statement based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     */
	public function toString() {
        return "DELETE ".($this->isIgnore?"IGNORE":"")." FROM ".$this->table.
            ($this->where && !$this->where->isEmpty()?"\r\n"."WHERE ".$this->where->toString():"");
	}
}