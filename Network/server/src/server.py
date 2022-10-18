import socket
from db import Database
import threading
import logging

class Server():
    def __init__(self, database_config: dict, address: str = '', port: int = 1024) -> None:
        """ This class contains the server to run.
        
        @param database_config the dictionary containing the data used to connect to the database. More information here @see{Database}.
        @param address the address of the server (default: '')
        @param port the port on which the server will run (default: 1024)
        """
        logging.basicConfig(filename='log.txt', encoding='utf-8', level=logging.DEBUG, datefmt='%Y-%m-%d %H:%M:%S')
        # clients list
        self.clients: list = []
        # indications about the server
        self.address: str = address
        self.port: int = port
        # creating the database connection
        self.db = Database(database_config)
        # creating the server
        self.server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        self.server.bind((address, port))
        self.server.listen(5)
        self.running: bool = False
        # creating the thread that will run the server
        self.server_thread = threading.Thread(target=self._accept_clients)

    def _accept_clients(self):
        """ Method that mainly run the server, accept connections and call actions. """
        logging.info(f"Starting server on port {self.port}")
        # starting the server
        self.running = True
        while self.running:
            try:
                # each time a client tries to connect, accept the connection and save it in the clients list
                client, client_address = self.server.accept()
                self.clients.append(client)
                logging.info(f"New connexion | Address: {client_address}")
                ## DATA PART
                data = client.recv(self.port)
                if (data):
                    ## TODO : HERE MUST GO THE CALL TO METHODS TO REPLY TO THE CLIENT
                    print (f"data: {data}")
                    res = data.upper()
                    n = client.send(res)
                    if (n != len(res)):
                        print('Error')
                    else:
                        print('Response sent')
                else:
                    # if no data, close the connection
                    client.close()
                    self.clients.remove(client)
                    logging.info(f"No data transmitted by client, closing | Address: {client_address}")
            except Exception as e:
                logging.error(e)
    
    def start(self) -> None:
        """Method that start the server. """
        self.server_thread.start()
    
    def stop(self) -> None:
        """ Method that stop the server, close the connection with the clients and close the connection to the database. """
        logging.info('Stopping server...')
        # indicating to the loop that the server is closing
        self.running = False
        # closing the connections with the clients
        for client in self.clients:
            client.close()
        # stopping the server
        self.server.close()
        # closing the connection to the database
        self.db.close()
        logging.info('Server stopped')
    
