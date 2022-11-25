#ifndef logger
#define logger

#include <stdio.h>
#include <string.h>
#include <stdbool.h>
/**
 * The path of the file to save data in.
 */
#define LOG_FILE_PATH "logs.txt"

/**
 * The max size of a log message.
 */
#define MAX_MESSAGE_SIZE 200

/**
 * The level to select, only messages with an equal or higher level will be saved and/or printed.
 */
#define LEVEL DEBUG

/**
 * An option to print data on screen.
 */
#define PRINT_DATA true

/**
 * An option to save data in a file.
 */
#define SAVE_DATA true

typedef enum {DEBUG, INFO, WARNING, ERROR} levels;

/**
 * This function save a debug message into the log file. The message will only be saved if the selected level is equal or lower than debug.
 * 
 * @param message the message to save/print.
 */
void logDebug(char *message);

/**
 * This function save an information message into the log file. The message will only be saved if the selected level is equal or lower than information.
 * 
 * @param message the message to save/print.
 */
void logInfo(char *message);

/**
 * This function save a warning message into the log file. The message will only be saved if the selected level is equal or lower than warning.
 * 
 * @param message the message to save/print.
 */
void logWarning(char *message);

/**
 * This function save an error message into the log file. The message will only be saved if the selected level is equal or lower than error.
 * 
 * @param message the message to save/print.
 */
void logError(char *message);

#endif