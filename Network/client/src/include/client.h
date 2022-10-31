#ifndef client
#define client

#ifdef _WIN32
#include <Winsock2.h>
#endif

#include <sys/socket.h>
#include <unistd.h>
#include <netdb.h>
#include <arpa/inet.h>
#include <stdio.h>
#include <stdlib.h>
#include <pthread.h>
#include <string.h>
#include <stdbool.h>

#define ADDRESS "192.168.0.1"
#define PORT 1025

void lockerCommunication(int sock, void* msg, uint32_t msgsize);

void hangarCommunication(int sock, void* msg, uint32_t msgsize);

#endif