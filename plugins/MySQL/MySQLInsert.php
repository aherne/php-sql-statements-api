<?php
namespace Lucinda\Query;
require_once(dirname(dirname(__DIR__))."/src/Insert.php");
require_once(dirname(dirname(__DIR__))."/src/clauses/Set.php");

class MySQLInsert extends Insert {
	protected $isIgnore=false;
	protected $onDuplicateKeyUpdate;

	public function ignore() {
		$this->isIgnore = true;
		return $this;
	}
	
	public function onDuplicateKeyUpdate($contents = array()) {
		$set = new Set($contents);
		$this->onDuplicateKeyUpdate=$set;
		return $set;
	}
	
	public function toString() {
        if(!$this->columns) throw new Exception("running columns() method is mandatory");
        if(!$this->rows) throw new Exception("running values() is mandatory");

        $output = "INSERT ".($this->isIgnore?"IGNORE":"")." INTO ".$this->table." (".$this->columns->toString().") VALUES "."\r\n";
        foreach($this->rows as $row) {
            $output.=$row->toString().", ";
        }
		return substr($output,0,-2).($this->onDuplicateKeyUpdate?"\r\n"."ON DUPLICATE KEY UPDATE ".$this->onDuplicateKeyUpdate->toString():"");
	}
}