import psycopg2 as psql
import logging

class Database:
    def __init__(self, config: dict[str, str | None]) -> None:
        logging.basicConfig(encoding='utf-8', level=logging.DEBUG)
        self.connection_string: str = f"host={config.get('HOST')} port={config.get('PORT')} dbname={config.get('DBNAME')} user={config.get('USER')} password={config.get('PASSWORD')}"

    def test_connection(self) -> None:
        connection: psql.connection = psql.connect(self.connection_string)
        cursor: psql.cursor = connection.cursor()
        cursor.execute("SELECT first_name FROM users;")
        print(cursor.fetchall())
        cursor.close()
        connection.close()
