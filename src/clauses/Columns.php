<?php
namespace Lucinda\Query;

require_once("AbstractClause.php");

/**
 * Encapsulates columns in SQL INSERT INTO {table} (COLUMNS) VALUES clause.
 */
class Columns extends AbstractClause {
    /**
     * Class constructor.
     *
     * @param string[] $contents
     */
    public function __construct($contents = array())
    {
        $this->contents = $contents;
    }

    /**
     * Adds column to list.
     *
     * @param string $columnName
     * @return Columns
     */
    public function add($columnName) {
        $this->contents[]= $columnName;
        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see AbstractClause::toString()
     */
    public function toString() {
        $strOutput = "";
        if(!sizeof($this->contents)) return $strOutput;

        foreach($this->contents as $value) {
            $strOutput .= $value.", ";
        }

        return substr($strOutput,0,-2);
    }
}