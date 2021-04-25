#!/usr/bin/env python
import sys
import json
from mysql.connector import MySQLConnection, Error
from lib.libbvf import *
from Crypto.Hash import SHA256

def write_bin(data, filename):
    with open(filename, 'wb') as f:
        f.write(data)

def write_str(str, filename):
    with open(filename, 'w') as f:
        f.write(str)

def main():
    if len(sys.argv) != 3:
        print("VoteApp-Counter v0.1\nUso: VotoApp-cli PATH_TO_CREDENTIALS_JSON PATH_TO_RSA_PRIVATE_KEY\n\ ")
    else:
        with open(sys.argv[1]) as f:
            creds = json.load(f)
        try:
            # query blob data form the authors table
            query = "SELECT `ID`, `Voto` FROM `urna`"
            conn = MySQLConnection(host='www.cee-elo.cl',
                                    user=creds["db_user"],
                                    password=creds["db_pass"],
                                    db='ceeelocl_tricel2020',
                                    charset='utf8',
                                    collation='utf8_unicode_ci')
            cursor = conn.cursor()
            cursor.execute(query)
            for (id, vote) in cursor:
                h = SHA256.new(vote)
                hash = h.hexdigest()
                write_bin(vote, 'votes/encrypted/'+hash+'.bvf')
                mivoto = readFromFile('votes/encrypted/'+hash+'.bvf', sys.argv[2])
                write_str(mivoto.toJson(), 'votes/decrypted/'+hash+'.json')
            cursor.close()
        except Error as e:
            print(e)
        finally:
            conn.close()
    sys.exit(0)

if __name__ == '__main__':
    main()
