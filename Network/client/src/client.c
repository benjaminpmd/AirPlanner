#include "include/client.h"
#include "include/logger.h"

void buildMessage(char *message, int argc, ...) {
	va_list argv;

	va_start(argv, argc);
	for (int i=0; i < argc; i++) {
		snprintf(message, MAX_MESSAGE_LENGTH, "%s,%s", message, va_arg(argv, char*));
	}
	printf("%s\n", message);
	va_end(argv);
}

void sendMessage(int socket, char *buffer) {
	// write the message, if the function return an int inferior to 0, then the message have not been sent
	if(send(socket, buffer, strlen(buffer), 0) < 0) {
		printf("Error: message could not be delivered to the server.\n");
		// close the socket and exit
		close(socket);
		exit(EXIT_FAILURE);
	}
	bzero(buffer, MAX_MESSAGE_LENGTH);
}

void readMessage(int socket, char *buffer) {
	bzero(buffer, MAX_MESSAGE_LENGTH);
	// write the message, if the function return an int inferior to 0, then the message have not been sent
	if(read(socket, buffer, MAX_MESSAGE_LENGTH) < 0) {
		printf("Error: message could not be received from the server.\n");
		// close the socket and exit
		close(socket);
		exit(EXIT_FAILURE);
	}
	// check if the connection ends
	else if ((strcmp(buffer, "endConnection")) == 0) {
        printf("Client Exit...\n");
		close(socket);
		exit(EXIT_SUCCESS);
    }
}

void lockerCommunication(int socket) {
	char readerBuffer[MAX_MESSAGE_LENGTH];

	int userId;

	printf("Please enter an user ID: ");
	scanf("%d", &userId);

	char userIdStr[4] = "";
	sprintf(userIdStr, "%d", userId);

	char message[MAX_MESSAGE_LENGTH] = "";
	char msg[MAX_MESSAGE_LENGTH] = "";
	snprintf(message, MAX_MESSAGE_LENGTH, "%s,%s,%s", "flight", REGISTRATION, userIdStr);
	buildMessage(msg, 3, "flight", REGISTRATION, userIdStr);
    sendMessage(socket, message);
	readMessage(socket, readerBuffer);

	if (atoi(readerBuffer) == -1) {
		printf("No flight is scheduled for this aircraft with your ID.\n");
	}
	else {
		printf("Opening locker...\n");
	}
	snprintf(message, MAX_MESSAGE_LENGTH, "%s,%s", "firstname", userIdStr);
    sendMessage(socket, message);
	readMessage(socket, readerBuffer);

	if (atoi(readerBuffer) == -1) {
		printf("No flight is scheduled for this aircraft with your ID.\n");
	}
	else {
		printf("%s\n", readerBuffer);
	}
	snprintf(message, MAX_MESSAGE_LENGTH, "%s", "closeConnection");
	sendMessage(socket, message);
	readMessage(socket, readerBuffer);
}

void hangarCommunication(int sock, void* msg, uint32_t msgsize)
{
	if(write(sock, msg, msgsize) < 0){
		printf("Error occurred while sending the message");
		close(sock);
		exit(1);
	}
	printf("M, (%d bits envoyés).\n", msgsize);
	
}


