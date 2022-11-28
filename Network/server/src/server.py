import socket
import threading
import logging
import config

from db import Database


class Server:
    def __init__(self, database_config: dict, address: str = '', port: int = 1024) -> None:
        """! Constructor of the server class.

        @param database_config the configuration for the postgreSQL. For more information, please refer to the Database class.
        @param address the address of the server, by default: ''.
        @param port the port used by the server, by default: 1024.
        """

        # creating the database object
        self.db: Database = Database(database_config)

        # port on which the server is running
        self.port: int = port

        self.address: str = address

        # creating the server using TCP
        self.server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)

        # list containing all the clients
        self.clients: list = []

        # defining various error codes
        self.timeout_command: str = "error-timeout"
        self.error_db: str = "error-db"
        self.error_arguments: str = "error-arguments"
        self.error_length: str = "error-length"
        self.error_unknown: str = "error-unknown"


    def close_client(self, client: socket, address: tuple, command: str = "closing-connection") -> None:
        """! close a client.
        
        @param client the socket of the client to close.
        @param address the address of the client to close.
        """
        # log the closing
        logging.info(f"Closing connection with client: {address} with command {command}")
        
        self.clients.remove(client)
        try:
            client.send(command.encode("utf8"))
        except:
            logging.warning(f"could not send close connection command to {address}")
        
        try:
            client.close()
        except:
            pass

    def handle_client(self, client: socket, address: tuple) -> None:
        """! Method that handle exchanges with a client. 
        This methods call the database and manage communications with a specific client.
        
        @param client the client socket to communicate with.
        @param address the address and port of the client.
        """

        # setting the client as active
        logging.info(f"New client with address: {address}")
        
        # set the client loop communication as active
        active_client: bool = True
        
        # while the client is active, receive data from it
        while (active_client):
            client.settimeout(config.DEFAULT_TIMEOUT)
            try:
                # read content sent by the client
                request_content = client.recv(self.port)
            
            except socket.timeout:
                # in case the client is timed out
                logging.error(f"terminating connection with client {address} due to timeout.")
                self.close_client(client, address, self.timeout_command)
                # exit the method
                return

            logging.debug(f"Content received from {address}: {request_content}")
            
            # decode the data
            request_str: str = request_content.decode('utf8')
            logging.debug(f"Content str received from {address}: '{request_str}'")

            # checking if length of the request is correct
            if (len(request_str) > 500):
                self.close_client(client, address, self.error_length)
                return
            
            # extract the data as a list
            data: list = request_str.split(",")
            logging.debug(f"Split data received from {address}: {data}")

            # if no data is provided by the client send message to close the client
            if (len(data) == 0):
                logging.warning(f"Content str received is null")
                self.close_client(client, address, self.error_arguments)
                return
            
            # else the data is not empty, so we can proceed with the data
            
            # case flight, the client wants to check if a locker can be opened
            if (data[0] == "open-locker"):
                
                # len must be 3 as the command is followed by an aircraft registration and an user ID
                if (len(data) == 3):
                    # get the result
                    res: tuple = self.db.check_for_locker_open(data[1], data[2])
                    # reminder
                    # res[0] = if the aircraft is not available
                    # res[1] = if the user is a mechanic
                    # res[2] = if the user is a pilot with a scheduled flight
                    try:
                        if (res is not None):
                            
                            if (res == ()):
                                # the locker cannot be opened, send code 0
                                client.send(b"0")

                            elif (res[1]):
                                # if the second element is true, it's a mechanic, send code 1
                                client.send(b"1")
                            elif (res[2] and not res[0]):
                                # if the third element is true, it's a pilot, send code 2 if the aircraft is available (indicated in the first element of res)
                                client.send(b"2")
                            else:
                                # the locker cannot be opened, send code 0
                                client.send(b"0")
                        else:
                            # if the result not none, then the database could not be reached
                            self.close_client(client, address, self.error_db)  
                            active_client = False                  
                    
                    except:
                        # catch exception in case the message could not be sent
                        logging.warning(f"Forced end of communication at locker open request")
                        self.close_client(client, address, self.error_unknown)
                        active_client = False
                
                else:
                    # else the len of the data is not correct, close the client
                    logging.warning(f"Forced end of communication at locker open request, incorrect arguments")
                    self.close_client(client, address, self.error_arguments)
                    active_client = False
            
            # client sent a command to ask if the door can be opened
            elif(data[0] == "open-door"):
                
                # len must be 3 as the command is followed by a parking number and an user ID
                if (len(data) == 3):
                    
                    # get the result: if the locker can be opened or not
                    res: tuple = self.db.check_for_door_open(data[1], data[2])
                    # reminder
                    # res[0] = if the aircraft is not available
                    # res[1] = if the user is a mechanic
                    # res[2] = if the user is a pilot with a scheduled flight
                    try:
                        if (res is not None):

                            if (res == ()):
                                # the locker cannot be opened, send code 0
                                client.send(b"0")

                            elif ((res[1]) or (res[2] and not res[0])):
                                # if the second element is true, it's a mechanic, send code 1
                                # if the second element, then it's a pilot with a scheduled flight
                                # if the first element, the aircraft is not available
                                client.send(b"1")
                            else:
                                # the locker cannot be opened, send code 0
                                client.send(b"0")
                        else:
                            # if the result not none, then the database could not be reached
                            logging.warning(f"Forced end of communication at door open request due to database error")
                            self.close_client(client, address, self.error_db)
                            active_client = False

                    except:
                        # catch exception in case the message could not be sent
                        logging.warning(f"Forced end of communication at door open request")
                        self.close_client(client, address, self.error_unknown)
                        active_client = False
                
                else:
                    # else the len of the data is not correct, close the client
                    logging.warning(f"Forced end of communication at door open request, incorrect arguments")
                    self.close_client(client, address, self.error_arguments)
                    active_client = False

            # client sent a command indicating that the locker has been opened
            elif(data[0] == "locker-opened"):
                
                # checking the data length
                if (len(data) == 3):
                    
                    # get the flight from the provided ID
                    res: tuple = self.db.get_flight_data(data[1], data[2])
                    
                    if (res is not None):
                        
                        # try to return the data from the client
                        try:
                            if (res == ()):
                                client.send(f"Aucun vol n'est réservé avec l'appareil {data[1]} pour cet identifiant".encode("utf8"))
                                continue

                            # create the message to return
                            message: str = f"Rappels : Fin du créneau à {res[1]}. Avion situé au parking {res[2]}."
                            
                            # add optionally the instructor
                            if (res[3] and res[4]):
                                message = f"{message}\nVotre instructeur pour ce vol est {res[3]} {res[4]}"
                            
                            # update the flight progression status
                            update_status: bool = self.db.set_flight_progress(res[0], True)
                            
                            if (not update_status):
                                message = f"{message}\nUne erreur est survenue lors de la mise à jour de la base de données, vous ne pourrez pas saisir votre vol après l'avoir effectué, merci de contacter un administrateur."
                            
                            # send the return to the client
                            client.send(message.encode("utf8"))
                        
                        except:
                            # else the len of the data is not correct, close the client
                            logging.warning(f"Forced end of communication at locker open")
                            self.close_client(client, address, self.error_unknown)
                            active_client = False
                    
                    else:
                        # error in the database connection or request
                        logging.warning("closing client at opened locker due to database")
                        self.close_client(client, address, self.error_db)
                        active_client = False
                
                else:
                    logging.warning("closing connection at opened locker due to incorrect arguments")
                    self.close_client(client, address, self.error_arguments)
                    active_client = False
                            
            # case flight, the client wants to check if a locker can be opened
            elif (data[0] == "locker-message"):
                
                # len must be 3 as the command is followed by an aircraft registration and an user ID
                if (len(data) == 3):
                    
                    # get the result: if the locker can be opened or not
                    res: tuple = self.db.check_for_locker_open(data[1], data[2])
                    # reminder
                    # res[0] = if the aircraft is not available
                    # res[1] = if the user is a mechanic
                    # res[2] = if the user is a pilot with a scheduled flight
                    try:
                        if (res is not None):
                            if (res == ()):
                                client.send(f"Aucun vol n'est réservé avec l'appareil {data[1]} pour cet identifiant".encode("utf8"))
                            
                            elif (res[1] or (res[2] and not res[0])):
                                client.send("Vous pouvez accéder au casier".encode("utf8"))
                            
                            elif (res[2] and res[0]):
                                client.send("L'appareil réservé est actuellement en maintenance".encode("utf8"))
                            
                            else:
                                client.send(f"Aucun vol n'est réservé avec l'appareil {data[1]} pour cet identifiant".encode("utf8"))
                        
                        else:
                            logging.warning(f"Forced end of communication at locker message request due to database error")
                            self.close_client(client, address, self.error_db)
                            active_client = False
                    except:
                        # catch exception in case the message could not be sent
                        logging.warning(f"Forced end of communication at locker message request")
                        self.close_client(client, address, self.error_unknown)
                        active_client = False
                else:
                    # else the len of the data is not correct, close the client
                    logging.warning(f"Forced end of communication at locker message request, not enough arguments")
                    self.close_client(client, address, self.error_arguments)
                    active_client = False
           
            # client sent a command indicating that the door has been opened
            elif(data[0] == "door-message"):
                
                # len must be 3 as the command is followed by a parking number and an user ID
                if (len(data) == 3):
                    
                    # get the result: if the dor can be opened or not
                    res: tuple = self.db.check_for_door_open(data[1], data[2])
                    try:
                        if (res is not None):
                            if (res == ()):
                                client.send("Aucun vol n'est actuellement prévu pour votre ID pour cet appareil.".encode("utf8"))

                            elif ((res[1]) or (res[2] and not res[0])):
                                client.send("Vous pouvez accéder au hangar".encode("utf8"))
                            
                            elif (res[2] and res[0]):
                                client.send("L'appareil demandé est actuellement en maintenance".encode("utf8"))
                            
                            else:
                                client.send("Aucun vol n'est actuellement prévu pour votre ID pour cet appareil.")
                        else:
                            logging.warning(f"Forced end of communication at door message request due to database error")
                            self.close_client(client, address, self.error_db)
                            active_client = False
                    except:
                        # catch exception in case the message could not be sent
                        logging.warning(f"Forced end of communication at door message request")
                        self.close_client(client, address, self.error_unknown)
                        active_client = False
                else:
                    # else the len of the data is not correct, close the client
                    logging.warning(f"Forced end of communication at door message request, not enough arguments")
                    self.close_client(client, address, self.error_arguments)
                    active_client = False
            
            # client sent a command to terminate the connection
            elif (data[0] == "close-connection"):
                # send that the communication is terminated
                self.close_client(client, address)
                active_client = False
            
            # client sent an unknown command
            else:
                # close the client
                logging.warning(f"Unexpected communication, received: {request_str}")
                self.close_client(client, address, self.error_arguments)
                active_client = False

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

