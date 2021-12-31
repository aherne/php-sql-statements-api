<?php
namespace Lucinda\Query;

use Lucinda\Query\Operator\Set AS SetOperator;
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
class SelectGroup implements \Stringable
{
    protected SetOperator $operator;
    protected ?OrderBy $orderBy = null;
    protected ?Limit $limit = null;
    protected array $contents=[];

    /**
     * Constructs a SELECT ... OPERATOR ... SELECT statement based on Set OPERATOR
     * 
     * @param SetOperator $operator Enum holding operator that will link SELECT statements in group (default: UNION)
     */
    public function __construct(SetOperator $operator = SetOperator::UNION)
    {
        $this->operator = $operator;
    }

    /**
     * Adds select statement to group
     *
     * @param Select|SelectGroup $select Instance of Select or SelectGroup
     */
    public function addSelect(Select|SelectGroup $select): void
    {
        $this->contents[] = $select;
    }

    /**
     * Sets up ORDER BY clause
     *
     * @param string[] $fields Sets list of columns to order by directly in ASC mode
     * @return OrderBy Object to set further clauses on.
     */
    public function orderBy(array $fields = []): OrderBy
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
     * Compiles SQL statement based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     * @throws Exception
     */
    public function __toString(): string
    {
        if (empty($this->contents)) {
            throw new Exception("running addSelect() method is mandatory");
        }
        $output="";
        foreach ($this->contents as $objValue) {
            $output.="(\r\n".$objValue."\r\n)"."\r\n".$this->operator->value."\r\n";
        }
        $output = substr($output, 0, -strlen($this->operator->value)-2);
        return $output.
                ($this->orderBy && !$this->orderBy->isEmpty()?"ORDER BY ".$this->orderBy."\r\n":"").
                ($this->limit?"LIMIT ".$this->limit:"");
    }
}
