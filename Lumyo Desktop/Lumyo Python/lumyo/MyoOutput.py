# Coded by Walker Argendeli, Lumyo Capstone Group

from __future__ import print_function

from lumyo.Listener import Observer
from lumyo import MyoREST, MyoConfig

from abc import ABCMeta, abstractmethod
from collections import namedtuple, deque
import os
import csv
import time

DataType = namedtuple('DataType', ['EMG', 'GYRO', 'ORIENT', 'ACCEL'])
_DataTypeName = {getattr(DataType,fName) : fName for fName in DataType._fields}

class MyoOutput(Observer):
    __metaclass__ = ABCMeta
    
    @abstractmethod
    def _output(self, dataType, timestamp, data):
        pass
    
    @abstractmethod
    def notify(self, dataType, timestamp, data):
        pass
        
class ConsoleOutput(MyoOutput):
    
    _DataTypeMethod = {dataType : lambda timestamp, data : self._outputLine(dataTypeName, timestamp, data) for dataType, dataTypeName in _DataTypeName.iteritems()}
    
    def __init__(self, interval=0.05):
        self.interval = interval
        self._lastTime = None
    
    def _outputLine(dataTypeName, timestamp, data):
        print(dataTypeName, ":\t", timestamp, "\t[", sep="", end="")
        print(*data, end="")
        print("]")
    
    def _output(self, dataType, timestamp, data):
        self._DataTypeMethod[dataType](timestamp, data)
    
    def notify(self, dataType, timestamp, data):
        if self._lastTime and (timestamp - self._lastTime < self.interval):
            return
        self._output(dataType, timestamp, data)
        self._last_time = currTime
    
class FileOutput(MyoOutput):
    
    def __init__(self, dataDir=MyoConfig.dataDir):
        self.dataDir = dataDir
        self._setup = False
    
    def _setupOutput(self, timestamp=None):
        if not timestamp:
            timestamp = int(time.time())
        timestamp = str(timestamp)
        sessionPostfix = '-' + timestamp
        try:
            if not self.dataDir:
                self.dataDir = './data'
            self.dataDir += '/' + timestamp
            if not os.path.isdir(self.dataDir):
                os.makedirs(self.dataDir)
            os.chdir(self.dataDir)
            
            # self.metadataFile = open('metadata' + sessionPostfix + '.json')
            
            self._emgFile = open('emg' + sessionPostfix + '.csv', "wb")
            self._gyroFile = open('gyro' + sessionPostfix + '.csv', "wb")
            self._orientFile = open('orientation' + sessionPostfix + '.csv', "wb")
            self._accelFile = open('accelerometer' + sessionPostfix + '.csv', "wb")
            
            self._emgWriter = csv.writer(self._emgFile, delimiter=',', quotechar='"', quoting=csv.QUOTE_NONE)
            self._gyroWriter = csv.writer(self._gyroFile, delimiter=',', quotechar='"', quoting=csv.QUOTE_NONE)
            self._orientWriter = csv.writer(self._orientFile, delimiter=',', quotechar='"', quoting=csv.QUOTE_NONE)
            self._accelWriter = csv.writer(self._accelFile, delimiter=',', quotechar='"', quoting=csv.QUOTE_NONE)
            
            self._emgWriter.writerow(["timestamp", "emg1", "emg2", "emg3", "emg4", "emg", "emg6", "emg7", "emg8"])
            self._gyroWriter.writerow(["timestamp", "x", "y", "z"])
            self._orientWriter.writerow(["timestamp", "x", "y", "z", "w"])
            self._accelWriter.writerow(["timestamp", "x", "y", "z"])
            
            self._DataTypeMethod = {
                DataType.EMG      :   self._emgWriter.writerow,
                DataType.GYRO     :   self._gyroWriter.writerow,
                DataType.ORIENT   :   self._orientWriter.writerow,
                DataType.ACCEL    :   self._accelWriter.writerow
            }
            
            self._setup = True
        except:
            raise
    
    def _output(self, dataType, timestamp, data):
        self._DataTypeMethod[dataType]([timestamp] + data)
    
    def tearDown(self):
        if self._setup:
            try:
                if self._emgFile:
                    self._emgFile.close()
                if self._gyroFile:
                    self._gyroFile.close()
                if self._orientFile:
                    self._orientFile.close()
                if self._orientEulFile:
                    self._orientEulFile.close()
                if self._accelFile:
                    self._accelFile.close()
                    
                self._setup = False
            except:
                pass
    
    def notify(self, dataType, timestamp, data):
        if not self._setup:
            self._setupOutput(timestamp)
        self._output(dataType, timestamp, data)

class ServerDBOutput(MyoOutput):
    
    def __init__(self, username=MyoConfig.dbUsername, password=MyoConfig.dbPassword, sessionType=MyoREST.Session.SessionType.SLEEP):
        self._connection = MyoREST.Connection(username, password)
        self._session = self._connection.openNewSession(sessionType)
        
        self._DataTypeMethod = {
            DataType.EMG      :   self._session.addEMGDataPoint,
            DataType.GYRO     :   self._session.addGyroDataPoint,
            DataType.ORIENT   :   self._session.addOrientDataPoint,
            DataType.ACCEL    :   self._session.addAccelDataPoint
        }
        
        self._setup = False
    
    def _output(self, dataType, timestamp, data):
        self._DataTypeMethod[dataType](timestamp, data)
    
    def notify(self, dataType, timestamp, data):
        if not self._setup:
            self._session.initialTimestamp = timestamp
            self._setup = True
        self._output(dataType, timestamp, data)
        
class SampledServerDBOutput(ServerDBOutput):
    
    @staticmethod
    def _microsecondsFromSeconds(secs):
        return int(secs * float(1E6))
    
    def __init__(self, offsetInSecs=0.5):
        super(SampledServerDBOutput, self).__init__(MyoConfig.dbUsername, MyoConfig.dbPassword, MyoREST.Session.SessionType.SLEEP)
        self._offset = SampledServerDBOutput._microsecondsFromSeconds(offsetInSecs)
        self._lastTimestampUploaded = {dataType: float("-inf") for dataType in _DataTypeName.keys()}
    
    def _output(self, dataType, timestamp, data):
        if timestamp - self._offset > self._lastTimestampUploaded[dataType]:
            self._lastTimestampUploaded[dataType] = timestamp
            return super(SampledServerDBOutput, self)._output(dataType, timestamp, data)