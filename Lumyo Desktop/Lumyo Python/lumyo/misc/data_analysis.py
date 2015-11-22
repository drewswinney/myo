# Coded by Walker Argendeli, Lumyo Capstone Group

from __future__ import print_function

import os
from Tkinter import Tk
from tkFileDialog import askdirectory

import pandas as pd
import matplotlib as mpl

from timeit import timeit

class DataAnalyzer():
    
    def __init__(self):
        emgData = None
        gyroData = None
        orientData = None
        orientEulData = None
        accelData = None

    def loadSessionFromFiles(self, dataDir=None):
        
        def readFileData(data_dir, file_prefix):
            timestamp = os.path.basename(data_dir)
            session_id = '-' + timestamp
            filePath = data_dir + '/' + file_prefix + session_id +'.csv'
            fileData = pd.read_csv(filePath)
            return fileData
        
        if not dataDir:
            Tk().withdraw() # we don't want a full GUI, so keep the root window from appearing
            dataDir = askdirectory(initialdir=dataDir) # show an "Open" dialog box and return the path to the selected file
        
        self.emgData = readFileData(dataDir, 'emg')
        self.gyroData = readFileData(dataDir, 'gyro')
        self.orientData = readFileData(dataDir, 'orientation')
        self.orientEulData = readFileData(dataDir, 'orientationEuler')
        self.accelData = readFileData(dataDir, 'accelerometer')
        
    def loadSessionFromDB():
        pass

def main():
    smallDataDir = "./data/1445955549"
    largeDataDir = "/Users/wa/Documents/School/Fall 2015/CS 4911 (Capstone)/Code, etc./Sensor Data/sleep/10:24-10:25/1445757212"
    
    analyzer = DataAnalyzer()
    analyzer.loadSessionFromFiles(largeDataDir)
    
    print(analyzer.orientEulData.roll[0])

if __name__ == "__main__":
    # main()
    print("\nProgram executed in", round(timeit("main()", number=1, setup="from __main__ import main"), 2), "s")