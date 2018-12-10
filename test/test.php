<?php
function test($left, $right) {
    $line = debug_backtrace()[0]["line"];
    echo $line.": ".($left==$right?"OK":"FAILED [".$left."]")."\n";
}