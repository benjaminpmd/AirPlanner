#include "include/logger.h"
#include <time.h>

void logData(char *level, char *message) {
    char *message, *timeString;
    time_t now;
    time(&now);
    strftime(timeString, 26, "%Y-%m-%d %H:%M:%S", now);

    sprintf(message ,"%s - %s - CLient: %s", timeString, level,  message);
    FILE *fptr;

    fptr = fopen(FILE_NAME, "a");
}