#include "include/client.h"


void setMessage(char *buffer, char *msg) {
	// remove the possible previous content
	bzero(buffer, MAX_MESSAGE_LENGTH);
	// apply the new value of the string
	strcpy(buffer, msg);
}

void sendMessage(int socket, char *buffer) {
	// write the message, if the function return an int inferior to 0, then the message have not been sent
	if(send(socket, buffer, MAX_MESSAGE_LENGTH, 0) < 0) {
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
	char buffer[MAX_MESSAGE_LENGTH];

	setMessage(buffer, "user&3&");
    sendMessage(socket, buffer);
	readMessage(socket, buffer);
    printf("From Server : %s\n", buffer);

	setMessage(buffer, "firstname&7&");
	sendMessage(socket, buffer);
	readMessage(socket, buffer);
    printf("From Server : %s\n", buffer);

	setMessage(buffer, "closeConnection&");
	sendMessage(socket, buffer);
	readMessage(socket, buffer);
    printf("From Server : %s\n", buffer);
}

void hangarCommunication(int sock, void* msg, uint32_t msgsize)
{
	if(write(sock, msg, msgsize) < 0){
		printf("Probleme lors de l'envoi");
		close(sock);
		exit(1);
	}
	printf("Message envoyé, (%d bits envoyés).\n", msgsize);
	
}


