# DataTheft
## Сложность
Medium

## Описание
Однажды, к вам в руки попал таинственный файл. Этот файл, как будто бы написанный самим Богом-программистом, был ключом к великой тайне. Но, как это часто бывает в мире технологий, не всё идёт гладко. Программа отказывалась раскрыть свою тайну и выдавать нужную информацию.

**!Участникам нужно выдать содержимое папки public!**

## Решение:
Для решения задачи нужно запустить IDA, найти метод main

![Main method](images/main.png)

После вы могли заметить условие которое нужно запатчить для этого выберите Patch programm/Assemble

![Patch assemble](images/patch_assemble.png)

Поменяйте в условии jz на jnz

![Assemble Instruction](images/assemble_instruction.png)

И примените изменения

![Apply patches](images/apply_patches.png)

## Флаг
GOCTF{did_you_make_the_patch?}
