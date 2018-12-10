<?php
namespace Lucinda\Query;

require_once("Exception.php");
require_once("AbstractStatement.php");
/**
 * Encapsulates SQL statement: TRUNCATE TABLE {TABLE}
 */
class Truncate extends AbstractStatement {
    protected $table;

    public function __construct($table) {
        $this->table = $table;
    }

	public function toString() {
		return "TRUNCATE TABLE ".$this->table;
	}
}