<?php
namespace Lucinda\Query;

use Lucinda\Query\Operator\Set;
use Lucinda\Query\Clause\OrderBy;
use Lucinda\Query\Clause\Limit;

/**
 * Encapsulates SQL statement grouping two or more SELECT statements:
 * ({SELECT})
 * {OPERATOR}
 * ({SELECT})
 * ...
 * ORDER BY {ORDER_BY}
 * LIMIT {LIMIT}
 */
class SelectGroup implements Stringable
{
    protected $operator;
    protected $orderBy;
    protected $limit;
    protected $contents=array();

    /**
     * @param Set $operator Enum holding operator that will link SELECT statements in group (default: UNION)
     */
    public function __construct(int $operator = Set::UNION)
    {
        $this->operator = $operator;
    }

    /**
     * Adds select statement to group
     *
     * @param Stringable $select Instance of Select or SelectGroup
     * @return Stringable Instance of Select or SelectGroup
     */
    public function addSelect(Stringable $select): Stringable
    {
        $this->contents[] = $select;
        return $select;
    }

    /**
     * Sets up ORDER BY clause
     *
     * @param string[] $fields Sets list of columns to order by directly in ASC mode
     * @return OrderBy Object to set further clauses on.
     */
    public function orderBy(array $fields = array()): OrderBy
    {
        $orderBy = new OrderBy($fields);
        $this->orderBy = $orderBy;
        return $orderBy;
    }

    /**
     * Sets a LIMIT clause
     *
     * @param integer $limit Sets how many rows SELECT will return.
     * @param integer $offset Optionally sets offset to start limiting with.
     */
    public function limit(int $limit, int $offset=0): void
    {
        $this->limit = new Limit($limit, $offset);
    }

    /**
     * Converts object to SQL statement.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * Compiles SQL statement based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     */
    public function toString(): string
    {
        if (empty($this->contents)) {
            throw new Exception("running addSelect() method is mandatory");
        }
        $output="";
        foreach ($this->contents as $objValue) {
            $output.="(\r\n".$objValue->toString()."\r\n)"."\r\n".$this->operator."\r\n";
        }
        $output = substr($output, 0, -strlen($this->operator)-2);
        return $output.
                ($this->orderBy && !$this->orderBy->isEmpty()?"\r\nORDER BY ".$this->orderBy->toString():"").
                ($this->limit?"\r\nLIMIT ".$this->limit->toString():"");
    }
}
