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