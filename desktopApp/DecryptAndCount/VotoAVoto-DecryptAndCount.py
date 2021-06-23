#!/usr/bin/env python3

import sys
import glob
from PyQt5 import QtWidgets
from PyQt5.QtCore import QTimer
from Crypto.Cipher import PKCS1_v1_5
from Crypto.PublicKey import RSA
from Crypto.Hash import SHA
from Crypto import Random
from lib.libbvf import *
from lib.voto import *
from lib.extra import *
from lib.VotoApp_decrypter_ui import *
import json

class VotoApp(QtWidgets.QMainWindow):
    def __init__(self):
        super(VotoApp, self).__init__()
        self.ui = Ui_VotoApp()
        self.ui.setupUi(self)
        self.ui.pushButtonPause.clicked.connect(self.toggleTimer)
        self.ui.pushButtonNext.clicked.connect(self.forceReadNextVote)
        self.ui.progressBar.setValue(0)
        self.ui.topImg.setText(topImg)
        self.error_dialog = QtWidgets.QErrorMessage()
        self.timerStatus = False
        self.ui.pushButtonPause.setText("Iniciar")
        self.timeout = 5
        self.countdown = self.timeout
        self.ui.spinBoxTimeout.setValue(5)
        self.ui.spinBoxTimeout.valueChanged.connect(self.setTimeout)
        self.timer=QTimer()
        self.timer.timeout.connect(self.updateNextLabel)
        with open("config.json") as f:
            self.config = json.load(f)
        self.pathToPEM = self.config['pathToPEM']
        self.pathToVotes = self.config['pathToVotes']
        self.dir_files = glob.glob(self.pathToVotes + "/*.bvf")
        self.counts = dict()
        self.resetCounters()
    
    def toggleTimer(self):
        if self.timerStatus:
            self.timerStatus = False
            self.countdown = 0
            self.ui.pushButtonPause.setText("Continuar")
            self.ui.pushButtonNext.setText("Siguiente")
            self.timer.stop()
        else:
            self.timerStatus = True
            self.countdown = self.timeout
            self.ui.pushButtonNext.setText("Siguiente ("+str(self.timeout)+")")
            self.ui.pushButtonPause.setText("Detener")
            self.timer.start(1000)

    def setTimeout(self):
        self.timeout = self.ui.spinBoxTimeout.value()

    def updateNextLabel(self):
        if self.countdown == 1:
            self.readNextVote()
            self.countdown = self.timeout
        else:
            self.countdown -= 1
        self.ui.pushButtonNext.setText("Siguiente ("+str(self.countdown)+")")


    def updateCurVote(self):
        final_text = ""
        subvotes = self.voto.main.split(",")
        for vote in subvotes:
            title,opt = vote.split(".")
            final_text += "==== "
            final_text += self.config["subvotes"][title]["Title"]
            final_text += " ====\n"
            final_text += "Opción: "
            final_text += self.config["subvotes"][title][opt]+"\n"
        final_text += "Notas al margen: "
        final_text += self.voto.free + "\n"
        self.ui.textEditVoto.setText(final_text)


    def decodeVote(self):
        print(self.dir_files[0])
        try:
            self.voto = readFromFile(self.dir_files[0], self.pathToPEM)
            self.subvotes = self.voto.main.split(",")
        except:
            for subvote in self.config["subvotes"].keys():
                self.subvotes.append(str(subvote)+"."+"00")

    def forceReadNextVote(self):
        self.readNextVote()
        self.countdown = self.timeout

    def readNextVote(self):
        if len(self.dir_files) > 0:
            self.decodeVote()
            for subvote in self.subvotes:
                key, opt = subvote.split(".")
                self.counts[key][opt] += 1
            self.counted += 1
            self.dir_files.remove(self.dir_files[0])
            self.updateCurVote()
            self.updateCounters()
        else:
            self.ui.bottomMessage.setText("No quedan más votos")
            if self.timerStatus:
                self.toggleTimer()

    def resetCounters(self):
        self.total = len(self.dir_files)
        self.counted = 0
        for i in range(5):
            self.counts["0"+str(i+1)] = dict()
            self.counts["0"+str(i+1)]["01"] = 0
            self.counts["0"+str(i+1)]["02"] = 0
            self.counts["0"+str(i+1)]["N"] = 0
            self.counts["0"+str(i+1)]["00"] = 0


    def updateCounters(self):
        self.ui.lcdNumber0101.display(self.counts["01"]["01"])
        self.ui.lcdNumber0102.display(self.counts["01"]["02"])
        self.ui.lcdNumber0103.display(self.counts["01"]["N"])
        self.ui.lcdNumber0104.display(self.counts["01"]["00"])
        self.ui.lcdNumber0201.display(self.counts["02"]["01"])
        self.ui.lcdNumber0202.display(self.counts["02"]["02"])
        self.ui.lcdNumber0203.display(self.counts["02"]["N"])
        self.ui.lcdNumber0204.display(self.counts["02"]["00"])
        self.ui.lcdNumber0301.display(self.counts["03"]["01"])
        self.ui.lcdNumber0302.display(self.counts["03"]["02"])
        self.ui.lcdNumber0303.display(self.counts["03"]["N"])
        self.ui.lcdNumber0304.display(self.counts["03"]["00"])
        self.ui.lcdNumber0401.display(self.counts["04"]["01"])
        self.ui.lcdNumber0402.display(self.counts["04"]["02"])
        self.ui.lcdNumber0403.display(self.counts["04"]["N"])
        self.ui.lcdNumber0404.display(self.counts["04"]["00"])
        self.ui.lcdNumber0501.display(self.counts["05"]["01"])
        self.ui.lcdNumber0502.display(self.counts["05"]["02"])
        self.ui.lcdNumber0503.display(self.counts["05"]["N"])
        self.ui.lcdNumber0504.display(self.counts["05"]["00"])
        self.ui.lineEditTotal.setText(str(self.total))
        self.ui.lineEditCount.setText(str(self.counted))
        self.progress = self.counted/self.total
        self.ui.progressBar.setValue(round(100*self.progress))


if __name__ == '__main__':
    app = QtWidgets.QApplication(sys.argv)
    window = VotoApp()
    window.show()
    sys.exit(app.exec_())
