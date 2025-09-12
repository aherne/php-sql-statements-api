<?php
namespace Test\Lucinda\Query;

use Lucinda\Query\Update;
use Lucinda\UnitTest\Result;

class UpdateTest
{
    private $object;
    
    public function __construct()
    {
        $this->object = new Update("q");
    }

    public function set()
    {
        $this->object->set(["a"=>"s"]);
        return new Result(true); // tested by toString
    }
        

    public function where()
    {
        $this->object->where(["d"=>"f"]);
        return new Result(true); // tested by toString
    }
        

    public function toString()
    {
        return new Result($this->object->toString()=="UPDATE q\r\nSET a = s\r\nWHERE d = f");
    }
    public function with()
    {
        /**
         * WITH SeniorSales AS (
         * SELECT employee_id
         * FROM employees
         * WHERE department = 'Sales' AND hire_date < (CURRENT_DATE - INTERVAL '5 years')
         * )
         * UPDATE employees
         * SET salary = salary * 1.10
         * WHERE employee_id IN (SELECT employee_id FROM SeniorSales);
         */
        $update = new Update("employees");
        $update->set()->set("salary", "salary * 1.10");

        $select1 = new \Lucinda\Query\Select("employees");
        $select1->fields(["employee_id"]);
        $where1 = $select1->where();
        $where1->set("department", "'Sales'");
        $where1->set("hire_date", "(CURRENT_DATE - INTERVAL '5 years')", \Lucinda\Query\Operator\Comparison::LESSER);
        $update->with()->addSelect("SeniorSales", $select1);

        $select2 = new \Lucinda\Query\Select("SeniorSales");
        $select2->fields(["employee_id"]);

        $update->where()->setIn("employee_id", $select2);
        $normalized = preg_replace("/\s+/", " ", str_replace(["\r", "\n"], " ", $update->toString()));
        return new Result($normalized == "WITH SeniorSales AS ( SELECT employee_id FROM employees WHERE department = 'Sales' AND hire_date < (CURRENT_DATE - INTERVAL '5 years') ) UPDATE employees SET salary = salary * 1.10 WHERE employee_id IN (SELECT employee_id FROM SeniorSales)");
    }
        

}
