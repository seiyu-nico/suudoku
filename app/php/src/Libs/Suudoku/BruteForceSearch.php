<?php

namespace Seiyu\Libs\Suudoku;

/**
 * 総当り型
 */
class BruteForceSearch
{
    protected $rule;
    protected $block;
    protected $answer = [];
    protected $length;

    public function __construct($problem, $rule)
    {
        $this->answer = $problem;
        $this->rule = $rule;
        $this->block = sqrt(count($rule));
        $this->createBlock();
        $this->length = count($rule) - 1;
    }
    
    /**
     * 計算処理
     * 行: line
     * 列: row
     */
    public function Calculation($line_key, $row_key)
    {
        if ($this->length < $line_key) {
            // 行が9になったら終了
            return true;
        } elseif (0 != $this->answer[$line_key][$row_key]) {
            // 0以外なので次のマスに移動
            if ($this->nextCalculation($line_key, $row_key)) {
                return true;
            }
        } else {
            // 0の場合
            // 現在のマスで入れることのできる値を取得
            // 縦横ブロックのそれぞれを1~9との差分を取り、縦横ブロックの共通項を取得
            $diff = $this->searchPossibleValues($line_key, $row_key);

            foreach ($diff as $val) {

                $this->answer[$line_key][$row_key] = $val;
                $this->updateBlock($line_key, $row_key, $val, 0);

                if ($this->nextCalculation($line_key, $row_key)) {
                    return true;
                }
                
                $this->updateBlock($line_key, $row_key, 0, $this->answer[$line_key][$row_key]);
                $this->answer[$line_key][$row_key] = 0;
            }
            return false;
        }
    }

    public function nextCalculation($line_key, $row_key)
    {
        if ($this->length == $row_key) {
            // 列が8になったら行+1
            if ($this->Calculation($line_key + 1, 0)) {
                return true;
            }
        } else {
            // まだ列がある場合は列+1
            if ($this->Calculation($line_key, $row_key + 1)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * 縦横ブロックのそれぞれを1~9との差分を取り、縦横ブロックの共通項を取得
     */
    public function searchPossibleValues($line_key, $row_key)
    {
        // 横の差分
        $line_diff = array_diff($this->rule, $this->answer[$line_key]);
        
        // 縦の差分
        $row_array = array_column($this->answer, $row_key);
        $row_diff = array_diff($this->rule, $row_array);
        
        // ブロック
        $block_array = $this->getBlock($line_key, $row_key);
        $block_diff = array_diff($this->rule, $block_array);
        
        // 最終的な差分
        $diff = array_values(array_intersect($line_diff, $row_diff, $block_diff));

        return $diff;
    }

    /**
     * 現在の位置のブロックの値を取得する
     */
    protected function getBlock($line_key, $row_key) 
    {
        list($block_line_key, $block_row_key) = $this->getBlockKey($line_key, $row_key);
        return $this->block_array[$block_line_key][$block_row_key];
    }

    /**
     * 指定のマスの所属するブロックのキーを返却
     */
    protected function getBlockKey($line_key, $row_key)
    {
        $block_line_key = floor($line_key / $this->block);
        $block_row_key = floor($row_key / $this->block);

        return [$block_line_key, $block_row_key];
    }

    /**
     * 毎回検索するのが大変なので各ブロックごとの配列を最初に作成しておく
     */
    protected function createBlock()
    {
        foreach ($this->answer as $line_key => $line) {
            foreach ($line as $row_key => $val) {
                // キーがblockで割り切れるときのみ実行
                if (0 == $line_key % $this->block && 0 == $row_key % $this->block) {
                    list($block_line_key, $block_row_key) = $this->getBlockKey($line_key, $row_key);
                    $line_start = $block_line_key * $this->block;
                    $row_start = $block_row_key * $this->block;
                    $line_end = $line_start + $this->block;
                    $row_end = $row_start + $this->block;
                    $block_array = [];
                    for ($i = $line_start; $i < $line_end; ++$i) {
                        for ($j = $row_start; $j < $row_end; ++$j) {
                            $block_array[] = $this->answer[$i][$j];
                        }
                    }
                    $this->block_array[$block_line_key][$block_row_key] = $block_array;
                }
            }
        }
    }

    /**
     * 回答が決まったブロックの値を更新
     * 0の要素を更新
     * この配列は順序は関係なく3*3のブロックに存在する数値がわかればいい
     */
    public function updateBlock($line_key, $row_key, $answer_num, $num = 0)
    {
        list($block_line_key, $block_row_key) = $this->getBlockKey($line_key, $row_key);
        $search_key = array_search($num, $this->block_array[$block_line_key][$block_row_key]);
        $this->block_array[$block_line_key][$block_row_key][$search_key] = $answer_num;
    }

    /**
     * 要素のチェック
     * 0がある場合はtrueを返す
     * 全てに数値が入った場合はfalseを返す
     */
    public function zeroExists()
    {
        $valid = false;
        foreach ($this->answer as $line) {
            if(false !== array_search(0, $line)) {
                $valid = true;
            }
        }
        return $valid;
    }

    public function getAnswer()
    {
        return $this->answer;
    }

    public function view()
    {
        foreach ($this->answer as $line_key => $line) {
            if (0 == $line_key % $this->block) {
                echo '|-----------------------|' . PHP_EOL;
            }
            foreach ($line as $row_key => $val) {
                if (0 == $row_key % $this->block) {
                    echo '| ';
                }

                echo $val;
                if ($this->length != $row_key) {
                    echo ' ';
                } elseif ($this->length == $row_key) {
                    echo ' |';
                }
            }

            if ($this->length == $line_key) {
                echo PHP_EOL . '|-----------------------|' . PHP_EOL;

            }
            echo ' ' . PHP_EOL;
        }
    }
}