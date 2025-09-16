<?php

namespace Test\Lucinda\Query;

use Lucinda\Query\InsertSelect;
use Lucinda\Query\Select;
use Lucinda\UnitTest\Result;

class InsertSelectTest
{
    private $object;

    public function __construct()
    {
        $this->object = new InsertSelect("x");
    }

    public function columns()
    {
        $this->object->columns(["a", "b"]);
        return new Result(true); // tested by toString
    }


    public function select()
    {
        $select = new Select("y");
        $select->fields(["c", "d"]);
        $this->object->select($select);
        return new Result(true); // tested by toString
    }

    public function toString()
    {
        $query = str_replace("\n", " ", str_replace("\r", "", $this->object->__toString()));
        return new Result($query=="INSERT INTO x (a, b) SELECT c, d FROM y");
    }

    public function __toString(): string
    {
        return "OK";
    }

    public function with()
    {
        /**
         * WITH MonthlySales AS (
         * SELECT
         * strftime('%Y-%m', sale_date) AS sales_month,
         * SUM(sale_amount) AS total_monthly_sales
         * FROM daily_sales
         * GROUP BY sales_month
         * )
         * INSERT INTO monthly_summary (month, total_sales)
         * SELECT sales_month, total_monthly_sales
         * FROM MonthlySales;
         */
        $insert = new InsertSelect("monthly_summary");
        $insert->columns(["month", "total_sales"]);
        $select1 = new Select("MonthlySales");
        $select1->fields(["sales_month", "total_monthly_sales"]);
        $insert->select($select1);
        $select2 = new Select("daily_sales");
        $fields = $select2->fields();
        $fields->add("strftime('%Y-%m', sale_date)", "sales_month");
        $fields->add("SUM(sale_amount)", "total_monthly_sales");
        $select2->groupBy(["sales_month"]);
        $insert->with()->addSelect("MonthlySales", $select2);
        $normalized = preg_replace("/\s+/", " ", str_replace(["\n", "\r"], " ", $insert));
        return new Result($normalized == "WITH MonthlySales AS ( SELECT strftime('%Y-%m', sale_date) AS sales_month, SUM(sale_amount) AS total_monthly_sales FROM daily_sales GROUP BY sales_month ) INSERT INTO monthly_summary (month, total_sales) SELECT sales_month, total_monthly_sales FROM MonthlySales");
    }

}
