<?php
namespace Lucinda\Query;
require_once(dirname(dirname(__DIR__))."/src/InsertSelect.php");
require_once(dirname(dirname(__DIR__))."/src/clauses/Set.php");

class MySQLReplaceSelect extends InsertSelect {
    public function toString() {
        if(!$this->columns) throw new Exception("running columns() method is required!");
        if(!$this->select) throw new Exception("running select() method is required!");

        return  "REPLACE INTO ".$this->table." (".$this->columns->toString().")"."\r\n".
            $this->select->toString();
    }
}