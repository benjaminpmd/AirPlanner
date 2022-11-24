#ifndef client
#define client

// include librairie for windows if the client is running on windows
#ifdef _WIN32
#include <Winsock2.h>
#endif

// include librairies
#include <sys/socket.h>
#include <unistd.h>
#include <netdb.h>
#include <arpa/inet.h>
#include <stdio.h>
#include <stdlib.h>
#include <pthread.h>
#include <string.h>
#include <stdbool.h>
#include <stdarg.h>
#include <math.h>

/**
 * Address of the client.
 */
#define ADDRESS "127.0.0.1"
/**
 * Port on which the server is running.
 */
#define PORT 1025
/**
 * Max length of output/input message.
 */
#define MAX_MESSAGE_LENGTH 700

#define REGISTRATION "F-BXNX"


/**
 * Function that converts an int to a str for formatting the request to the server.
 * 
 * @param x the integer to convert.
 * @param o the output.
*/
void intToStr(int x, char *o);

/**
 * Function that set the message to send.
 * 
 * @param buffer the message buffer to use.
 * @param msg the message to send.
*/
void setMessage(char *buffer, int argc, ...);

/**
 * Function that send a message to the server.
 * 
 * @param socket the socket of the client.
 * @param buffer the message to send.
*/
void sendMessage(int socket, char *buffer);

/**
 * Function that read a message from the server.
 * ## TODO: Finishing doc
 * @param socket the socket of the client.
 * @param buffer the message buffer to use.
*/
void readMessage(int socket, char *buffer);

/**
 * Procedure that communicate with the server as a locker.
 * 
 * @param socket the client socket.
 */
void lockerCommunication(int socket);

void hangarCommunication(int socket);

#endif