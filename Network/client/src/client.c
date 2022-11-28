#include "include/client.h"
#include "include/logger.h"

void intToStr(int x, char *o) {
	sprintf(o, "%d", x);
}

void setMessage(char *buffer, int argc, ...) {
	// reset the buffer to the max message length
	bzero(buffer, MAX_MESSAGE_LENGTH);
	
	// set argv as the array of parameters
	va_list argv;

	// setting the size of the argv value
	va_start(argv, argc);
	
	// for each parameter passed
	for (int i=0; i < argc; i++) {
		
		// message copy will hold the current result content
		char msgCopy[MAX_MESSAGE_LENGTH-1];
		
		// copy the result to the messageCopy
		strcpy(msgCopy, buffer);
		
		
		if (i == 0) {
			// if i == 0 it means it's the first element, the message copy is not useful as it will be empty
			snprintf(buffer, MAX_MESSAGE_LENGTH, "%s", va_arg(argv, char*));
		}
		else {
			// else store in the buffer the current message and add the next argument
			snprintf(buffer, MAX_MESSAGE_LENGTH, "%s,%s", msgCopy, va_arg(argv, char*));
		}
	}
	// end the argv array
	va_end(argv);
}

void sendMessage(int socket, char *buffer) {  
	// setup the timeout
	struct timeval timeout;
	timeout.tv_sec = 20;
	timeout.tv_usec = 0; 
    
	// set the timeout
	setsockopt(socket, SOL_SOCKET, SO_SNDTIMEO, &timeout,sizeof timeout);
	
	// send the message, if the function return a value lower than 0
	// then the communication cannot be established
	if(send(socket, buffer, strlen(buffer), 0) < 0) {
		
		// inform the user
		printf("Impossible de communiquer avec le serveur.\n");
		
		// log the event
		logError("cannot communicate with the server on sending message");
		
		// close the socket and exit
		close(socket);
		exit(EXIT_SUCCESS);
	}

	// reset the buffer to its original size
	bzero(buffer, MAX_MESSAGE_LENGTH);
}

void readMessage(int socket, char *buffer) {
	// setup the timeout
	struct timeval timeout;      
    timeout.tv_sec = 20;
    timeout.tv_usec = 0;

	// reset the buffer to its original size
	bzero(buffer, MAX_MESSAGE_LENGTH);

	// set the timeout
	setsockopt(socket, SOL_SOCKET, SO_RCVTIMEO, &timeout, sizeof timeout);
	
	// read the message, if the function return a value lower than 0
	// then the communication cannot be established
	if(read(socket, buffer, MAX_MESSAGE_LENGTH) < 0) {
		// inform the user
		printf("Impossible de communiquer avec le serveur.\n");

		// log the error
		logError("message could not be received from the server on message read");
		
		// close the socket and exit
		close(socket);
		exit(EXIT_SUCCESS);
	}

	// check if the command received a close command
	else if ((strcmp(buffer, "closing-connection")) == 0) {
		// log the information
		logInfo("client received close instruction => closing the client");

		// close the socket and exit
		close(socket);
		exit(EXIT_SUCCESS);
    }

	// check if the command received a timeout command
	else if ((strcmp(buffer, "error-timeout")) == 0) {
		// log the information
		logWarning("client received timeout command");

		// close the socket and exit
		close(socket);
		exit(EXIT_SUCCESS);
    }

	// check if the command received a database unavailable error
	else if ((strcmp(buffer, "error-db")) == 0) {
		// inform the user
		printf("Impossible de communiquer avec la base de données. Veuillez contacter l'administrateur.\n");
        
		// log the information
		logError("client received error due to database unavailable on select");

		// close the socket and exit
		close(socket);
		exit(EXIT_SUCCESS);
    }

	// check if the command received a bad arguments command
	else if ((strcmp(buffer, "error-arguments")) == 0) {
		// inform the user
		printf("Une erreur est survenue. Veuillez contacter l'administrateur.\n");
		        
		// log the information
		logError("client received error due bad arguments");

		// close the socket and exit
		close(socket);
		exit(EXIT_SUCCESS);
    }

	// check if the command received a too long data error
	else if ((strcmp(buffer, "error-length")) == 0) {
		// inform the user
		printf("Une erreur est survenue. Veuillez contacter l'administrateur.\n");

		// log the information
		logError("client received error due to too long length");

		// close the socket and exit
		close(socket);
		exit(EXIT_SUCCESS);
    }

	// check if the command received a too long data error
	else if ((strcmp(buffer, "error-unknown")) == 0) {
		// inform the user
		printf("Une erreur est survenue. Veuillez contacter l'administrateur.\n");
        
		// log the information
		logError("client received unknown error");

		// close the socket and exit
		close(socket);
		exit(EXIT_SUCCESS);
    }
}

void lockerSequence(int socket) {
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

	/* check if the locker can be opened */
	
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
		// code 0 indicate that the locker cannot be opened, print to the user and request the reason why 
		// the locker cannot be opened
		printf("Le casier de l'appareil %s ne peut pas être ouvert\n", REGISTRATION);
		
		// create the command and save it into the buffer
		setMessage(buffer, 3, "locker-message", REGISTRATION, userId);
    	// send the buffer to the server
		sendMessage(socket, buffer);
		// read the response from the server
		readMessage(socket, buffer);
		// print the information sent by the server, should indicate the reason why the locker cannot be opened
		printf("%s\n", buffer);
	}

	// close the connection
	setMessage(buffer, 1, "close-connection");
	sendMessage(socket, buffer);
	readMessage(socket, buffer);
}

void hangarSequence(int socket) {
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

	/* Request if a door can be opened */
	
	// create the command and save it into the buffer
	setMessage(buffer, 3, "open-door", PARKING, userId);
    // send the buffer to the server
	sendMessage(socket, buffer);
	// read the response from the server
	readMessage(socket, buffer);

	// act depending of the return from the server
	if (atoi(buffer) == 1) {
		// code 1 means that the user can access
		printf("Ouverture de la porte...\n");
	}
	else {
		// else, do not open the locker
		printf("Vous ne pouvez pas accéder à ce hangar.\n");
	}

	/* Request the message that should be printed to the user */

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


