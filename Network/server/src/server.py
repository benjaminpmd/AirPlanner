import socket

clients = []
server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)

server.bind(('', 8080))

server.listen()

while True:
    client, adresseClient = server.accept()
    clients.append(client)
    data = client.recv(80)
    client.send(b"variable echo\n")
    print(data)
    break

for client in clients:
    client.close()

class Server:
    def __init__(self, address: str = '', port: int = 1024) -> None:
        self.server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        self.server.bind((address, port))
        self.clients: socket = []
    
    def run(self):
        server.listen()
        while True:
            client, adresse_client = server.accept()
            self.clients.append(client)
            data = client.recv(80)
            client.send(b"variable echo\n")
            break
    
    def stop(self):
        for client in self.clients:
            client.close()
        self.server.close()
        