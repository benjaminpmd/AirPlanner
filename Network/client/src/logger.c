#include "include/logger.h"
#include <time.h>

#define MAX_MESSAGE_SIZE 200

void logData(char *level, char *message) {
    char data[MAX_MESSAGE_SIZE], timeString[30];
    time_t timestamp = time(NULL);
    struct tm *ptime = localtime(&timestamp);
    strftime(timeString, 30, "%d/%m/%Y %H:%M:%S", ptime);

    snprintf(data, MAX_MESSAGE_SIZE, "%s - %s - Client: %s\n", timeString, level,  message);
    FILE *fptr;

    fptr = fopen(FILE_NAME, "a");
    fputs(data, fptr);
    fclose(fptr);
}