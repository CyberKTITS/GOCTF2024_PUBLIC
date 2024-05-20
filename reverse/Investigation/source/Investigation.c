#include <stdio.h>
#include <string.h>
#include <unistd.h>

int main()
{
    printf("Секретные данные подлежат шифрованию\nно если вы обладаете серетной информацией впшите её сюда\n");
    
    int mass[28] = {75, 67, 79, 88, 74, 119, 65, 56, 98, 83, 56, 98, 104, 83, 107, 63, 120, 96, 83, 101, 120, 57, 83, 105, 56, 127, 117, 113};

    char line[1024];
    scanf("%[^\n]", line);

    int sizeMass = sizeof(mass) / sizeof(mass[0]);

    for(int i = 0; i < sizeMass; i++)
    {
        int userChar = 12 ^ line[i];
        int flagChar = mass[i];
        
        if(userChar != flagChar)
        {
           print_and_sleep("Система удалиться через 5");
           print_and_sleep("4");
           print_and_sleep("3");
           print_and_sleep("2");
           print_and_sleep("1");
           printf("шутка!");
           return 0;
        }
    }
    
     printf("Вы обладатель ценной информации, не разглашайте её");
    
    return 0;
}


void print_and_sleep(char message[])
{
    printf("%s\n", message);
    sleep(1);
}