#include "include/client.h"

int main(int argc, char const *argv[]) {
	
    char id, server_reply[2000];

    /**
     * Structure of the client socket
     */
    struct sockaddr_in clientAddress;
    int socketClient = socket(AF_INET, SOCK_STREAM, 0);
    clientAddress.sin_addr.s_addr = inet_addr(ADDRESS);
    clientAddress.sin_family = AF_INET;
    clientAddress.sin_port = htons(PORT);
    connect(socketClient, (const struct sockaddr *)&clientAddress, sizeof(clientAddress));
    printf("connection réussi\n");

    lockerCommunication(socketClient);

    return EXIT_SUCCESS;
}