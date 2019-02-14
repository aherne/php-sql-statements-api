<?php
namespace Lucinda\Query;

require_once(dirname(__DIR__, 2)."/src/clauses/Set.php");
require_once(dirname(__DIR__, 2)."/src/Stringable.php");

/**
 * Encapsulates MySQL statement: REPLACE INTO {TABLE} SET {SET}
 */
class MySQLReplaceSet implements Stringable {
    protected $table;
    protected $set;

    /**
     * @param string $table Name of table to replace into (including schema)
     */
    public function __construct($table) {
        $this->table = $table;
    }

    /**
     * Sets up SET clause.
     *
     * @param string[string] $contents Sets condition group directly by column name and value
     * @return Set Object to write further set clauses on.
     */
    public function set($contents = array()) {
        $set = new Set($contents);
        $this->set = $set;
        return $set;
    }

    /**
     * Compiles SQL statement based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     * @throws Exception When statement could not be compiled due to incomplete class fields.
     */
    public function toString() {
        if(!$this->set || $this->set->isEmpty()) throw new Exception("running set() method is mandatory");

        return "REPLACE INTO ".$this->table." SET"."\r\n".
            $this->set->toString();
    }
}