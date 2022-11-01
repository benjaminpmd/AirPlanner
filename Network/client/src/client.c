#include "include/client.h"

void lockerCommunication(int sock, void* msg, uint32_t msgsize)
{
	if(write(sock, msg, msgsize) < 0){
		printf("Probleme lors de l'envoi");
		close(sock);
		exit(1);
	}
	read(sock, msg, 300);
	printf("From server : %s\n", msg);
	printf("Message envoyé, (%d bits envoyés).\n", msgsize);
	
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


