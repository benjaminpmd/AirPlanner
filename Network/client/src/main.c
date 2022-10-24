#include "include/client.h"

int main(int argc, char const *argv[]) {
	int sockfd, connfd;
	struct sockaddr_in serverAddress, cli;

	// socket create and verification
	sockfd = socket(AF_INET, SOCK_STREAM, 0);
	if (sockfd == -1) {
		printf("socket creation failed...\n");
		exit(0);
	}
	else printf("Socket successfully created..\n");
	bzero(&serverAddress, sizeof(serverAddress));

	// assign IP, PORT
	serverAddress.sin_family = AF_INET;
	serverAddress.sin_addr.s_addr = inet_addr("127.0.0.1");
	serverAddress.sin_port = htons(PORT);

	// connect the client socket to server socket
	if (connect(sockfd, (SA*)&serverAddress, sizeof(serverAddress))
		!= 0) {
		printf("connection with the server failed...\n");
		exit(0);
	}
	else
		printf("connected to the server..\n");

	// function for chat
	communication(sockfd);

	// close the socket
	close(sockfd);

}
