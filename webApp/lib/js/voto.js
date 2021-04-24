class Voto {
    constructor(){
        this.main = '';
        this.free = '';
        this.ciphered_data = '';
        this.nonce = CryptoJS.lib.WordArray.random(16);
        this.key = CryptoJS.lib.WordArray.random(16);
    }
    encryptVote(){
        this.msg = CryptoJS.lib.WordArray.random(32).toString();
        this.msg += ';';
        this.msg += this.main;
        this.msg += ';';
        this.msg += this.free;
        this.cipher = CryptoJS.EAX.create(this.key);
        this.ciphered_data = this.cipher.encrypt(this.msg, this.nonce);
    }
}

