"""
    File containing the main function for the server. Run this file to start the server.

    @author MA Xuming, FLEUTRY Eva, PAUMARD Benjamin
    @version 0.0.1
    @since 2022.10.18
"""
#!/usr/bin/python3

# importing librairies
from dotenv import dotenv_values
from db import Database
from server import Server

# defining values for the server configuration
ADDRESS = ''
PORT = 1025

def main() -> None:
    """Function starting the server."""

    # getting the values stored in the .env file
    #config = dotenv_values(".env")

    #db = Database(config)
    server = Server(ADDRESS, PORT)

    #db.test_connection()
    server.run()

if __name__ == '__main__':
    main()