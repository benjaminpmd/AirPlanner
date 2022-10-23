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
#define PORT 1024
#define SA struct sockaddr
/**
 * This procedure executes the client 
 * 
 * @param sockfd the socket
*/
void execute(int sockfd);

#endif