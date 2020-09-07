import csv

def getProblem(file_name):
    problem = list()
    with open('./problem/' + file_name) as f:
        reader = csv.reader(f)
        for row in reader:
            problem.append(list(map(int, row)))

    return problem