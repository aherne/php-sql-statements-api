<?php
namespace Lucinda\Query;

require_once(dirname(__DIR__)."/Stringable.php");

/**
 * Encapsulates column lists clause
 */
class Columns implements Stringable
{
    protected $contents = array();

    /**
     * @param string[] $contents Sets list of columns directly
     */
    public function __construct($contents = array())
    {
        $this->contents = $contents;
    }

    /**
     * Adds column to list.
     *
     * @param string $columnName Name of column to add
     * @return Columns Object to set further columns on.
     */
    public function add($columnName)
    {
        $this->contents[]= $columnName;
        return $this;
    }

    /**
     * Compiles SQL clause based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     */
    public function toString()
    {
        $strOutput = "";
        if (!sizeof($this->contents)) {
            return $strOutput;
        }

        foreach ($this->contents as $value) {
            $strOutput .= $value.", ";
        }

        return substr($strOutput, 0, -2);
    }

    /**
     * Checks if clause is empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return sizeof($this->contents) == 0;
    }
}
