<?php
namespace Lucinda\Query;

require_once(dirname(dirname(__DIR__))."/src/clauses/Set.php");
require_once(dirname(dirname(__DIR__))."/src/AbstractStatement.php");

class MySQLReplaceSet extends AbstractStatement {
    protected $table;
    protected $set;

    public function __construct($table) {
        $this->table = $table;
    }

    public function set($contents = array()) {
        $set = new Set($contents);
        $this->set = $set;
        return $set;
    }

    public function toString() {
        if(!$this->set) throw new Exception("running set() method is mandatory");

        return "REPLACE INTO ".$this->table." SET"."\r\n".
            $this->set->toString();
    }
}