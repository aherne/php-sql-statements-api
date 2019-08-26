<?php
namespace Lucinda\Query;

/**
 * Interface providing blueprint of translating PHP data in statements/clauses classes to SQL
 */
interface Stringable
{
    /**
     * Converts php data in statement/clauses classes to SQL
     *
     * @return string SQL that results from conversion
     * @throws Exception When data in statement/clause couldn't be converted to a valid SQL
     */
    public function toString();
}
