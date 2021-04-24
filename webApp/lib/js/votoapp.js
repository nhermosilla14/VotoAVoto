class VotoApp {
    constructor(){
    }
    getOutputFile(){
        if (!document.getElementById("bvfLocalFile").value){
            this.output_file = "voto.bvf";
        } else {
            this.output_file = document.getElementById("bvfLocalFile").value + ".bvf";
        }
    }
    initVoto(){
        this.voto = new Voto();
    }

    getMain(){
        this.voto.main = ' ';
        if (document.getElementById("checkboxApruebo").checked){
            this.voto.main += "apruebo";
        }
        if (document.getElementById("checkboxRechazo").checked){
            this.voto.main += "rechazo";
        }
    }

    getFree(){
        this.voto.free = ' ';
        this.voto.free += document.getElementById("free").value;
    }

    getCipheredSecret(){
        this.ciphered_secret = encrypt_bytes(this.secret);
    }
    
    generateVote(){
        this.initVoto();
        this.getMain();
        this.getFree();
        this.voto.encryptVote();
        this.secret = this.voto.key+this.voto.nonce;
        this.getCipheredSecret();
        this.getOutputFile();
        download(this.ciphered_secret+this.voto.ciphered_data, this.output_file, "application/octet-stream");
    }

}

