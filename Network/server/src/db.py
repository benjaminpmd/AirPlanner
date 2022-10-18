import psycopg2 as psql
import logging

class Database:
    def __init__(self, config: dict[str, str | None]) -> None:
        logging.basicConfig(filename='log.txt', encoding='utf-8', level=logging.DEBUG)
        
        self.connection: psql.connection = psql.connect(host=config.get("HOST"), port=config.get("PORT"), dbname=config.get("DBNAME"), user=config.get("USER"), password=config.get("PASSWORD"))
        logging.info("Connection established with the database")
        self.cursor = self.connection.cursor()

    def close(self) -> None:
        logging.info("Closing the connection to the database...")
        self.cursor.close()
        self.connection.close()
        logging.info("Connection with the database closed")
