#!/usr/bin/env python
# v1.5.1
import sys
from PyQt5 import QtWidgets, uic
from lib.voto import *
from lib.libbvf import *

class VotoApp(QtWidgets.QMainWindow):
    def __init__(self):
        super(VotoApp, self).__init__()
        uic.loadUi('ui/voto-tricelo-main.ui', self)
        self.pushButtonPEM.clicked.connect(self.selectPEMFile)
        self.pushButtonDST.clicked.connect(self.selectDSTFile)
        self.pushButtonGen.clicked.connect(self.generateVote)
        self.error_dialog = QtWidgets.QErrorMessage()
        if sys.platform == 'win32':
            self.lineEditPEM.setText('rsa\\public.pem')
        else:
            self.lineEditPEM.setText('rsa/public.pem')

    def selectPEMFile(self):
        self.lineEditPEM.setText(QtWidgets.QFileDialog.getOpenFileName(QtWidgets.QFileDialog(), "Elige el archivo de clave pública", ".", "PEM (*.pem)" )[0]) 

    def selectDSTFile(self):
        self.lineEditDST.setText(QtWidgets.QFileDialog.getSaveFileName(QtWidgets.QFileDialog(), "Guardar voto como", ".", "Binary Vote Format (*.bvf)")[0])
        if self.lineEditDST.text().split('.')[-1] != 'bvf':
            if self.lineEditDST.text() != '':
                self.lineEditDST.setText(self.lineEditDST.text() + '.bvf')

    def writeOutputFile(self):
        try:
            writeToFile(self.lineEditDST.text(), self.lineEditPEM.text(), self.voto)
        except:
            self.error_dialog.showMessage('Ingresa un nombre de archivo válido para guardar tu voto.')

    def initVoto(self):
        self.voto = Voto()
        self.voto.setKey()

    def getMain(self):
        self.voto.main = ' '
        if (self.checkBoxApprove.isChecked()):
            self.voto.main += 'apruebo'
        if (self.checkBoxReject.isChecked()):
            self.voto.main += 'rechazo'    

    def getFree(self):
        self.voto.free = ' '
        self.voto.free += self.lineEditFree.text()
        
    def tryCipheredSecret(self):
        try:
            encrypt_bytes(self.lineEditPEM.text(), b'test')
        except:
            self.error_dialog.showMessage('Verifica que hayas elegido correctamente la clave pública.')

    def generateVote(self):
        try:
            self.initVoto()
            self.getMain()
            self.getFree()
            self.voto.encryptVote()
            self.tryCipheredSecret()
            self.writeOutputFile()
            self.labelStatus.setText("Voto generado exitosamente")
        except:
            self.labelStatus.setText("No se pudo generar el voto")

if __name__ == '__main__':
    app = QtWidgets.QApplication(sys.argv)
    window = VotoApp()
    window.show()
    sys.exit(app.exec_())
