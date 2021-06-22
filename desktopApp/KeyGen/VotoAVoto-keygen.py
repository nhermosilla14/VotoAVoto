import sys
from lib.votoavoto_keygen_ui import *
from Crypto.PublicKey import RSA
from PyQt5 import QtWidgets, uic

class KeygenApp(QtWidgets.QMainWindow):
    def __init__(self):
        super(KeygenApp, self).__init__()
        self.ui = Ui_MainWindow()
        self.ui.setupUi(self)
        self.ui.pushButtonDST.clicked.connect(self.selectRSADir)
        self.ui.pushButton.clicked.connect(self.genKeys)

    def selectRSADir(self):
        self.ui.lineEditDST.setText(QtWidgets.QFileDialog.getExistingDirectory(\
                QtWidgets.QFileDialog(), "Elige la carpeta para guardar las claves"))

    def genKeys(self):
        private_file = open(self.ui.lineEditDST.text() + "/private.pem", "wb")
        public_file = open(self.ui.lineEditDST.text() + "/public.pem", "wb")
        key = RSA.generate(2048)
        private_file.write(key.export_key('PEM'))
        private_file.close()
        public_file.write(key.public_key().export_key('PEM'))
        public_file.close()
        modulus = str(hex(key.public_key().n)).upper()[2:]
        exponent = str(hex(key.e)).upper()[2:]
        text_output = "Tus claves han sido generadas. Usa estos datos:\n"
        text_output += "modulus: "+modulus+"\n"
        text_output += "exponent: "+exponent+"\n"
        text_output += "bit_size: "+str(2048)
        self.ui.textEdit.setText(text_output)

if __name__ == '__main__':
    app = QtWidgets.QApplication(sys.argv)
    window = KeygenApp()
    window.show()
    sys.exit(app.exec_())
