import socket
import select
import threading

from db import Database

class Server:
    def __init__(self, database_config: dict, address: str = '', port: int = 1024) -> None:
        self.db = Database(database_config)
        self.port = port
        self.server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        self.server.bind((address, port))
        self.server.listen(32)
        self.clients: list = []

    def handle_client(self, client: socket, address: str):
        request_content = client.recv(self.port)
        if (not request_content):
            print("Connection closed")
            client.close()
        request_str: str = request_content.decode()
        if (request_str.startswith("user")):
            data: list = request_str.split("&")
            res = self.db.get_user(data[1])
            client.send(res[2].encode('utf-8'))

    def run(self) -> None:
        self.server.listen()
        while True:
            readable, writable, exceptional = select.select([self.server], self.clients, [self.server], 1)
            for s in readable:
                if (s is self.server):
                    client, client_address = self.server.accept()
                    client.setblocking(False)
                    self.clients.append(client)
                    threading.Thread(target=self.handle_client, args=(client, client_address)).start()
    
    def stop(self) -> None:
        for client in self.clients:
            client.close()
        self.server.close()
        