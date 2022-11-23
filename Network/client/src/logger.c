#include "include/logger.h"
#include <time.h>

void logTrace(char *level, char *message) {
    time_t now;
    time(&now);
    printf("%s - %s - CLient: %s", level,  message);
}