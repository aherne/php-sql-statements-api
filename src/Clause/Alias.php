<?php
namespace Lucinda\Query\Clause;

use Lucinda\Query\Stringable;

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
    public function __construct(string $fieldName, string $fieldAlias)
    {
        $this->fieldName = $fieldName;
        $this->fieldAlias = $fieldAlias;
    }
    
    /**
     * Converts object to SQL statement.
     *
     * @return string SQL that results from conversion
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * Compiles SQL clause based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     */
    public function toString(): string
    {
        return $this->fieldName." AS ".$this->fieldAlias;
    }
}
