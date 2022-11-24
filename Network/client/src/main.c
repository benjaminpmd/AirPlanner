#include "include/logger.h"
#include "include/client.h"

int main(int argc, char const *argv[]) {
    char address[16];
    int port;

    printf("Veuillez entrer une adresse IP (appuyez sur entrer pour utiliser l'adresse par défaut : %s) : ", ADDRESS);
    scanf("%[^\n]%*c", address);
    
    printf("Veuillez entrer un port (0 pour utiliser le port par défaut : %d) : ", PORT);
    scanf("%d", &port);

    // structure of the socket
    struct sockaddr_in clientAddress;
    int socketClient = socket(AF_INET, SOCK_STREAM, 0);
    clientAddress.sin_family = AF_INET;

    // use default or input server address
    if (strlen(address) <= 6) {
        clientAddress.sin_addr.s_addr = inet_addr(ADDRESS);
    }
    else {
        clientAddress.sin_addr.s_addr = inet_addr(address);
    }

    // use default or input server port
    if (port == 0) {
        clientAddress.sin_port = htons(PORT);
    }
    else {
        clientAddress.sin_port = htons(port);
    }

    // connection to the server
    int connectionStatus = connect(socketClient, (const struct sockaddr *)&clientAddress, sizeof(clientAddress));
    if (connectionStatus == 0) {
        logData("info", "connection successful");
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
                logData("warning", "user input error");
            }
        } while ((selection != 1) && (selection != 2));
    }
    else {
        printf("Erreur: Le serveur ne peut pas être atteint.\n");
        logData("error", "server unavailable");
    }

    return EXIT_SUCCESS;
}