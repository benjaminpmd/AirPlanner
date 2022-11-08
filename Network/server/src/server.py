import socket
import threading
import logging

from db import Database


class Server:
    def __init__(self, database_config: dict, address: str = '', port: int = 1024) -> None:
        logging.basicConfig(format='%(asctime)s: %(levelname)s: Server: %(message)s',
                            datefmt='%m/%d/%Y %I:%M:%S %p', level=logging.INFO)
        self.db = Database(database_config)
        self.port = port
        self.server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        self.server.bind((address, port))
        self.server.listen(32)
        self.clients: list = []
    
    def close_client(self, client: socket, address: tuple) -> None:
        logging.info(f"Closing connection with client: {address}")
        try:
            client.close()
        except:
            pass
        self.clients.remove(client)

    def handle_client(self, client: socket, address: tuple) -> None:
        logging.info(f"New client with address: {address}")
        active_client: bool = True
        while (active_client):
            request_content = client.recv(self.port)
            logging.debug(
                f"Content received from {address}: {request_content}")
            request_str: str = request_content.decode('utf-8')
            logging.debug(
                f"Content str received from {address}: {request_str}")
            data: list = request_str.split("&")
            data.pop()
            logging.debug(f"Splited data received from {address}: {data}")

            match(data[0]):
                case "user":
                    res = self.db.get_user(data[1])
                    try:
                        client.send(res[2].encode())
                    except:
                        logging.warning(f"Forced end of communication: '{other}'")
                        self.close_client(client, address)
                        active_client = False

                case "firstname":
                    res = self.db.get_user(data[1])
                    try:
                        client.send(res[4].encode())
                    except:
                        logging.warning(f"Forced end of communication: '{other}'")
                        self.close_client(client, address)
                        active_client = False

                case "closeConnection":
                    res = "endConnection"
                    client.send(res.encode())
                    active_client = False
                    self.close_client(client, address)

                case other:
                    logging.warning(f"Unexpected communication: '{other}'")
                    res = "endConnection"
                    client.send(res.encode())
                    active_client = False
                    self.close_client(client, address)

    def run(self) -> None:
        logging.info("Running the server")
        self.server.listen()
        while True:
            client, client_address = self.server.accept()
            self.clients.append(client)
            threading.Thread(target=self.handle_client,
                             args=(client, client_address)).start()

    def stop(self) -> None:
        for client in self.clients:
            client.close()
        self.server.close()
