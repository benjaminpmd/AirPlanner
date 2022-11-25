#include "include/logger.h"
#include <time.h>

void logDebug(char *message) {
    // check if the level select is lower than the level of the function
    if (LEVEL <= DEBUG) {
        // if the level is lower create the variables to use
        char data[MAX_MESSAGE_SIZE], timeString[30];
        // extract current time
        time_t timestamp = time(NULL);
        struct tm *ptime = localtime(&timestamp);
        
        // format current time
        strftime(timeString, 30, "%d/%m/%Y %H:%M:%S", ptime);
        
        // format the data to save
        snprintf(data, MAX_MESSAGE_SIZE, "%s - DEBUG - Client: %s\n", timeString,  message);
        
        // if the option to print datas is enable, print data
        if (PRINT_DATA) {
            printf("%s", data);
        }

        if (SAVE_DATA) {
            // open the file to log the information
            FILE *fptr = fopen(LOG_FILE_PATH, "a");
            // append the data
            fputs(data, fptr);
            // close the file
            fclose(fptr);
        }
    }
}

void logInfo(char *message) {
    if (LEVEL <= INFO) {
        char data[MAX_MESSAGE_SIZE], timeString[30];
        time_t timestamp = time(NULL);
        struct tm *ptime = localtime(&timestamp);
        strftime(timeString, 30, "%d/%m/%Y %H:%M:%S", ptime);

        snprintf(data, MAX_MESSAGE_SIZE, "%s - INFO - Client: %s\n", timeString,  message);
        FILE *fptr;

        fptr = fopen(LOG_FILE_PATH, "a");
        fputs(data, fptr);
        fclose(fptr);
    }
}


void logWarning(char *message) {
    if (LEVEL <= WARNING) {
        char data[MAX_MESSAGE_SIZE], timeString[30];
        time_t timestamp = time(NULL);
        struct tm *ptime = localtime(&timestamp);
        strftime(timeString, 30, "%d/%m/%Y %H:%M:%S", ptime);

        snprintf(data, MAX_MESSAGE_SIZE, "%s - WARNING - Client: %s\n", timeString,  message);
        FILE *fptr;

        fptr = fopen(LOG_FILE_PATH, "a");
        fputs(data, fptr);
        fclose(fptr);
    }
}


void logError(char *message) {
    if (LEVEL <= ERROR) {
        char data[MAX_MESSAGE_SIZE], timeString[30];
        time_t timestamp = time(NULL);
        struct tm *ptime = localtime(&timestamp);
        strftime(timeString, 30, "%d/%m/%Y %H:%M:%S", ptime);

        snprintf(data, MAX_MESSAGE_SIZE, "%s - ERROR - Client: %s\n", timeString,  message);
        FILE *fptr;

        fptr = fopen(LOG_FILE_PATH, "a");
        fputs(data, fptr);
        fclose(fptr);
    }
}

