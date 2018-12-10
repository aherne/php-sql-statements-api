<?php
namespace Lucinda\Query;

/**
 * Encapsulates SQL VALUES clause to be used by INSERT statements
 */
class Row implements Stringable {
    protected $contents = array();

    /**
     * @param string[] $contents Sets list of values to write in columns directly
     */
    public function __construct($contents = array()) {
        $this->contents = $contents;
    }

    /**
     * Compiles SQL clause based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     */
	public function toString() {
		$output = "";		
		foreach($this->contents as $mixValue) {
			$output .= $mixValue.", ";
		}
		return "(".substr($output,0,-2).")";
	}
}