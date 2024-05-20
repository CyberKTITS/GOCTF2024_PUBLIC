import random,itertools

ll = 36*5
g = [[] for i in range(ll)]
for i in range(1, ll):
    g[random.randint(0, i - 1)].append(i)

used = [0 for i in range(len(g))]
colors = [0 for i in range(len(g))]
def dfs(v, c):
    used[v] = 1
    colors[v] = c
    for u in g[v]:
        if not used[u]:
            dfs(u, c ^ 1)
dfs(0, 0)

print(repr(colors).replace("[", "{").replace("]", "}"))
print(repr(g).replace("[", "{").replace("]", "}"))

alp = "abcdefghijklmnopqrstuvwxyz_?"
FLAG = 'i_snova_vse_zabyl_da_chto_so_mnoy???'
combs = list(map(list,itertools.product(range(2),repeat=5)))
matrix = []
for i in range(len(FLAG)):
    print(arr)
    matrix_el = combs.copy()
    random.shuffle(matrix_el)
    arr = colors[5*i:5*i+5]
    need_pos = alp.index(FLAG[i])
    real_pos = matrix_el.index(arr)
    matrix_el[real_pos], matrix_el[need_pos] = matrix_el[need_pos], matrix_el[real_pos]
    matrix.append(matrix_el)
