#ifndef client
#define client


#include <arpa/inet.h>
#include <netdb.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <strings.h>
#include <sys/socket.h>
#include <unistd.h>

#define MAX 80
#define PORT 8080
#define SA struct sockaddr

/**
 * This procedure enable communications with the server
 * 
 * @param sockfd the socket
*/
void communication(int sockfd);

// INFO: the procedures declared here are meant to communicate with the server, so we are going to have 2 procedure
// void lockerCommunication(int sockfd);
// void hangarCommunication(int sockfd);

#endif