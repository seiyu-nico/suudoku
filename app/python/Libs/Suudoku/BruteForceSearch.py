import math
from pprint import pprint

# 総当り
class BruteForceSearch:

    def __init__(self, problem, rule):
        self.answer = problem
        self.rule = rule
        self.block = int(math.sqrt(len(rule)))
        self.block_list =  [[0 for i in range(self.block)] for j in range(self.block)]
        self.createBlock()
        self.length = len(rule) - 1


    def Calculation(self, line_key, row_key):
        if self.length < line_key:
            # 行が9になったら終了
            return True

        elif 0 != self.answer[line_key][row_key]:
            # ようそが0以外なので次の行に移動
            if self.nextCalculation(line_key, row_key):
                return True

        else:
            # マスが0の場合
            diff = self.searchPossibleValues(line_key, row_key)

            for num in diff:
                # 一度仮の値を代入
                self.answer[line_key][row_key] = num
                # ブロックのアップデート
                self.updateBlock(line_key, row_key, num, 0)
                
                # 次のマスに移動
                if self.nextCalculation(line_key, row_key):
                    return True
                
                # ここまで来たら仮に代入した値を戻す
                self.answer[line_key][row_key] = 0
                # ブロックのアップデート
                self.updateBlock(line_key, row_key, 0, num)

            return False
    

    def nextCalculation(self, line_key, row_key):
        """
        次のマスに移動する
        """
        if self.length == row_key:
            # 列が8になら次の行に移動
            if self.Calculation(line_key + 1, 0):
                return True

        else:
            # そうでなければ次の列のマスに移動
            if self.Calculation(line_key, row_key + 1):
                return True
    
        return False


    def searchPossibleValues(self, line_key, row_key):
        """
        縦横ブロックと1~9で差分を取り、指定されたマスに入りうる数値を配列で返却
        """
        # 横の差分を取得
        line_diff = set(self.rule) - set(self.answer[line_key])

        # 縦の差分取得
        row_list = list()
        for line in self.answer:
            row_list.append(line[row_key])
        row_diff = set(self.rule) - set(row_list)

        # ブロックの差分取得
        block_list = self.getBlock(line_key, row_key)
        block_diff = set(self.rule) - set(block_list)

        # 最終的な差分取得
        diff = list(set(line_diff) & set(row_diff) & set(block_diff))
        diff.sort()

        return diff

    def createBlock(self):
        """
        毎回3*3の配列を計算するのは非効率なので最初に作成する
        値が確定したら配列を更新することで整合性を保つ
        """
        for line_key, line in enumerate(self.answer):
            for row_key, row in enumerate(line):
                if 0 == line_key % self.block and 0 == row_key % self.block:
                    block_line_key, block_row_key = self.getBlockKey(line_key, row_key)
                    line_start = block_line_key * self.block
                    row_start = block_row_key * self.block
                    line_end = line_start + self.block
                    row_end = row_start + self.block
                    block = list()
                    for i in range(int(line_start), int(line_end)):
                        for j in range(int(row_start), int(row_end)):
                            block.append(self.answer[i][j])
                    
                    self.block_list[int(block_line_key)][int(block_row_key)] = block

    def updateBlock(self, line_key, row_key, answer_num, num):
        """
        3*3の配列の更新
        """
        block_line_key, block_row_key = self.getBlockKey(line_key, row_key)
        search_key = self.block_list[block_line_key][block_row_key].index(num) 
        self.block_list[block_line_key][block_row_key][search_key] = answer_num

    def getBlock(self, line_key, row_key):
        """
        指定されたマスが属するブロックを返す
        """
        block_line_key, block_row_key = self.getBlockKey(line_key, row_key)
        
        return self.block_list[block_line_key][block_row_key]

    def getBlockKey(self, line_key, row_key):
        block_line_key = int(line_key / self.block)
        block_row_key = int(row_key / self.block)

        return block_line_key, block_row_key

    def zeroExists(self):
        """
        要素に0が存在するかどうか(終了判定)
        0が存在する場合はTrue
        0が存在しない場合はFalseが変える
        """
        for line in self.answer:
            if 0 in line:
                return True
        
        return False

    def getAnswer(self):
        return self.answer

    def view(self):
        for line_key, line in enumerate(self.answer):
            if 0 == line_key % self.block:
                print('|-----------------------|')

            for row_key, row in enumerate(line):
                if 0 == row_key % self.block:
                    print('| ', end='')
                
                print(row, end='')

                if self.length != row_key:
                    print(' ', end='')
                elif self.length == row_key:
                    print(' |')
                
            if self.length == line_key:
                print('|-----------------------|')
            
