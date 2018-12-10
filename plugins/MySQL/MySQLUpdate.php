<?php
namespace Lucinda\Query;
require_once(dirname(dirname(__DIR__))."/src/Update.php");
require_once("clauses/MySQLCondition.php");

/**
 * Encapsulates MySQL statement: DELETE {IGNORE} FROM {TABLE} WHERE {CONDITION}
 */
class MySQLUpdate extends Update {
    protected $isIgnore=false;

    public function ignore() {
        $this->isIgnore = true;
        return $this;
    }

    public function toString() {
        if(!$this->set) throw new Exception("running set() method is required");

        return "UPDATE ".($this->isIgnore?"IGNORE ":"").$this->table.
            "\r\n"."SET ".$this->set->toString().
            ($this->where?"\r\n"."WHERE ".$this->where->toString():"");
    }
}