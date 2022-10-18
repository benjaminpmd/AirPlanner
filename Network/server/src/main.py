#import server
import db
from dotenv import dotenv_values

ADDRESS = ''
PORT = 1024

def main():
    config = dotenv_values(".env")
    #s: server.Server = server.Server(ADDRESS, PORT)
    #s.accept_client()
    d = db.Database(config)
    d.close()

if __name__ == '__main__':
    main()