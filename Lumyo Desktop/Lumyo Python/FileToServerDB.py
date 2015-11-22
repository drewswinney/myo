# Coded by Walker Argendeli, Lumyo Capstone Group

from __future__ import print_function

import sys
sys.dont_write_bytecode = True

import os
import csv
import glob
import datetime

from lumyo.MyoInterface import initialize_myo, MyoError, shutdown_myo
import lumyo.MyoOutput as MyoOutput

def main(fileDataDir):
    print("Welcome to Lumyo Desktop!  Press Ctrl-C to quit.")
    
    dbOutput = MyoOutput.SampledServerDBOutput()
    
    print("Starting transfer from files to server...")
    timestamp = fileDataDir.rsplit('/',1)[1].strip()
    sessionPostfix = '-' + timestamp
    
    os.chdir(fileDataDir)
    
    lastModTime = os.path.getmtime('emg' + sessionPostfix + '.csv') # TODO Super kludge-y: gets last modified time of one of the files as the arbitrary session start
    dbOutput._session.initialTimestamp = int(timestamp)
    dbOutput._session.sessionStartTime = datetime.datetime.fromtimestamp(lastModTime)
    
    for fileDataType, dataTypeName in MyoOutput._DataTypeName.iteritems():
        filePrefix = dataTypeName.lower()
        file = glob.glob(filePrefix + '*' + sessionPostfix + '.csv')[0]
        with open(file, 'rb') as dataFile:
            dataReader = csv.reader(dataFile, delimiter=',', quotechar='"', quoting=csv.QUOTE_NONE)
            next(dataReader, None)
            for i, row in enumerate(dataReader):
                if i == 0:
                    firstRow = row
                lastRow = row
                dbOutput._output(fileDataType, int(row[0]), row[1:])
            if filePrefix == MyoOutput._DataTypeName['EMG']:
                dbOutput._session.sessionStartTime = firstRow[0]
                dbOutput._session.sessionEndTime = lastRow[0]
                dbOutput._sesson.update()
                
    print("\nAll done!")
    
    print("Goodbye")

if __name__ == "__main__":
    main(sys.argv[1])

#TODO NEED TO FIX GETTING PROPER SESSION TIMESTAMP INSTEAD OF CURRENT TIME (NEED TO MODIFY HOW SESSIONS ARE INSTANTIATED)