## Разборчик
Мы вводим программе строку, которая проверяется на принадлженость к алфавиту "abcdefghijklmnopqrstuvwxyz_?":
```cpp
    v11 = *(_BYTE *)std::__cxx11::basic_string<char,std::char_traits<char>,std::allocator<char>>::operator[](v15, i);
    if ( (v11 <= 96 || v11 > 122) && v11 != 95 && v11 != 63 )
    {
      std::operator<<<std::char_traits<char>>(
        &std::cout,
        "Invalid char. Your alphabet is 'abcdefghijklmnopqrstuvwxyz_?1379'\n");
      goto LABEL_15;
    }
```

Затем с каждым символом строки происходит вот это:
```cpp
  std::__cxx11::basic_string<char,std::char_traits<char>,std::allocator<char>>::basic_string<std::allocator<char>>(
    v16,
    "abcdefghijklmnopqrstuvwxyz_?",
    v14);
    // ...
    v4 = std::vector<std::vector<std::vector<int>>>::operator[](&matrix, i);
    v5 = std::__cxx11::basic_string<char,std::char_traits<char>,std::allocator<char>>::find(v16, (unsigned int)v11, 0LL);
    v6 = std::vector<std::vector<int>>::operator[](v4, v5);
    std::vector<int>::vector(v14, v6);
    for ( j = 0; j <= 4; ++j )
    {
      v7 = *(_DWORD *)std::vector<int>::operator[](v14, j);
      *(_DWORD *)std::vector<int>::operator[](&colors, 5 * i + j) = v7;
    }
    std::vector<int>::~vector(v14);
```

На питоне будет как-то так:
```python
st = input()
alp = 'abcdefghijklmnopqrstuvwxyz_?'
matrix = [[[]]] # vector<vector<vector<int>>>
colors = [] # vector<vector<int>>
for i in range(len(st)):
    for j in range(5):
        colors[5 * i + j] = matrix[i][alp.index(st[i])][j]
```
Но зачем используется colors?
```cpp
  dfs(0, 0);
  if ( result )
  {
    v8 = std::operator<<<std::char_traits<char>>(&std::cout, "YOUR FLAG CAONGRATS: CTF{");
    v9 = std::operator<<<char>(v8, v15);
    std::operator<<<std::char_traits<char>>(v9, "}\n");
  }
  else
  {
    std::operator<<<std::char_traits<char>>(&std::cout, "CHECK FAILED TRY AGAIN");
  }
```
Код `dfs`:
```cpp
unsigned __int64 __fastcall dfs(int a1, int a2)
{
  int v2; // ebx
  int v4; // [rsp+1Ch] [rbp-34h]
  __int64 v5; // [rsp+20h] [rbp-30h] BYREF
  __int64 v6; // [rsp+28h] [rbp-28h] BYREF
  __int64 v7; // [rsp+30h] [rbp-20h]
  unsigned __int64 v8; // [rsp+38h] [rbp-18h]

  __endbr64();
  v8 = __readfsqword(0x28u);
  v7 = std::vector<std::vector<int>>::operator[](&g, a1);
  v5 = std::vector<int>::begin(v7);
  v6 = std::vector<int>::end(v7);
  while ( (unsigned __int8)__gnu_cxx::operator!=<int *,std::vector<int>>(&v5, &v6) )
  {
    v4 = *(_DWORD *)__gnu_cxx::__normal_iterator<int *,std::vector<int>>::operator*(&v5);
    v2 = *(_DWORD *)std::vector<int>::operator[](&colors, v4);
    if ( v2 == *(_DWORD *)std::vector<int>::operator[](&colors, a1) )
      result = 0;
    dfs(v4, a2 ^ 1);
    __gnu_cxx::__normal_iterator<int *,std::vector<int>>::operator++(&v5);
  }
  return v8 - __readfsqword(0x28u);
}
```
На питоне осознать это будет проще:
```python
result =1
def dfs(a1,a2):
    v7= g[a1]
    for v4 in v7:
        if colors[v4]==colors[a1]:
            result=0
        dfs(v4,a2^1)
```
Имея на руках имена `dfs`, `colors` и код выше, можно осознать, что программа проверяет, что в графе `g` цвета смежных вершин разные. Проверка эта использует массив `colors`, который задается нашим вводом.
Проверок внутри `dfs`, что вершина уже была использована, нет, поэтому если мы встретим цикл внутри графа, то прога должна будет зависнуть. Но это не так, поэтому `g` - дерево.

Осталось вытащить из памяти `matrix` и граф `g`, после чего получить нужный `colors`. Сделаю это через GDB(с плагином gef), хотя можно жоско прочитать код `__static_initialization_and_destruction_0(int,int)` с idapython.
```
> b main
> r
> x/24x 0x44C3C0 # это адрес g в .bss
0x44c3c0 <g>:   0x0045fb10      0x00000000      0x00460bf0      0x00000000
0x44c3d0 <g+16>:        0x00460bf0      0x00000000      0x00000000      0x00000000
0x44c3e0 <colors>:      0x00000000      0x00000000      0x00000000      0x00000000
0x44c3f0 <colors+16>:   0x00000000      0x00000000      0x00000000      0x00000000
0x44c400 <matrix>:      0x00479a40      0x00000000      0x00479da0      0x00000000
0x44c410 <matrix+16>:   0x00479da0      0x00000000      0x00000000      0x00000000
```
Видим, что тут хранятся ссылки на начало и конец векторов. Напомню, что g - это `vector<vector<int>>`, colors - `vector<int>`, а matrix - `vector<vector<vector<int>>>`. Попробуем принтануть граф `g`:
```
>  x/20x 0x0045fb10
0x45fb10:       0x00460c00      0x00000000      0x00460c10      0x00000000
0x45fb20:       0x00460c10      0x00000000      0x00460c20      0x00000000
0x45fb30:       0x00460c30      0x00000000      0x00460c30      0x00000000
0x45fb40:       0x00460c40      0x00000000      0x00460c48      0x00000000
0x45fb50:       0x00460c48      0x00000000      0x00460c60      0x00000000
```
Опять ссылки на начало и концы вложенных векторов. Но тут уже можно заметить, что они разные по размеру, да еще и адрес конца вектора повторяется два раза: `0x00460c00     0x00460c10   0x00460c10`
Еще раз принтанем первый адрес:
```
>  x/40x 0x00460c00
0x460c00:       0x00000001      0x00000004      0x0000000d      0x0000000e
0x460c10:       0x00000000      0x00000000      0x00000021      0x00000000
0x460c20:       0x00000002      0x00000003      0x0000001d      0x00000027
0x460c30:       0x00000000      0x00000000      0x00000021      0x00000000
0x460c40:       0x00000014      0x00000038      0x00000000      0x00000000
0x460c50:       0x00000000      0x00000000      0x00000021      0x00000000
0x460c60:       0x00000005      0x00000015      0x00000033      0x00000043
0x460c70:       0x0000005a      0x00000094      0x00000021      0x00000000
0x460c80:       0x00000006      0x0000000a      0x00000012      0x0000001a
0x460c90:       0x00000000      0x00000000      0x00000021      0x00000000
```
Вот это уже больше похоже на номера вершин графа.
Итого скрипт гдб:
```python
import re
start = 0x45fb10
end =   0x00460bf0
# print(type(gdb.execute("x/3w 0x0045fb10", to_string=True)))
# print(help(gdb.execute))
txt = gdb.execute(f"x/{(end-start)//4}x {hex(start)}", to_string=True).split('\n')
g = []
arr = []

for i in range(len(txt)-1):
    st = txt[i].split()
    arr.extend([int(st[1],16), int(st[3],16)])

for i in range(0,len(arr),3):
    print(arr[i:i+2])
    if arr[i]==0:
        g.append([])
    else:
        addr,size = (arr[i],arr[i+2]-arr[i])
        res = [int(x[2:]) for x in re.findall("= \d+", gdb.execute(f"print/d *{hex(addr)}@{size//4}", to_string=True))]
        print(i,res)
        g.append(res)
print(g)
```
Запускается из gdb командой `python script.py`.
Аналогично вытаскивается `matrix`.
Теперь, покрасим дерево в два цвета, получив массив colors(очевидно, что у него может быть одна-две раскраски, не более):
```python
g = [[1, 4, 13, 14], [2, 3, 29, 39], [20, 56], [5, 21, 51, 67, 90, 148], [6, 10, 18, 26], [7, 9, 62, 127], [108, 157], [8, 11, 34], [97], [12, 69, 87], [15, 17, 24, 158], [25, 45], [16, 22, 27], [53, 129, 164], [117], [19], [37, 61, 114], [23, 28, 30, 54], [40, 71, 154], [33, 145], [32], [44, 76, 150], [92], [31, 38, 149], [47, 55], [36, 58, 70, 130], [41, 65, 169], [49, 113], [52, 122], [68], [73, 78, 99, 172, 174], [35, 91], [141], [102], [], [57, 137], [42], [165, 168], [43, 48], [95, 107], [46, 153], [], [103], [139], [79], [60, 151], [64], [166], [82], [50, 74, 86], [126, 177], [81], [75], [72], [142], [83], [63, 167], [146], [59, 143], [66, 77], [100], [171], [94], [], [138], [], [84, 88], [80, 140, 152], [89, 109], [125], [110], [128], [93, 135], [], [173], [], [], [104, 115, 147], [98], [96], [], [], [], [85], [123, 131], [112], [], [], [116], [], [105], [], [101], [170], [], [118, 120, 124], [156, 159], [], [], [], [], [161], [119], [], [106], [], [], [175], [], [133], [111], [136], [132], [], [], [], [], [121], [155, 163], [], [], [], [], [], [], [], [], [], [134], [], [], [], [], [], [], [], [], [], [144], [], [160], [], [], [], [], [], [], [], [], [162], [178], [], [], [], [], [], [], [176], [], [], [], [], [], [179], [], [], [], [], [], [], [], [], [], [], [], [], [], [], [], []]
colors = [0 for i in range(len(g))]
def dfs(a1,a2):
    v7= g[a1]
    colors[a1]=a2
    for v4 in v7:
        dfs(v4,a2^1)
dfs(0,0)
```
Теперь нужно развернуть этот алгос, если узнать, что матрица выглядит как ` [[[1, 1, 1, 0, 0], [0, 1, 1, 0, 1], [0, 1, 0, 0, 0], ...], ... ]`
```python
st = input()
alp = 'abcdefghijklmnopqrstuvwxyz_?'
matrix = [[[]]] # vector<vector<vector<int>>>
colors = [] # vector<vector<int>>
for i in range(len(st)):
    for j in range(5):
        colors[5 * i + j] = matrix[i][alp.index(st[i])][j]
```
То есть 5 элементов для `colors` берутся из `matrix[i][alp.index(st[i])]` и далее понятно чо делать:
```python
alp = 'abcdefghijklmnopqrstuvwxyz_?'
st = ''
for i in range(0,len(colors),5):
    st += alp[matrix[i//5].index(colors[i:i+5])]
print(st)
# i_snova_vse_zabyl_da_chto_so_mnoy???
```
ура:
```sh
/m/c/U/r/D/c/m/w/opianarium ∫ ./f0
i_snova_vse_zabyl_da_chto_so_mnoy???
YOUR FLAG CAONGRATS: CTF{i_snova_vse_zabyl_da_chto_so_mnoy???}
```