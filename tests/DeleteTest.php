<?php
namespace Test\Lucinda\Query;

use Lucinda\Query\Delete;
use Lucinda\Query\Operator\Comparison;
use Lucinda\Query\Select;
use Lucinda\UnitTest\Result;

class DeleteTest
{
    private $object;
    
    public function __construct()
    {
        $this->object = new Delete("x");
    }

    public function where()
    {
        $this->object->where(["a"=>"b", "c"=>"d"]);
        return new Result(true); // tested by toString
    }
        

    public function toString()
    {
        return new Result($this->object->toString()=="DELETE FROM x\r\nWHERE a = b AND c = d");
    }
    public function with()
    {
        $select1 = new Select("customers");
        $select1->fields(["customer_id"]);
        $select1->where()->set("last_order_date", "(CURRENT_DATE - INTERVAL '1 year')", Comparison::LESSER);

        $select2 = new Select("InactiveCustomers");
        $select2->fields(["customer_id"]);

        $delete = new Delete("orders");
        $delete->with()->addSelect("InactiveCustomers", $select1);
        $delete->where()->setIn("customer_id", $select2);
        $normalized = preg_replace("/\s+/", " ", str_replace(["\r", "\n"], " ", $delete->toString()));
        return new Result($normalized == "WITH InactiveCustomers AS ( SELECT customer_id FROM customers WHERE last_order_date < (CURRENT_DATE - INTERVAL '1 year') ) DELETE FROM orders WHERE customer_id IN (SELECT customer_id FROM InactiveCustomers)");
    }

}
