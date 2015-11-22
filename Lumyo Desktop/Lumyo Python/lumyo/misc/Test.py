from collections import namedtuple

# OutputFormat = namedtuple('OutputFormat', ['text', 'files', 'db'])
# output_options = OutputFormat(True, False, False)
# print output_options.text, output_options.files

DataType = namedtuple('DataType', ['EMG', 'GYRO', 'ORIENT', 'ACCEL'])
_DataTypeName = {getattr(DataType,fName) : fName for fName in DataType._fields}

# class Test():
#     pass
#     # a = 7
#     # b = a + 3
#     # DataType = namedtuple('DataType', ['EMG', 'GYRO', 'ORIENT', 'ACCEL'])
#     # f = DataType._fields
#     # g = getattr(DataType, 'EMG')
#     # n = {getattr(Test.DataType, fn) : fn for fn in f}
#     # _DataTypeName = {getattr(Test.DataType,fName) : fName for fName in DataType._fields}/

import Listener
class TestObserver(Listener.Observer):
    def __init__(self, name):
        self.name = name
        
    def notify(*args, **kwargs):
        controlParam = args[0]
        args = args[1:]
        self.notify(controlParam, *args, **kwargs)
    
    # def notify(self, a, b, c):
    #     print self.name, "(abc): ", a, b, c
    #
    # def notify(self, a):
    #     print self.name, "(a): ", a

testObs = TestObserver("testObs")
abc = Listener.Observable()
abc.registerObserver(testObs)
abc._notifyObservers(1)
abc._notifyObservers(1,2,3)