#include "include/client.h"
#include "include/logger.h"

int main(int argc, char const *argv[]) {

    // structure of the socket
    struct sockaddr_in clientAddress;
    int socketClient = socket(AF_INET, SOCK_STREAM, 0);
    clientAddress.sin_addr.s_addr = inet_addr(ADDRESS);
    clientAddress.sin_family = AF_INET;
    clientAddress.sin_port = htons(PORT);

    // connection to the server
    int connectionStatus = connect(socketClient, (const struct sockaddr *)&clientAddress, sizeof(clientAddress));
    if (connectionStatus == 0) {
        printf("connection réussi\n");
        int selection;
        do {
            // ask for which system to run
            printf("Sélectionnez le système à utiliser\n    (1) le lecteur de carte pour casier\n    (2) le lecteur de carte pour portique hangar\nEntrez votre selection: ");
            scanf("%d", &selection);
            if (selection == 1) {
                lockerCommunication(socketClient);
            }
            else if (selection == 2) {
                hangarCommunication(socketClient);
            }

            if ((selection != 1) && (selection != 2)) {
                printf("\nErreur : Veuillez entrer une donnée correcte.\n\n");
            }
        } while ((selection != 1) && (selection != 2));
    }

    return EXIT_SUCCESS;
}