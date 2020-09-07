import csv
from pprint import pprint
import time

from Libs.Suudoku.BruteForceSearch import BruteForceSearch
from Libs import functions

# 解きたい問題のファイル名
file_name = '001.csv'

# 問題取得
problem = functions.getProblem(file_name)
rule = list(range(1, 10))

BFS = BruteForceSearch(problem, rule)
print('問題')
BFS.view()

time_start = time.time()
BFS.Calculation(0, 0)
time = time.time() - time_start

if False == BFS.zeroExists():
    print('答え')
    BFS.view()
    print('計算時間')
    print(time)

else:
    print('解けませんでした')

