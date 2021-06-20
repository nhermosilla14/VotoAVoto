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

    getChoice(number){
        var choice = "";
        if (document.getElementById("checkbox" + this.leadZeros(number) + "01").checked){
            choice = "01";
        }
        if (document.getElementById("checkbox" + this.leadZeros(number) + "02").checked){
            if (choice !== ""){
                choice = "N";
            } else {
                choice = "02";
            }
        }
        if (choice === "")
            choice = "00";
        return choice;
    }
    
    leadZeros(number){
        if (number < 10){
            return "0"+number;
        }else{
            return number;
        }
    }


    getMain(){
        for (var i=1; i <= 5; i++){
            this.voto.main += this.leadZeros(i)+"."+this.getChoice(i)+",";
        }
        this.voto.main = this.voto.main.substring(0, this.voto.main.length - 1);
        console.log(this.voto.main);
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

