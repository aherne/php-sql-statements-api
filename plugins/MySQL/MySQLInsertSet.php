<?php
namespace Lucinda\Query;

require_once(dirname(dirname(__DIR__))."/src/clauses/Set.php");
require_once(dirname(dirname(__DIR__))."/src/AbstractStatement.php");

class MySQLInsertSet extends AbstractStatement {
    protected $table;
    protected $set;
    protected $isIgnore=false;
    protected $onDuplicateKeyUpdate;

    public function __construct($table) {
        $this->table = $table;
    }

    public function ignore() {
        $this->isIgnore = true;
        return $this;
    }

    public function set($contents = array()) {
        $set = new Set($contents);
        $this->set = $set;
        return $set;
    }

    public function onDuplicateKeyUpdate($contents = array()) {
        $set = new Set($contents);
        $this->onDuplicateKeyUpdate=$set;
        return $set;
    }

    public function toString() {
        if(!$this->set) throw new Exception("running set() method is mandatory");

        return "INSERT ".($this->isIgnore?"IGNORE":"")." INTO ".$this->table." SET"."\r\n".
            $this->set->toString().
            ($this->onDuplicateKeyUpdate?"\r\n"."ON DUPLICATE KEY UPDATE ".$this->onDuplicateKeyUpdate->toString():"");
    }
}