#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <locale.h>

char form_dict[128];

void form_dict_init() {
    for (int i = 0; i < 128; i++) {
        form_dict[i] = (char)i;
    }
}

int* encode_val(char* word) {
    int len = strlen(word);
    int* list_code = (int*)malloc(len * sizeof(int));
    int iter = 0;
    for (int w = 0; w < len; w++) {
        for (int value = 0; value < 128; value++) {
            if (word[w] == form_dict[value]) {
                list_code[iter++] = value;
                break;
            }
        }
    }
    return list_code;
}

int** comparator(int* value, int* key, int len_value, int len_key) {
    int** dic = (int**)malloc(len_value * sizeof(int*));
    int full = 0, iter = 0;
    for (int i = 0; i < len_value; i++) {
        dic[i] = (int*)malloc(2 * sizeof(int));
        dic[i][0] = value[i];
        dic[i][1] = key[iter++];
        full++;
        if (iter >= len_key) {
            iter = 0;
        }
    }
    return dic;
}

char* decode_val(int* list_in, int len) {
    char* list_code = (char*)malloc(len * sizeof(char));
    int iter = 0;
    for (int i = 0; i < len; i++) {
        list_code[iter++] = form_dict[list_in[i]];
    }
    list_code[len] = '\0';
    return list_code;
}

int* full_decode(int* value, int* key, int len_value, int len_key) {
    int** dic = comparator(value, key, len_value, len_key);
    int* lis = (int*)malloc(len_value * sizeof(int));
    int len_dict = 128;
    for (int v = 0; v < len_value; v++) {
        lis[v] = (dic[v][0] - dic[v][1] + len_dict) % len_dict;
    }
    for (int i = 0; i < len_value; i++) {
        free(dic[i]);
    }
    free(dic);
    return lis;
}


int give_flag(){

    char* word = "2:.?1fOTOJdZ`JXLVPJ_SPJ[L_NS*h";
    char* key = "kkkkkkkk";

    form_dict_init();

    int* key_encoded = encode_val(key);
    int* value_encoded = encode_val(word);
    int len_key = strlen(key);
    int len_value = strlen(word);


    int* decoded = full_decode(value_encoded, key_encoded, len_value, len_key);
    printf("%s", decode_val(decoded, len_value));

    free(key_encoded);
    free(value_encoded);
    free(decoded);

    return 0;

}


int main() {
    char *locale = setlocale(LC_ALL, "");

    puts("Ты не получить данные просто так!");

    int i = 0;
    if(i)
    {
        give_flag();
    }
    return 0;
}