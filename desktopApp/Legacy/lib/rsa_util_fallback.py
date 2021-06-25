import rsa

def encrypt_bytes(public_key_file_path, bytes_to_encrypt):
    public_key_file = open(public_key_file_path, 'rb')
    public_key = rsa.PublicKey.load_pkcs1_openssl_pem(public_key_file.read())
    crypto = rsa.encrypt(bytes_to_encrypt, public_key)
    return crypto

def decrypt_bytes(private_key_file_path, bytes_to_decrypt):
    private_key_file = open(private_key_file_path, 'rb')
    private_key = rsa.PrivateKey.load_pkcs1(private_key_file.read())
    clear_msg = rsa.decrypt(bytes_to_decrypt, private_key)
    return clear_msg

