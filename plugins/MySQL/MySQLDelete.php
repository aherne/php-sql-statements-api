<?php
namespace Lucinda\Query;
require_once(dirname(__DIR__, 2)."/src/Delete.php");

/**
 * Encapsulates MySQL statement: DELETE {IGNORE} FROM {TABLE} WHERE {CONDITION}
 */
class MySQLDelete extends Delete {
	protected $isIgnore=false;
	
	public function ignore() {
		$this->isIgnore = true;
		return $this;
	}
	
	public function toString() {
        return "DELETE ".($this->isIgnore?"IGNORE":"")." FROM ".$this->table.
            ($this->where?"\r\n"."WHERE ".$this->where->toString():"");
	}
}