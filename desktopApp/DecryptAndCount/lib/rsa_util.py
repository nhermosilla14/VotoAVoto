from Crypto.Cipher import PKCS1_v1_5
from Crypto.PublicKey import RSA
from Crypto.Hash import SHA
from Crypto import Random

def encrypt_bytes(public_key_file_path, bytes_to_encrypt):
    public_key_file = open(public_key_file_path, 'r')
    public_key = RSA.importKey(public_key_file.read())
    cipher = PKCS1_v1_5.new(public_key)
    return cipher.encrypt(bytes_to_encrypt)

def decrypt_bytes(private_key_file_path, bytes_to_decrypt):
    private_key_file = open(private_key_file_path, 'r')
    private_key = RSA.importKey(private_key_file.read())
    decipher = PKCS1_v1_5.new(private_key)
    sentinel = Random.new().read(SHA.digest_size + 15)
    return decipher.decrypt(bytes_to_decrypt, sentinel)

