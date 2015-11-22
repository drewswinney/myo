# Coded by Walker Argendeli, Lumyo Capstone Group

from urlparse import urlparse, urljoin
from datetime import datetime, timedelta
import requests, json
from getpass import getpass
from enum import Enum, IntEnum
from collections import namedtuple

# class API:
#
#         def __init__(self, low_myo, timestamp, firmware_version):
#             super(DataCaptureListener.MyoProxy, self).__init__()
#             self._connect_time = None
#
#         @property
#         def connected(self):
#             with self.synchronized:
#                 return (
#                     self._connect_time is not None and
#                     self._disconnect_time is None
#                 )



_apiURL = "http://drewswinney.com:8080/api/"

_requestSuccessful = 200

def _apiParams(**params):
    return params

# It turns out that classes cannot have static function pointers; they'll be converted to unbound methods >< dumb
# class RequestMethod(Enum):
#     GET=requests.get
#     POST=requests.post

_RequestMethods = namedtuple('RequestMethod', ['GET', 'POST', 'PUT'])

_RequestMethod = {
    _RequestMethods.GET     :   requests.get,
    _RequestMethods.POST    :   requests.post
    _RequestMethods.PUT     :   requests.put
}

def _apiRequest(reqURL, reqParams, reqMethod):
    response = reqMethod(reqURL, reqParams) # auth=('user', 'pw')
    requestStatus = response.status_code
    if requestStatus != _requestSuccessful:
        raise RequestError("Request failed with error code " + str(requestStatus))
    return response
    
def _request(path, params, methodName):
    reqURL = urljoin(_apiURL, path)
    reqParams = params
    reqMethod = _RequestMethod[methodName]
    return _apiRequest(reqURL, reqParams, reqMethod)

# _dateTimeFormatString = '%Y-%m-%d %H:%M:%S:%f' # TODO Suppport microseconds here
_dateTimeFormatString = '%Y-%m-%d %H:%M:%S'
    
def timeToSQL(pyTime):
    return pyTime.strftime(_dateTimeFormatString)
    
def timeFromSQL(sqlTime):
    return datetime.strptime(sqlTime, _dateTimeFormatString)
    
class RequestError(Exception):
    pass
    
class Connection:
    
    __loginAuthPath = "loginauth"
        
    def __init__(self, username=None, password=None):
        if not username:
            username = raw_input("Username: ")
        if not password:
            password = getpass()
        
        self.username = username
        self.__password = password # TODO We should be using salted hashes for this ><
        
        self.loginID = None
        self.__connect()
        
        self.session = None
        
    # def __del__(self):
    #     if self.session:
    #         self.session.updateSession()
    
    class AuthError(RequestError):
         pass
    
    def __connect(self):
        loginAuthParams = _apiParams(username=self.username, password=self.__password)
        response = _request(Connection.__loginAuthPath, loginAuthParams, _RequestMethods.POST)
        # if responseText == "NOTFOUND":
        try:
            self.loginID = int(response.text)
        except:
            raise Connection.AuthError("Could not establish authenticated connection with API")
            
    @property
    def connected(self):
        return self.loginID is not None
        
    def openNewSession(self, sessionType, sessionStartTime=None, initialTimestamp=None):
        if not self.connected:
            if not self.__connect():
                raise Connection.AuthError("Can't open new session -- no valid authenticated connection to API")
        self.session = Session.openNewSession(self.loginID, sessionType, sessionStartTime, initialTimestamp)
        return self.session
        
    def openSession(self, sessionID):
        if not self.connected:
            if not self.__connect():
                raise Connection.AuthError("Can't open a session -- no valid authenticated connection to API")
        self.session = Session.openSession(sessionID, self.loginID)
        return self.session
        

class Session:
    
    __sessionPath = "session"
    
    class SessionType(IntEnum):
        SLEEP = 0
        PEDOMETRY = 1
        REP_COUNT = 2
    
    def __init__(self, loginID, sessionID, sessionType, sessionStartTime, initialTimestamp=None, sessionEndTime=None, sessionQuality=None):
        self.loginID = loginID
        self.sessionID = sessionID
        self.sessionType = sessionType
        self.sessionStartTime = sessionStartTime
        self.sessionEndTime = sessionEndTime
        self.initialTimestamp = initialTimestamp
        self.sessionQuality = sessionQuality
        
    @classmethod
    def openNewSession(cls, loginID, sessionType, sessionStartTime=None, initialTimestamp=None):
        if not sessionStartTime:
            sessionStartTime = datetime.now()
        sessionStartParam = timeToSQL(sessionStartTime)
        sessionTypeID = int(sessionType)
        sessionIDParams = _apiParams(loginID=loginID, sessionTypeID=sessionTypeID, sessionStartTime=sessionStartParam)
        response = _request(Session.__sessionPath, sessionIDParams, _RequestMethods.POST)
        responseJSON = response.json()
        sessionID = responseJSON['id']
        return cls(loginID, sessionID, sessionType, sessionStartTime, initialTimestamp)
    
    @classmethod
    def openSession(cls, sessionID, loginID=None):
        path = Session.__sessionPath + "/" + str(sessionID)
        params = None
        response = _request(path, params, _RequestMethods.GET)
        responseJSON = response.json()
        sessionLoginID = responseJSON['loginID']
        if loginID is not None:
            if loginID != sessionLoginID:
                raise RequestError("Current loginID doesn't match the loginID of the requested session")
        sessionType = SessionType(int(responseJSON['sessionTypeID']))
        sessionStartTime = timeFromSQL(responseJSON['sessionStartTime'])
        sessionEndTime = timeFromSQL(responseJSON['sessionEndTime'])
        sessionQuality = int(responseJSON['sessionQuality'])
        initialTimestamp = None # TODO
        return cls(sessionLoginID, sessionID, sessionType, sessionStartTime, initialTimestamp, sessionEndTime, sessionQuality)
    
    def updateSession(self):
        updateSessionPath = Session.__sessionPath + "/" + self.sessionID
        sessionTypeID = int(self.sessionType)
        sessionStartTime = timeToSQL(self.sessionStartTime)
        sessionEndTime = timeToSQL(self.sessionEndTime)
        params = _apiParams(loginID=self.loginID, sessionTypeID=sessionTypeID, sessionStartTime=sessionStartTime, sessionEndTime=sessionEndTime, sessionQuality=self.sessionQuality)
        response = _request(updateSessionPath, params, _RequestMethods.PUT)
        responseJSON = response.json()
        sessionLoginID = responseJSON['loginID']
        
    # TODO Encapsulate the below
    
    __allDPSubPath = "sbysessionid/"
    def getDatapoints(self, dpSubPath):
        path = dpSubPath + self.__allDPSubPath + str(self.sessionID)
        params = None
        response = _request(path, params, _RequestMethods.GET)
        return response.json()
    
    def getFirstTimestamp(self, dpSubPath, currTimestamp, timestampKey):
        datapoints = self.getDatapoints(dpSubPath)
        if isinstance(datapoints, list):
            if not datapoints: # If no entries
                firstTimestamp = currTimestamp
            else: # If multiple entries
                firstTimestamp = min(timeFromSQL(dp[timestampKey]) for dp in datapoints)
        else: # If 1 entry
            firstTimestamp = datapoints[timestampKey]
        return firstTimestamp
    
    def __genericDataPointParams(self, dpSubPath, timestamp, timestampKey):
        paramsDict = {'sessionID' : self.sessionID}
        if self.initialTimestamp is None:
            # self.initialTimestamp = self.getFirstTimestamp(dpSubPath, timestamp, timestampKey)
            self.initialTimestamp = timestamp
        microsecondsDiff = timestamp - self.initialTimestamp
        timeDiff = timedelta(microseconds=microsecondsDiff)
        pyTimestamp = self.sessionStartTime + timeDiff
        sqlTime = timeToSQL(pyTimestamp)
        paramsDict[timestampKey] = sqlTime
        return paramsDict
    
    __emgPath = "emgdatapoint"
    def addEMGDataPoint(self, timestamp, emgData):
        paramsDict = self.__genericDataPointParams(Session.__emgPath, timestamp, 'emgpDateTime')
        for podNum, podData in enumerate(emgData, 1):
            paramsDict['emgpPod' + str(podNum)] = podData
        params = _apiParams(**paramsDict)
        response = _request(Session.__emgPath, params, _RequestMethods.POST)
    
    __gyroPath = "rotationdatapoint"
    def addGyroDataPoint(self, timestamp, gyroData):
        paramsDict = self.__genericDataPointParams(Session.__gyroPath, timestamp, 'rdpDateTime')
        paramsDict['rdpXRotation'] = gyroData[0]
        paramsDict['rdpYRotation'] = gyroData[1]
        paramsDict['rdpZRotation'] = gyroData[2]
        params = _apiParams(**paramsDict)
        response = _request(Session.__gyroPath, params, _RequestMethods.POST)
    
    __accelPath = "accelerationdatapoint"
    def addAccelDataPoint(self, timestamp, accelData):
        paramsDict = self.__genericDataPointParams(Session.__accelPath, timestamp, 'adpDateTime')
        paramsDict['adpXAcceleration'] = accelData[0]
        paramsDict['adpYAcceleration'] = accelData[1]
        paramsDict['adpZAcceleration'] = accelData[2]
        params = _apiParams(**paramsDict)
        response = _request(Session.__accelPath, params, _RequestMethods.POST)
    
    __orientPath = "orientationdatapoint"
    def addOrientDataPoint(self, timestamp, orientData): # TODO Decide what to do about quaternions vs Euler angles
        paramsDict = self.__genericDataPointParams(Session.__orientPath, timestamp, 'odpDateTime')
        paramsDict['odpXRotation'] = orientData[0]
        paramsDict['odpYRotation'] = orientData[1]
        paramsDict['odpZRotation'] = orientData[2]
        params = _apiParams(**paramsDict)
        response = _request(Session.__orientPath, params, _RequestMethods.POST)

def main():
    testUsername = "walker"
    testPassword = "walkersux"
    
    connection = Connection(testUsername, testPassword)
    session = connection.openNewSession(Session.SessionType.SLEEP)
    session.initialTimestamp = 179484700928
    dpTimestamp = 179488247655
    dpData = [-1.062011719, 0.30859375, 0.79296875]
    session.addAccelDataPoint(dpTimestamp, dpData)
    
    # TODO datapoint datatypes of Double(8,2)
        # accell, orient: signed double(10,9)
        # gyro: signed double(7, 4)
        # emg: signed smallint

if __name__ == "__main__":
    main()