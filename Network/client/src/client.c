#include "include/client.h"
#include "include/logger.h"

void intToStr(int x, char *o) {
	sprintf(o, "%d", x);
}

void setMessage(char *buffer, int argc, ...) {
	bzero(buffer, MAX_MESSAGE_LENGTH);
	
	va_list argv;

	va_start(argv, argc);
	
	for (int i=0; i < argc; i++) {
		char msgCopy[MAX_MESSAGE_LENGTH-1];
		strcpy(msgCopy, buffer);
		
		if (i == 0) {
			snprintf(buffer, MAX_MESSAGE_LENGTH, "%s", va_arg(argv, char*));
		}
		else {
			snprintf(buffer, MAX_MESSAGE_LENGTH, "%s,%s", msgCopy, va_arg(argv, char*));
		}
	}
	va_end(argv);
}

void sendMessage(int socket, char *buffer) {  

	struct timeval timeout;
	timeout.tv_sec = 20;
	timeout.tv_usec = 0; 
    
	// write the message, if the function return an int inferior to 0, then the message have not been sent
	setsockopt(socket, SOL_SOCKET, SO_SNDTIMEO, &timeout,sizeof timeout);
	if(send(socket, buffer, strlen(buffer), 0) < 0) {
		logError("cannot communicate with the server");
		// close the socket and exit
		close(socket);
		exit(EXIT_FAILURE);
	}
	bzero(buffer, MAX_MESSAGE_LENGTH);
}

void readMessage(int socket, char *buffer) {
	bzero(buffer, MAX_MESSAGE_LENGTH);

	struct timeval timeout;      
    timeout.tv_sec = 20;
    timeout.tv_usec = 0;

	setsockopt(socket, SOL_SOCKET, SO_RCVTIMEO, &timeout, sizeof timeout);
	// write the message, if the function return an int inferior to 0, then the message have not been sent
	if(read(socket, buffer, MAX_MESSAGE_LENGTH) < 0) {
		logError("message could not be received from the server");
		// close the socket and exit
		close(socket);
		exit(EXIT_SUCCESS);
	}
	// check if the connection ends
	else if ((strcmp(buffer, "closing-connection")) == 0) {
        logInfo("stopping client");
		close(socket);
		exit(EXIT_SUCCESS);
    }
}

void lockerCommunication(int socket) {
	// init variables used in the function, the buffer for communication, the userInput
	// and the userInput conversion into a string
	char buffer[MAX_MESSAGE_LENGTH] = "";
	int userIdInput;
	char userId[4];
	char statusCode[1];

	// ask the input of the user (simulate a card read)
	printf("Veuillez entrer un ID utilisateur : ");
	scanf("%d", &userIdInput);
	
	// convert the input to a string
	intToStr(userIdInput, userId);

	/* Request if a flight is currently scheduled with the registration and userID provided */
	
	// create the command and save it into the buffer
	setMessage(buffer, 3, "open-locker", REGISTRATION, userId);
    // send the buffer to the server
	sendMessage(socket, buffer);
	// read the response from the server
	readMessage(socket, buffer);

	// act depending of the return from the server
	if (atoi(buffer) == 1) {
		// code 1 means that the user is a mechanic
		printf("Ouverture du casier de l'appareil %s\n", REGISTRATION);
	}
	else if (atoi(buffer) == 2) {
		// code 2 means that the user is a pilot with a scheduled flight
		printf("Ouverture du casier de l'appareil %s\n", REGISTRATION);
		
		// create the command and save it into the buffer
		setMessage(buffer, 3, "locker-opened", REGISTRATION, userId);
    	// send the buffer to the server
		sendMessage(socket, buffer);
		// read the response from the server
		readMessage(socket, buffer);
		// print the information sent by the server, should be indications about the flight
		printf("%s\n", buffer);
	}
	else {
		// else, do not open the locker
		printf("Aucun vol n'est enregistré pour l'appareil %s avec cet ID.\n", REGISTRATION);
	}

	// close the connection
	setMessage(buffer, 1, "close-connection");
	sendMessage(socket, buffer);
	readMessage(socket, buffer);
}

void hangarCommunication(int socket) {
	// init variables used in the function, the buffer for communication, the userInput
	// and the userInput conversion into a string
	char buffer[MAX_MESSAGE_LENGTH] = "";
	int userIdInput;
	char userId[4];
	char statusCode[1];

	// ask the input of the user (simulate a card read)
	printf("Veuillez entrer un ID utilisateur : ");
	scanf("%d", &userIdInput);
	
	// convert the input to a string
	intToStr(userIdInput, userId);

	/* Request if a flight is currently scheduled with the registration and userID provided */
	
	// create the command and save it into the buffer
	setMessage(buffer, 3, "open-door", PARKING, userId);
    // send the buffer to the server
	sendMessage(socket, buffer);
	// read the response from the server
	readMessage(socket, buffer);

	// act depending of the return from the server
	if (atoi(buffer) == 1) {
		// code 1 means that the user is a mechanic
		printf("Ouverture de la porte...\n");
	}
	else {
		// else, do not open the locker
		printf("Vous ne pouvez pas accéder à ce hangar.\n");
	}

	// create the command and save it into the buffer
	setMessage(buffer, 3, "door-message", PARKING, userId);
    // send the buffer to the server
	sendMessage(socket, buffer);
	// read the response from the server
	readMessage(socket, buffer);

	printf("%s\n", buffer);

	// close the connection
	setMessage(buffer, 1, "close-connection");
	sendMessage(socket, buffer);
	readMessage(socket, buffer);
}


