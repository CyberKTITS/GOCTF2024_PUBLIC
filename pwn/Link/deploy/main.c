#include <stdlib.h>
#include <string.h>
#include <stdio.h>
#include <unistd.h>
#include <sys/mman.h>

void menu();
int make_wish();
int change_wish();
int remove_wish();
int remember_wish();

char **wishes;
int num_wish;

int main(){
  setvbuf(stdout, NULL, _IONBF, 0);
  setvbuf(stdin, NULL, _IONBF, 0);
  setvbuf(stderr, NULL, _IONBF, 0);
  puts("Hello traveller!");
  puts("You can ask the goddess Hylia for help.");
  char input[0xa];
  num_wish = 0;
  wishes =  mmap((void*)0x424000, 0x1000, PROT_WRITE|PROT_READ, MAP_FIXED | MAP_ANON | MAP_PRIVATE, 0, 0);
  int choice;
  int result;
  do {
    menu();
    char* status = fgets(input, 10,stdin);
    if (status == NULL){
      return 0;
    }
    choice = atoi(input);
    switch(choice){
      case 1:
        result = make_wish();
        break;
      case 2:
        result = change_wish();
        break;
      case 3:
        result = remove_wish();
        break;
	  case 4:
	  	result = remember_wish();
		break;
      case 5:
      	exit(0);
      	break;
    }
    if (!result)
    	puts("The goddess has heard you!");
    else
    	puts("The goddess did not hear you...");
    fflush(stdout);
  } while(1);
}

int make_wish(){
	puts("What is the length of your wish?");
	char input[0xff];
	char *status = fgets(input, 0xa, stdin);
	if (status == NULL)
		return 1;
	
	long size = atoll(input);

	char *ptr = malloc(size);
	if (ptr == NULL)
		return 1;
	puts("What is your wish?");
	
	int stat = fread(ptr, 1, size, stdin);
	
	if (stat == 0)
		return 1;
	num_wish++;
	wishes[num_wish] = ptr;
	printf("Your wish №%d has been heard.\n", num_wish);
	return 0;
}

int change_wish(){
	puts("What desire do you want to change?");
	char input[0xff];
	char *status = fgets(input, 0xa, stdin);
	if (status == NULL)
		return 1;
	
	int index = atoi(input);

	if(wishes[index] == NULL){
		return 1;
	}

	puts("What is the length of your wish?");
	
	status = fgets(input, 0xa, stdin);
	if (status == NULL)
		return 1;
	
	int size = atoi(input);
	puts("What is your wish?");

	int stat = fread(wishes[index], sizeof(char), size, stdin);
	
	if (stat == 0)
		return 1;
	return 0;
}

int remove_wish(){
	puts("Which wish do you want to forget?");
	char input[0xff];
	char *status = fgets(input, 0xa, stdin);
	if (status == NULL)
		return 1;

	int index = atoi(input);
	
	if(wishes[index] == NULL){
		return 1;
	}
	free(wishes[index]);
	wishes[index] = NULL;
		
	return 0;
}

int remember_wish(){
	puts("What wish do you want to remember?");
	char input[0xff];
	char *status = fgets(input, 0xa, stdin);
	if (status == NULL)
		return 1;

	int index = atoi(input);
	
	if(wishes[index] == NULL){
		return 1;
	}

	fputs(wishes[index], stdout);

	return 0;
}

void menu(){
  puts("1. Leave a wish to the goddess");
  puts("2. Change your wish");
  puts("3. Ask the goddess to forget about your wish");
  puts("4. Ask the goddess to repeat your wish");
  puts("5. Get away from the statue");
}
