from lib.rsa_util import *
from lib.hex_util import *
from lib.voto import *

def readFromFile(pathtofile, privkey_file):
    input_file = open(pathtofile, 'rb')
    secret = input_file.read(256)
    unsecret = recoverBytes(decrypt_bytes(privkey_file, secret))
    myVote = Voto()
    myVote.key = unsecret[0:16]
    myVote.nonce = unsecret[16:32]
    myVote.ciphered_data = recoverBytes(input_file.read())
    myVote.tag = myVote.ciphered_data[-16:]
    myVote.ciphered_data = myVote.ciphered_data[0:-16]
    myVote.decryptVote()
    return myVote

def writeToFile(pathtofile, pubkey_file, myVote):
    output_file = open(pathtofile, 'wb')
    secret = myVote.key.hex()
    secret += myVote.nonce.hex()
    output_file.write(encrypt_bytes(pubkey_file, secret.encode()))
    output_file.close()
    output_file = open(pathtofile, 'a')
    output_file.write(myVote.ciphered_data.hex())
    output_file.write(myVote.tag.hex())
    output_file.close()

