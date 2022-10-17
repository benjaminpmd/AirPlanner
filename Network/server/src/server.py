import socket
ADRESS = ''
PORT = 1024

server= socket.socket(socket.AF_INET, socket.SOCK_STREAM)
server.bind((ADRESS, PORT))
server.listen(1)
client, client_adress = server.accept()
print('Connexion of a new client: ', client_adress)

data = client.recv(1024)

if (not data):
    print('Error: no data')
else:
    print (f"data: {data}")
    res = data.upper()
    n = client.send(res)
    if (n != len(res)):
        print('Error')
    else:
        print('Response sent')


print('CLosing client')
client.close()
print('Stopping server')
server.close()