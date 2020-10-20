<?php

require_once(dirname(__FILE__) . '/bootstrap/autoload.php');

use Seiyu\Libs\Suudoku\BruteForceSearch;

// 解きたい問題のファイル名を設定
$file_name = '005.csv';

// 問題取得
$problem = getProblem($file_name);
$rule = range(1, 9);

echo '問題' . PHP_EOL;

$BFS = new BruteForceSearch($problem, $rule);
$BFS->view();

$time_start = microtime(true);
$BFS->Calculation(0, 0);
$time = microtime(true) - $time_start;

if (false == $BFS->zeroExists()) {
    echo '答え' . PHP_EOL;
    $BFS->view();
    echo '計算時間' . PHP_EOL;
    echo $time . '秒' . PHP_EOL;
} else {
    echo '解けませんでした。' . PHP_EOL;
}
