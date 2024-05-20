#include <stdio.h>
#include <stdlib.h>

char buff[20];
int buff2;

void setup(){
        setvbuf(stdin, buff, _IONBF, 0);
        setvbuf(stdout, buff, _IONBF, 0);
        setvbuf(stderr, buff, _IONBF, 0);
}

void flag_handler(){
        FILE *f = fopen("flag.txt","r");
        if (f == NULL) {
        printf("Help me find the flag");
        exit(0);
  }
}

void buffer(){
        buff2 = 0;
        printf("how to find the flag?...\n");
        printf("Input: ");
        fflush(stdout);
        gets(buff); 
        if (buff2 > 4347715) {
                printf("OH NO!\n\n");
        } else if (buff2 == 4347715){
                printf("and here it is - the flag\n");
                system("cat flag.txt");
        } else {
                printf("which is little\n\n");
        }
        printf("\nOutput: %s, Value: %d \n", buff, buff2);
}

int main(){
        flag_handler();
        setup();
        buffer();
}