#include "include/client.h"

int main(int argc, char const *argv[]) {
	char id, server_reply[2000];

    int socketClient = socket(AF_INET, SOCK_STREAM, 0);
    struct sockaddr_in addrClient;
    addrClient.sin_addr.s_addr = inet_addr("192.168.3.137");
    addrClient.sin_family = AF_INET;
    addrClient.sin_port = htons(PORT);
    connect(socketClient, (const struct sockaddr *)&addrClient, sizeof(addrClient));
    printf("connection réussi\n");

	char reserv[] = "user&1";
    lockerCommunication(socketClient, reserv, sizeof(reserv));


    close(socketClient);

    return 0;
}