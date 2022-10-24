import socket

class Server:
    def __init__(self, address: str = '', port: int = 1024) -> None:
        self.server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        self.server.bind((address, port))
        self.clients: socket = []
    
    def run(self) -> None:
        self.server.listen()
        while True:
            client, adresse_client = self.server.accept()
            self.clients.append(client)
            data = client.recv(80)
            client.send(b"variable echo\n")
    
    def stop(self) -> None:
        for client in self.clients:
            client.close()
        self.server.close()
        