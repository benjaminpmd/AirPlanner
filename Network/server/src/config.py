"""! File containing constant values needed to run the server.

@author Benjamin PAUMARD
@version 1.0.0 (28/11/2022)
@since 10/11/2022
"""
# imports
import logging

# logging configuration
LOGGING_FILE: str = "debug.log"

# level of the logging saved in the file
LOGGING_LEVEL: int = logging.DEBUG

# defining values for the server configuration
ADDRESS: str = ''

# default port for the server
PORT: int = 1024

# timeout value used to avoid too long connections
DEFAULT_TIMEOUT: int = 20

# max message size
MAX_MESSAGE_SIZE: int = 500
