<?php
namespace Lucinda\Query;

require_once(dirname(dirname(__DIR__))."/src/Insert.php");

class MySQLReplace extends Insert
{
    public function toString() {
        if(!$this->columns) throw new Exception("running columns() method is mandatory");
        if(!$this->rows) throw new Exception("running values() is mandatory");

        $output = "REPLACE INTO ".$this->table." (".$this->columns->toString().") VALUES "."\r\n";
        foreach($this->rows as $row) {
            $output.=$row->toString().", ";
        }
        return substr($output,0,-2);
    }
}