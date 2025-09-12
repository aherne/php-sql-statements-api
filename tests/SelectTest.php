<?php
namespace Test\Lucinda\Query;

use Lucinda\Query\Clause\OrderBy;
use Lucinda\Query\Clause\Window\Over;
use Lucinda\Query\Clause\Window\PartitionBy;
use Lucinda\Query\Select;
use Lucinda\UnitTest\Result;
use Lucinda\Query\Operator\Comparison;

class SelectTest
{
    private $object;
    
    public function __construct()
    {
        $this->object = new Select("q", "t1");
    }

    public function distinct()
    {
        $this->object->distinct();
        return new Result(true); // tested by toString
    }
        

    public function fields()
    {
        $this->object->fields(["a", "s"]);
        return new Result(true); // tested by toString
    }
        

    public function joinLeft()
    {
        $this->object->joinLeft("w", "t2")->on(["t1.z"=>"t2.b"]);
        return new Result(true); // tested by toString
    }
        

    public function joinRight()
    {
        $this->object->joinRight("e", "t3")->on(["t2.x"=>"t3.n"]);
        return new Result(true); // tested by toString
    }
        

    public function joinInner()
    {
        $this->object->joinInner("r", "t4")->on(["t3.c"=>"t4.m"]);
        return new Result(true); // tested by toString
    }
        

    public function joinCross()
    {
        $this->object->joinCross("t", "t5")->on(["t4.v"=>"t5.k"]);
        return new Result(true); // tested by toString
    }
        

    public function where()
    {
        $this->object->where(["t1.f"=>11]);
        return new Result(true); // tested by toString
    }
        

    public function groupBy()
    {
        $this->object->groupBy(["t1.id"]);
        return new Result(true); // tested by toString
    }
        

    public function having()
    {
        $this->object->having()->set("t2.g", 18, Comparison::GREATER);
        return new Result(true); // tested by toString
    }
        

    public function orderBy()
    {
        $this->object->orderBy(["t2.g"]);
        return new Result(true); // tested by toString
    }
        

    public function limit()
    {
        $this->object->limit(10, 20);
        return new Result(true); // tested by toString
    }
        

    public function __toString()
    {
        return $this->object->toString();
    }
        

    public function toString()
    {
        $normalized = $this->normalize($this->object->toString());
        return new Result($normalized=="SELECT DISTINCT a, s FROM q AS t1 LEFT OUTER JOIN w AS t2 ON t1.z = t2.b RIGHT OUTER JOIN e AS t3 ON t2.x = t3.n INNER JOIN r AS t4 ON t3.c = t4.m CROSS JOIN t AS t5 ON t4.v = t5.k WHERE t1.f = 11 GROUP BY t1.id HAVING t2.g > 18 ORDER BY t2.g ASC LIMIT 10 OFFSET 20");
    }
    public function window()
    {
        /**
         * SELECT
         * AVG(sales) OVER w AS moving_avg,
         * SUM(sales) OVER w AS moving_sum
         * FROM orders
         * WINDOW w AS (PARTITION BY region ORDER BY date);
         * */
        $select = new Select("orders");
        $fields = $select->fields();
        $fields->over("AVG(sales)", "w", "moving_avg");
        $fields->over("SUM(sales)", "w", "moving_sum");
        $select->window()->add("w", new Over(new PartitionBy(["region"]), new OrderBy(["date"])));
        $normalized = $this->normalize($select->toString());
        return new Result($normalized == "SELECT AVG(sales) OVER w AS moving_avg, SUM(sales) OVER w AS moving_sum FROM orders WINDOW w AS (PARTITION BY region ORDER BY date ASC)");
    }
        

    public function with()
    {
        /**
         * WITH EmployeeRanks AS (
         * SELECT
         * employee_id,
         * department,
         * salary,
         * ROW_NUMBER() OVER(PARTITION BY department ORDER BY salary ASC) as rank_within_department
         * FROM employees
         * )
         * SELECT
         * employee_id,
         * department,
         * salary
         * FROM EmployeeRanks
         * WHERE rank_within_department <= 5;
         */
        $select1 = new Select("EmployeeRanks");
        $select1->fields(["employee_id", "department", "salary"]);
        $select1->where()->set("rank_within_department", 5, Comparison::LESSER_EQUALS);
        $select2 = new Select("employees");
        $fields = $select2->fields();
        $fields->add("employee_id");
        $fields->add("department");
        $fields->add("salary");
        $fields->over("ROW_NUMBER()", new Over(new PartitionBy(["department"]), new OrderBy(["salary"])), "rank_within_department");
        $select1->with()->addSelect("EmployeeRanks", $select2);
        $normalized = $this->normalize($select1->toString());
        return new Result($normalized == "WITH EmployeeRanks AS ( SELECT employee_id, department, salary, ROW_NUMBER() OVER (PARTITION BY department ORDER BY salary ASC) AS rank_within_department FROM employees ) SELECT employee_id, department, salary FROM EmployeeRanks WHERE rank_within_department <= 5");
    }
        

    private function normalize(string $string): string
    {
        return  preg_replace("/\s+/", " ", str_replace(["\n", "\r"], " ", $string));
    }
}
