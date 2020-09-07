<?php

require_once(dirname(__FILE__) . '/../../vendor/autoload.php');


/**
 * 問題を取得する
 */
function getProblem($file_name)
{
    $problem = [];
    $f = fopen('./problem/' . $file_name, "r");
    while($line = fgetcsv($f)){
        $problem[] = $line;
    }
    fclose($f);
    return $problem;
}