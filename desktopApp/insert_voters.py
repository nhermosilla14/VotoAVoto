import sys
import csv
import json
from mysql.connector import MySQLConnection, Error

def main():
    if len(sys.argv) != 3:
        print("Uso: insert_voters PATH_TO_CREDENTIALS_JSON PATH_TO_VOTERS_FILE\n\ ")
    else:
        with open(sys.argv[1]) as f:
            creds = json.load(f)
        try:
            votersFile = open(sys.argv[2], 'r')
            voters_csv = csv.reader(votersFile)
            # query blob data form the authors table
            conn = MySQLConnection(host='www.cee-elo.cl',
                                    user=creds["db_user"],
                                    password=creds["db_pass"],
                                    db='ceeelocl_tricel2020',
                                    charset='utf8',
                                    collation='utf8_unicode_ci')
            cursor = conn.cursor()
            for row in voters_csv:
                cursor.execute("INSERT INTO `nomina` (`rol`) VALUES (\""+row[0].upper()+"\")")
                conn.commit()
            cursor.close()
        except Error as e:
            print(e)
        finally:
            votersFile.close()
            conn.close()
    sys.exit(0)

if __name__ == '__main__':
    main()
