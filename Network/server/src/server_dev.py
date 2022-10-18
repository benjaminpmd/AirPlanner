import socket
import threading
import logging

class Server():
    def __init__(self, address: str = '', port: int = 1024) -> None:

        logging.basicConfig(filename='log.txt', encoding='utf-8', level=logging.DEBUG)

        # clients list
        self.clients: list = []
        self.address: str = address
        self.port: int = port
        
        # creating the server
        self.server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        self.server.bind((address, port))
        self.server.listen(20)

    def accept_client(self):
        logging.info(f"Server started on port {self.port}")
        client, client_address = self.server.accept()
        self.clients.append(client)
        logging.info(f"New connexion | Address: {client_address}")
        
        data = client.recv(1024)
        if (not data):
            logging.warning(f"No data transmitted by client | Address: {client_address}")
        else:
            print (f"data: {data}")
            res = data.upper()
            n = client.send(res)
            if (n != len(res)):
                print('Error')
            else:
                print('Response sent')
    
    def stop(self) -> None:
        print('Stopping server...')
        for client in self.clients:
            client.close()
        self.server.close()
        print('Server stopped...')
    
