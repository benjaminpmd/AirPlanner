import socket
import threading
import logging
import time
import config

from db import Database


class Server:
    def __init__(self, database_config: dict, address: str = '', port: int = 1024) -> None:
        """! Constructor of the server class.

        @param database_config the configuration for the postgreSQL. For more information, please refer to the Database class.
        @param address the address of the server, by default: ''.
        @param port the port used by the server, by default: 1024.
        """
        logging.basicConfig(
            format='%(asctime)s - %(levelname)s - Server: %(message)s',
            datefmt='%m/%d/%Y %H:%M:%S',
            level=config.LOGGING_LEVEL,
            handlers=[
                logging.FileHandler(config.LOGGING_FILE),
                logging.StreamHandler()
            ]
        )

        # creating the database object
        self.db = Database(database_config)

        # port on which the server is running
        self.port = port

        self.address = address

        # creating the server using TCP
        self.server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)

        # list containing all the clients
        self.clients: list = []

    def close_client(self, client: socket, address: tuple) -> None:
        """! close a client.
        
        @param client the socket of the client to close.
        @param address the address of the client to close.
        """
        logging.info(f"Closing connection with client: {address}")
        self.clients.remove(client)
        try:
            client.send(b"closing-connection")
        except:
            pass
        try:
            client.close()
        except:
            pass

    def handle_client(self, client: socket, address: tuple) -> None:
        """! Method that handle exchanges with a client. 
        This methods call the database and manage communications with a specific client.
        
        @param client the client socket to communicate with.
        @param address, the address and port of the client.
        """

        # setting the client as active
        logging.info(f"New client with address: {address}")
        active_client: bool = True
        
        # while the client is active, receive data from it
        while (active_client):
            client.settimeout(config.DEFAULT_TIMEOUT)
            try:
                # read content sent by the client
                request_content = client.recv(self.port)
            except socket.timeout:
                logging.error(f"terminating connection with client {address} due to timeout.")
                self.close_client(client, address)
                return

            logging.debug(f"Content received from {address}: {request_content}")
            
            # decode the data
            request_str: str = request_content.decode('utf8')
            logging.debug(f"Content str received from {address}: '{request_str}'")
            
            # extract the data as a list
            data: list = request_str.split(",")
            logging.debug(f"Split data received from {address}: {data}")

            # if no data is provided by the client send message to close the client
            if (len(data) == 0):
                logging.warning(f"Content str received is null")
                active_client = False
                self.close_client(client, address)
            
            # else the data is not empty, so we can proceed with the data
            else:
                # case flight, the client wants to check if a locker can be opened
                if (data[0] == "open-locker"):
                    # len must be 3 as the command is followed by an aircraft registration and an user ID
                    if (len(data) == 3):
                        # get the result: if the locker can be opened or not
                        res = self.db.check_for_locker_open(data[1], data[2])
                        try:
                            if (res != None):
                                if (res[0]):
                                    # if the first element is true, it's a mechanic, send code 1
                                    client.send(b"1")
                                elif (res[1]):
                                    # if the first element is true, it's a pilot, send code 2
                                    client.send(b"2")
                                else:
                                    # the locker cannot be opened, send code 0
                                    client.send(b"0")
                            else:
                                # if the result not none, then the locker cannot be opened
                                # retuning the code 0
                                client.send(b"0")
                        except Exception as e:
                            # catch exception in case the message could not be sent
                            logging.warning(f"Forced end of communication at flight request")
                            self.close_client(client, address)
                            active_client = False
                    else:
                        # else the len of the data is not correct, close the client
                        logging.warning(f"Forced end of communication at flight request, not enough arguments")
                        self.close_client(client, address)
                        active_client = False
                
                # client sent a command indicating that the locker has been opened
                elif(data[0] == "locker-opened"):
                    
                    # checking the data length
                    if (len(data) == 3):
                        
                        # get the flight from the provided ID
                        res = self.db.get_flight(data[1], data[2])
                        if (res != None):
                            # update the flight progression status
                            self.db.set_flight_progress(res[0], True)
                            # try to return the data from the client
                            try:
                                message: str = f"Rappels : Fin du créneau à {res[1]}. Avion situé au parking : {res[2]}."
                                if (res[3] and res[4]):
                                    message = f"{message}\nVotre instructeur pour ce vol est {res[3]} {res[4]}"
                                # send the return to the client
                                client.send(message.encode("utf8"))
                            except:
                                # else the len of the data is not correct, close the client
                                logging.warning(f"Forced end of communication at firstname")
                                self.close_client(client, address)
                                active_client = False
                        else:
                            self.close_client(client, address)
                            active_client = False
                    else:
                        self.close_client(client, address)
                        active_client = False
                # client sent a command to terminate the connection
                elif (data[0] == "close-connection"):
                    # send that the communication is terminated
                    active_client = False
                    self.close_client(client, address)
                # client sent an unknown command
                else:
                    # close the client
                    logging.warning(f"Unexpected communication, received: {request_str}")
                    active_client = False
                    self.close_client(client, address)

    def run(self) -> None:
        """! Method to run the server."""

        try:
            # bind the server to its address and its port
            self.server.bind((self.address, self.port))
        except PermissionError:
            logging.error(f"could not bind the server to port: {self.port}")
            return
        except Exception as e:
            logging.error(f"error occurred while binding the server: {e}")
            return
            
        # listen up to 30 clients
        self.server.listen(30)

        logging.info(f"Running the server on port: {self.port}")

        running_server: bool = True
        
        # running the server
        while (running_server):
            # accept connections
            try:
                client, client_address = self.server.accept()
                # append the client to all the client list
                self.clients.append(client)

                # start a thread to handle the communication
                threading.Thread(target=self.handle_client, args=(client, client_address)).start()
            
            except KeyboardInterrupt:
                logging.info("server stopped by the administrator.")
                running_server = False

