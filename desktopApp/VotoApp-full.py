#!/usr/bin/env python
# v1.5.1
import sys
from PyQt5 import QtCore, QtGui, QtWidgets
from Crypto.Cipher import AES
from Crypto.Random import get_random_bytes
from Crypto.Util.Padding import pad
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

class Ui_VotoApp(object):
    def setupUi(self, VotoApp):
        VotoApp.setObjectName("VotoApp")
        VotoApp.resize(429, 500)
        sizePolicy = QtWidgets.QSizePolicy(QtWidgets.QSizePolicy.Fixed, QtWidgets.QSizePolicy.Fixed)
        sizePolicy.setHorizontalStretch(0)
        sizePolicy.setVerticalStretch(0)
        sizePolicy.setHeightForWidth(VotoApp.sizePolicy().hasHeightForWidth())
        VotoApp.setSizePolicy(sizePolicy)
        VotoApp.setMinimumSize(QtCore.QSize(429, 500))
        VotoApp.setMaximumSize(QtCore.QSize(800, 900))
        self.centralwidget = QtWidgets.QWidget(VotoApp)
        self.centralwidget.setObjectName("centralwidget")
        self.gridLayoutWidget = QtWidgets.QWidget(self.centralwidget)
        self.gridLayoutWidget.setGeometry(QtCore.QRect(60, 300, 301, 121))
        self.gridLayoutWidget.setObjectName("gridLayoutWidget")
        self.gridLayout = QtWidgets.QGridLayout(self.gridLayoutWidget)
        self.gridLayout.setSizeConstraint(QtWidgets.QLayout.SetNoConstraint)
        self.gridLayout.setContentsMargins(0, 0, 0, 0)
        self.gridLayout.setObjectName("gridLayout")
        self.label_2 = QtWidgets.QLabel(self.gridLayoutWidget)
        self.label_2.setMinimumSize(QtCore.QSize(20, 0))
        self.label_2.setWordWrap(True)
        self.label_2.setObjectName("label_2")
        self.gridLayout.addWidget(self.label_2, 1, 0, 1, 1)
        self.lineEditFree = QtWidgets.QLineEdit(self.gridLayoutWidget)
        self.lineEditFree.setText("")
        self.lineEditFree.setObjectName("lineEditFree")
        self.gridLayout.addWidget(self.lineEditFree, 1, 1, 1, 1)
        self.pushButtonGen = QtWidgets.QPushButton(self.gridLayoutWidget)
        self.pushButtonGen.setObjectName("pushButtonGen")
        self.gridLayout.addWidget(self.pushButtonGen, 3, 1, 1, 1)
        self.lineEditDST = QtWidgets.QLineEdit(self.gridLayoutWidget)
        self.lineEditDST.setObjectName("lineEditDST")
        self.gridLayout.addWidget(self.lineEditDST, 2, 1, 1, 1)
        self.label_4 = QtWidgets.QLabel(self.gridLayoutWidget)
        self.label_4.setWordWrap(True)
        self.label_4.setObjectName("label_4")
        self.gridLayout.addWidget(self.label_4, 2, 0, 1, 1)
        self.pushButtonDST = QtWidgets.QPushButton(self.gridLayoutWidget)
        self.pushButtonDST.setObjectName("pushButtonDST")
        self.gridLayout.addWidget(self.pushButtonDST, 2, 2, 1, 1)
        self.label_6 = QtWidgets.QLabel(self.gridLayoutWidget)
        self.label_6.setAlignment(QtCore.Qt.AlignCenter)
        self.label_6.setWordWrap(True)
        self.label_6.setObjectName("label_6")
        self.gridLayout.addWidget(self.label_6, 1, 2, 1, 1)
        self.label_5 = QtWidgets.QLabel(self.centralwidget)
        self.label_5.setGeometry(QtCore.QRect(0, 0, 421, 141))
        self.label_5.setObjectName("label_5")
        self.labelStatus = QtWidgets.QLabel(self.centralwidget)
        self.labelStatus.setGeometry(QtCore.QRect(0, 430, 431, 20))
        self.labelStatus.setAlignment(QtCore.Qt.AlignCenter)
        self.labelStatus.setWordWrap(True)
        self.labelStatus.setObjectName("labelStatus")
        self.horizontalLayoutWidget = QtWidgets.QWidget(self.centralwidget)
        self.horizontalLayoutWidget.setGeometry(QtCore.QRect(50, 150, 331, 41))
        self.horizontalLayoutWidget.setObjectName("horizontalLayoutWidget")
        self.horizontalLayout = QtWidgets.QHBoxLayout(self.horizontalLayoutWidget)
        self.horizontalLayout.setContentsMargins(0, 0, 0, 0)
        self.horizontalLayout.setObjectName("horizontalLayout")
        self.label_3 = QtWidgets.QLabel(self.horizontalLayoutWidget)
        self.label_3.setMinimumSize(QtCore.QSize(20, 0))
        self.label_3.setObjectName("label_3")
        self.horizontalLayout.addWidget(self.label_3)
        self.lineEditPEM = QtWidgets.QLineEdit(self.horizontalLayoutWidget)
        self.lineEditPEM.setObjectName("lineEditPEM")
        self.horizontalLayout.addWidget(self.lineEditPEM)
        self.pushButtonPEM = QtWidgets.QPushButton(self.horizontalLayoutWidget)
        self.pushButtonPEM.setObjectName("pushButtonPEM")
        self.horizontalLayout.addWidget(self.pushButtonPEM)
        self.verticalLayoutWidget = QtWidgets.QWidget(self.centralwidget)
        self.verticalLayoutWidget.setGeometry(QtCore.QRect(30, 200, 361, 80))
        self.verticalLayoutWidget.setObjectName("verticalLayoutWidget")
        self.verticalLayout = QtWidgets.QVBoxLayout(self.verticalLayoutWidget)
        self.verticalLayout.setContentsMargins(0, 0, 0, 0)
        self.verticalLayout.setObjectName("verticalLayout")
        self.label = QtWidgets.QLabel(self.verticalLayoutWidget)
        self.label.setWordWrap(True)
        self.label.setObjectName("label")
        self.verticalLayout.addWidget(self.label)
        self.horizontalLayout_2 = QtWidgets.QHBoxLayout()
        self.horizontalLayout_2.setObjectName("horizontalLayout_2")
        spacerItem = QtWidgets.QSpacerItem(40, 20, QtWidgets.QSizePolicy.Expanding, QtWidgets.QSizePolicy.Minimum)
        self.horizontalLayout_2.addItem(spacerItem)
        self.checkBoxApprove = QtWidgets.QCheckBox(self.verticalLayoutWidget)
        self.checkBoxApprove.setObjectName("checkBoxApprove")
        self.horizontalLayout_2.addWidget(self.checkBoxApprove)
        spacerItem1 = QtWidgets.QSpacerItem(40, 20, QtWidgets.QSizePolicy.Expanding, QtWidgets.QSizePolicy.Minimum)
        self.horizontalLayout_2.addItem(spacerItem1)
        self.checkBoxReject = QtWidgets.QCheckBox(self.verticalLayoutWidget)
        self.checkBoxReject.setObjectName("checkBoxReject")
        self.horizontalLayout_2.addWidget(self.checkBoxReject)
        spacerItem2 = QtWidgets.QSpacerItem(40, 20, QtWidgets.QSizePolicy.Expanding, QtWidgets.QSizePolicy.Minimum)
        self.horizontalLayout_2.addItem(spacerItem2)
        self.verticalLayout.addLayout(self.horizontalLayout_2)
        VotoApp.setCentralWidget(self.centralwidget)

        self.retranslateUi(VotoApp)
        QtCore.QMetaObject.connectSlotsByName(VotoApp)

    def retranslateUi(self, VotoApp):
        _translate = QtCore.QCoreApplication.translate
        VotoApp.setWindowTitle(_translate("VotoApp", "VotoApp"))
        self.label_2.setText(_translate("VotoApp", "Notas al margen"))
        self.pushButtonGen.setText(_translate("VotoApp", "Generar voto"))
        self.label_4.setText(_translate("VotoApp", "Ubicación de destino"))
        self.pushButtonDST.setText(_translate("VotoApp", "Guardar en..."))
        self.label_6.setText(_translate("VotoApp", "(opcional)"))
        self.label_5.setText(_translate("VotoApp", "<html><head/><body><p align=\"center\"><img src=\"data:image/png;base64,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\"/></p></body></html>"))
        self.labelStatus.setText(_translate("VotoApp", "Rellena esta papeleta y deposita tu voto en nuestra urna virtual."))
        self.label_3.setText(_translate("VotoApp", "Clave pública"))
        self.pushButtonPEM.setText(_translate("VotoApp", "Elegir..."))
        self.label.setText(_translate("VotoApp", "<html><head/><body><p align=\"center\"><span style=\" font-weight:600;\">¿Quieres que la Lista A: Ampliando Electrónica</span></p><p align=\"center\"><span style=\" font-weight:600;\">sea elegida como CEE para el período 2020?</span></p></body></html>"))
        self.checkBoxApprove.setText(_translate("VotoApp", "Apruebo"))
        self.checkBoxReject.setText(_translate("VotoApp", "Rechazo"))

class VotoApp(QtWidgets.QMainWindow):
    def __init__(self):
        super(VotoApp, self).__init__()
        self.ui = Ui_VotoApp()
        self.ui.setupUi(self)
        self.ui.pushButtonPEM.clicked.connect(self.selectPEMFile)
        self.ui.pushButtonDST.clicked.connect(self.selectDSTFile)
        self.ui.pushButtonGen.clicked.connect(self.generateVote)
        self.error_dialog = QtWidgets.QErrorMessage()
        if sys.platform == 'win32':
            self.ui.lineEditPEM.setText('rsa\\public.pem')
        else:
            self.ui.lineEditPEM.setText('rsa/public.pem')

    def selectPEMFile(self):
        self.ui.lineEditPEM.setText(QtWidgets.QFileDialog.getOpenFileName(QtWidgets.QFileDialog(), "Elige el archivo de clave pública", ".", "PEM (*.pem)" )[0]) 

    def selectDSTFile(self):
        self.ui.lineEditDST.setText(QtWidgets.QFileDialog.getSaveFileName(QtWidgets.QFileDialog(), "Guardar voto como", ".", "Binary Vote Format (*.bvf)")[0])
        if self.ui.lineEditDST.text().split('.')[-1] != 'bvf':
            if self.ui.lineEditDST.text() != '':
                self.ui.lineEditDST.setText(self.ui.lineEditDST.text() + '.bvf')

    def writeOutputFile(self):
        try:
            writeToFile(self.ui.lineEditDST.text(), self.ui.lineEditPEM.text(), self.voto)
        except:
            self.error_dialog.showMessage('Ingresa un nombre de archivo válido para guardar tu voto.')

    def initVoto(self):
        self.voto = Voto()
        self.voto.setKey()

    def getMain(self):
        self.voto.main = ' '
        if (self.ui.checkBoxApprove.isChecked()):
            self.voto.main += 'apruebo'
        if (self.ui.checkBoxReject.isChecked()):
            self.voto.main += 'rechazo'    

    def getFree(self):
        self.voto.free = ' '
        self.voto.free += self.ui.lineEditFree.text()
        
    def tryCipheredSecret(self):
        try:
            encrypt_bytes(self.ui.lineEditPEM.text(), b'test')
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
            self.ui.labelStatus.setText("Voto generado exitosamente")
        except:
            self.ui.labelStatus.setText("No se pudo generar el voto")

if __name__ == '__main__':
    app = QtWidgets.QApplication(sys.argv)
    window = VotoApp()
    window.show()
    sys.exit(app.exec_())
