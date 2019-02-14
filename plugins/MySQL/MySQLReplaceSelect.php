<?php
namespace Lucinda\Query;
require_once(dirname(__DIR__, 2)."/src/InsertSelect.php");
require_once(dirname(__DIR__, 2)."/src/clauses/Set.php");

/**
 * Encapsulates MySQL statement: REPLACE INTO {TABLE} ({COLUMNS}) {SELECT}
 */
class MySQLReplaceSelect extends InsertSelect {
    /**
     * Compiles SQL statement based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     * @throws Exception When statement could not be compiled due to incomplete class fields.
     */
    public function toString() {
        if(!$this->columns || $this->columns->isEmpty()) throw new Exception("running columns() method is required!");
        if(!$this->select) throw new Exception("running select() method is required!");

        return  "REPLACE INTO ".$this->table." (".$this->columns->toString().")"."\r\n".
            $this->select->toString();
    }
}