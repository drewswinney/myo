# Coded by Walker Argendeli, Lumyo Capstone Group

import sys
from ConfigParser import SafeConfigParser

sys.dont_write_bytecode = True

def parseConfig():
    configParser = SafeConfigParser()
    configParser.read('MyoConfig.ini')
    
    global consoleOutput, fileOutput, dbOutput, dataDir, dbUsername, dbPassword
    
    consoleOutput = configParser.getboolean('OutputOptions', 'Console')
    fileOutput = configParser.getboolean('OutputOptions', 'Files')
    dbOutput = configParser.getboolean('OutputOptions', 'DB')
    
    dataDir = configParser.get('OutputOptions', 'DataDir')
    
    dbUsername = configParser.get('DBLogin', 'Username')
    dbPassword = configParser.get('DBLogin', 'Password')

    # configSection = 'OutputOptions'
    # if configParser.hassection(configSection):
    #     configOption = 'Console'
    #     if configParser.hasoption('Console'):
    #         configParser.get(configSection, configOption)

consoleOutput = False
fileOutput = False
dbOutput = False

dataDir = None

dbUsername = None
dbPassword = None

parseConfig()