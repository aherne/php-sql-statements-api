<?php
namespace Test\Lucinda\Query;

use Lucinda\Query\Select;
use Lucinda\Query\Validator;
use Lucinda\UnitTest\Validator\Strings;

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
        $output[] = (new Strings($this->validator->validateTable("asd")))->assertEquals("asd", "primitive");

        $select = new Select("fgh");
        $select->fields()->add("id");
        $output[] = (new Strings($this->normalize($this->validator->validateTable($select, "k"))))->assertEquals("( SELECT id FROM fgh )", "select");
        return $output;
    }


    public function validateSelectField()
    {
        $output = [];
        $output[] = (new Strings($this->validator->validateSelectField("asd")))->assertEquals("asd", "primitive");

        $select = new Select("fgh");
        $select->fields()->add("id");
        $output[] = (new Strings($this->normalize($this->validator->validateSelectField($select, "k"))))->assertEquals("(SELECT id FROM fgh) AS k", "select");
        return $output;
    }


    public function validateOrderByField()
    {
        $output = [];
        $output[] = (new Strings($this->validator->validateOrderByField("asd")))->assertEquals("asd", "primitive");

        $select = new Select("fgh");
        $select->fields()->add("id");
        $output[] = (new Strings($this->normalize($this->validator->validateOrderByField($select))))->assertEquals("(SELECT id FROM fgh)", "select");
        return $output;
    }


    public function validateCondition()
    {
        $output = [];
        $output[] = (new Strings($this->validator->validateCondition("asd")))->assertEquals("asd", "primitive");

        $select = new Select("fgh");
        $select->fields()->add("id");
        $output[] = (new Strings($this->normalize($this->validator->validateCondition($select))))->assertEquals("(SELECT id FROM fgh)", "select");
        return $output;
    }




    private function normalize(string $string): string
    {
        return  preg_replace("/\s+/", " ", str_replace(["\n", "\r"], " ", $string));
    }
}
