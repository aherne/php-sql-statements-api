<?php
namespace Lucinda\Query;
require_once(dirname(dirname(__DIR__))."/src/InsertSelect.php");
require_once(dirname(dirname(__DIR__))."/src/clauses/Set.php");

/**
 * Encapsulates MySQL statement: INSERT {IGNORE} INTO {TABLE} ({COLUMNS}) {SELECT} ON DUPLICATE KEY UPDATE {UPDATES}
 */
class MySQLInsertSelect extends InsertSelect {
    protected $isIgnore=false;
    protected $onDuplicateKeyUpdate;

    /**
     * Sets statement as "IGNORE" (ignoring foreign key errors / duplicates)
     */
    public function ignore() {
        $this->isIgnore = true;
        return $this;
    }

    /**
     * Sets up ON DUPLICATE KEY UPDATE clause.
     *
     * @param string[string] $contents Sets condition group directly by column name and value
     * @return Set Object to write further set clauses on.
     */
    public function onDuplicateKeyUpdate($contents = array()) {
        $set = new Set($contents);
        $this->onDuplicateKeyUpdate=$set;
        return $set;
    }

    /**
     * Compiles SQL statement based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     * @throws Exception When statement could not be compiled due to incomplete class fields.
     */
	public function toString() {
        if(!$this->columns) throw new Exception("running columns() method is required!");
        if(!$this->select) throw new Exception("running select() method is required!");

        return  "INSERT ".($this->isIgnore?"IGNORE":"")." INTO ".$this->table." (".$this->columns->toString().")"."\r\n".
            $this->select->toString().
            ($this->onDuplicateKeyUpdate?"\r\n"."ON DUPLICATE KEY UPDATE ".$this->onDuplicateKeyUpdate->toString():"");
	}
}