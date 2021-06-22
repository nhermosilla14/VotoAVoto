from Crypto.Cipher import AES
from Crypto.Random import get_random_bytes
from Crypto.Util.Padding import pad

class Voto:
    def __init__(self, main='', free=''):
        self.main = main
        self.free = free
        self.ciphered_data = b''
        self.tag = b''
        self.nonce = b''
        self.key = b''
    
    def setKey(self, key_string=''):
        if not key_string:
            self.key = get_random_bytes(16)
        else:
            self.key = key_string.encode('utf-8')

    def encryptVote(self):
        if not self.key:
            self.setKey()
        cipher = AES.new(self.key, AES.MODE_EAX)
        self.nonce = cipher.nonce
        msg = str(get_random_bytes(32))+';'+self.main+';'+self.free
        data = msg.encode('utf-8')
        self.ciphered_data, self.tag = cipher.encrypt_and_digest(data)
    
    def decryptVote(self):
        cipher = AES.new(self.key, AES.MODE_EAX, self.nonce)
        data = cipher.decrypt_and_verify(self.ciphered_data, self.tag)
        msg = data.decode('utf-8')
        self.main = msg.split(';')[1]
        self.free = msg.split(';')[2]

    def toJson(self):
        return str({"opt": self.main.strip(), "msg": self.free.strip()})
