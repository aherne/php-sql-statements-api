<?php
namespace Lucinda\Query;

require_once(dirname(__DIR__)."/Stringable.php");

/**
 * Encapsulates SQL clause: AS.
 */
class Alias implements Stringable
{
    protected $fieldName;
    protected $fieldAlias;
    
    /**
     * Sets up an alias clause.
     *
     * @param string $fieldName Column/table name
     * @param string $fieldAlias Column/table alias
     */
    public function __construct($fieldName, $fieldAlias)
    {
        $this->fieldName = $fieldName;
        $this->fieldAlias = $fieldAlias;
    }

    /**
     * Converts object to SQL statement.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Compiles SQL clause based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     */
    public function toString()
    {
        return $this->fieldName." AS ".$this->fieldAlias;
    }
}
