<?php
namespace Test\Lucinda\Query;
    
use Lucinda\Query\Select;
use Lucinda\Query\Validator;
use Lucinda\UnitTest\Result;

class ValidatorTest
{
    private $validator;


    public function __construct()
    {
        $this->validator = new Validator();
    }

    public function validateTable()
    {
        $output = [];
        $output[] = new Result($this->validator->validateTable("asd") == "asd", "primitive");

        $select = new Select("fgh");
        $select->fields()->add("id");
        $output[] = new Result($this->normalize($this->validator->validateTable($select, "k")) == "( SELECT id FROM fgh )", "select");
        return $output;
    }
        

    public function validateSelectField()
    {
        $output = [];
        $output[] = new Result($this->validator->validateSelectField("asd") == "asd", "primitive");

        $select = new Select("fgh");
        $select->fields()->add("id");
        $output[] = new Result($this->normalize($this->validator->validateSelectField($select, "k")->toString()) == "(SELECT id FROM fgh) AS k", "select");
        return $output;
    }
        

    public function validateOrderByField()
    {
        $output = [];
        $output[] = new Result($this->validator->validateOrderByField("asd") == "asd", "primitive");

        $select = new Select("fgh");
        $select->fields()->add("id");
        $output[] = new Result($this->normalize($this->validator->validateOrderByField($select)) == "(SELECT id FROM fgh)", "select");
        return $output;
    }
        

    public function validateCondition()
    {
        $output = [];
        $output[] = new Result($this->validator->validateCondition("asd") == "asd", "primitive");

        $select = new Select("fgh");
        $select->fields()->add("id");
        $output[] = new Result($this->normalize($this->validator->validateCondition($select)) == "(SELECT id FROM fgh)", "select");
        return $output;
    }




    private function normalize(string $string): string
    {
        return  preg_replace("/\s+/", " ", str_replace(["\n", "\r"], " ", $string));
    }
}
