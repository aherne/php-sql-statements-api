<?php
namespace Lucinda\Query;
require_once(dirname(dirname(__DIR__))."/src/InsertSelect.php");
require_once(dirname(dirname(__DIR__))."/src/clauses/Set.php");

class MySQLInsertSelect extends InsertSelect {
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
        if(!$this->columns) throw new Exception("running columns() method is required!");
        if(!$this->select) throw new Exception("running select() method is required!");

        return  "INSERT ".($this->isIgnore?"IGNORE":"")." INTO ".$this->table." (".$this->columns->toString().")"."\r\n".
            $this->select->toString().
            ($this->onDuplicateKeyUpdate?"\r\n"."ON DUPLICATE KEY UPDATE ".$this->onDuplicateKeyUpdate->toString():"");
	}
}