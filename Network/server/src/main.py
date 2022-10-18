"""
    File containing the main function for the server. Run this file to start the server.

    @author MA Xuming, FLEUTRY Eva, PAUMARD Benjamin
    @version 0.0.1
    @since 2022.10.18
"""
#!/usr/bin/python3

# importing librairies
from dotenv import dotenv_values
import server

# defining values for the server configuration
ADDRESS = ''
PORT = 1024

def main() -> None:
    """Function starting the server."""

    # getting the values stored in the .env file
    config = dotenv_values(".env")

    # creating the server and starting it
    s: server.Server = server.Server(config, ADDRESS, PORT)
    s.start()

    input("Press enter to stop the server...")
    s.stop()

if __name__ == '__main__':
    main()