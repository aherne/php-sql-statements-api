<?php
namespace Lucinda\Query\Clause;

use Lucinda\Query\Clause\Window\Over;
use Lucinda\Query\Exception;
use Lucinda\Query\Stringable;
use Lucinda\Query\Validator;

/**
 * Encapsulates SQL select fields clause.
 */
class Fields implements Stringable
{
    protected $contents = [];
    /**
     * @var Validator
     */
    private $validator;

    /**
     * Class constructor.
     *
     * @param string[] $contents
     */
    public function __construct(array $contents = [])
    {
        $this->validator = new Validator();
        $this->contents = $contents;
    }

    /**
     * Adds column to list.
     *
     * @param string|Stringable $columnDefinition Column definition (name or expression)
     * @param string $columnAlias Optional column alias
     * @return Fields
     */
    public function add($columnDefinition, string $columnAlias = ""): Fields
    {
        $this->contents[]=$this->validator->validateSelectField($columnDefinition, $columnAlias);
        return $this;
    }

    /**
     * Adds aggregate function with windowing to list.
     *
     * @param string $aggregateFunction Aggregate function (e.g. SUM(col))
     * @param string|Over $window Window name or definition
     * @param string $columnAlias Column alias (mandatory)
     * @return Fields
     * @throws Exception
     */
    public function over(string $aggregateFunction, $window, string $columnAlias): Fields
    {
        if ($window instanceof Stringable) {
            $windowName = "(". $window->toString() .")";
        } else {
            $windowName = $window;
        }
        $this->contents[] = $aggregateFunction." OVER ".$windowName." AS ".$columnAlias;
        return $this;
    }

    /**
     * Compiles SQL clause based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     */
    public function toString(): string
    {
        $output = "";
        if (!sizeof($this->contents)) {
            return $output;
        }
        
        foreach ($this->contents as $value) {
            $output .= $value.", ";
        }
        
        return substr($output, 0, -2);
    }

    /**
     * Checks if clause is empty
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return sizeof($this->contents) == 0;
    }
}
