#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <sys/mman.h>
#include <string.h>
int main(){
  setvbuf(stdout, NULL, _IONBF, 0);
	setvbuf(stdin, NULL, _IONBF, 0);
	setvbuf(stderr, NULL, _IONBF, 0);
  char buf[100]={0};
  void(*shellobomber)(void) = (void (*)())mmap(NULL,0x1000,PROT_EXEC|PROT_WRITE|PROT_READ,MAP_PRIVATE | MAP_ANONYMOUS,-1,0);
  for(int i=0;i<0x1000/8;i++){
    puts("Gimme 4 bytes:\n>>");
    fgets(buf,50,stdin);
    if(!strcmp(buf,"stop\n"))
      break;
    void* pointer = (void*)((long)shellobomber+i*8);
    memcpy(pointer,buf,4);
  }
  (*shellobomber)();
}
