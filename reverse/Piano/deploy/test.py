def dec(enc, guid):
    data = enc.split("|")
    cnt = len(data)
    old = 0
    fib_old = 0
    fib = guid
    numbs = []
    
    for i in range(cnt):
        fib_old = fib
        fib = fib + old
        s = int(data[i])
        numbs.append(int((s - fib) / (1 + fib)))
        old = fib_old
    str_numbs = [str(n) for n in numbs]
    return '|'.join(str_numbs)

print(dec("64|49|184|121|484|627|1011|1635|1321|4275|3457|11187|22624|36604|59224|38329|155044|100345|405904|525411|850131|1375539|1112833|3601203|2913433|9428067|19068664",12))