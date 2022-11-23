#ifndef logger
#define logger

#include <stdio.h>
#include <string.h>

#define FILE_NAME "logs.txt"
# define LEVELS {debug, info, warning, error}

void logTrace(char *level, char *message);

#endif