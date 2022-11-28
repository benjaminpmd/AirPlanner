"""!
File containing the main function for the server. Run this file to start the server.

@author MA Xuming, FLEUTRY Eva, PAUMARD Benjamin
@version 0.0.1
@since 2022.10.18
"""
#!/usr/bin/python3

# importing librairies
import logging
from dotenv import dotenv_values
import config
from server import Server



def main() -> None:
    """Function starting the server."""
    # setup logging
    logging.basicConfig(
        format='%(asctime)s - %(levelname)s - SERVER: %(message)s',
        datefmt='%m/%d/%Y %H:%M:%S',
        level=config.LOGGING_LEVEL,
        handlers=[
            logging.FileHandler(config.LOGGING_FILE),
            logging.StreamHandler()
        ]
    )

    # parameters that must be present in the db configuration
    db_params: list = ["HOST", "PORT", "DBNAME", "USER", "PASSWORD"]

    # getting the values stored in the .env file
    logging.debug("extracting data from .env file")
    db_config = dotenv_values(".env")

    logging.debug("checking for db_config validity")
    for value in db_params:
        if (db_config.get(value) is None):
            logging.fatal(f"incorrect db param: {value}={db_config.get(value)}")
            quit()

    input_port: str = ''
    port: int = 0

    while ((port < 1024) or (port > 65535)):
        input_port = input(f"Please enter a port to use (press enter to use the default port ({config.PORT}): ")
        if (input_port == ''):
            port = config.PORT
        else:
            try:
                port = int(input_port)
                if ((int(port) < 1024) or (int(port) > 65535)):
                    print("Error: Port must be in range 1024 - 65535.")
            except:
                print("Error: Port must be only numeric.")

    server: Server = Server(db_config, config.ADDRESS, port)

    server.run()


if __name__ == '__main__':
    main()
