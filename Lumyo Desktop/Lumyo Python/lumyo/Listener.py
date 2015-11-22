# Coded by Walker Argendeli, Lumyo Capstone Group

from abc import ABCMeta, abstractmethod

class Observable(object):
    def __init__(self):
        super(Observable, self).__init__()
        self.__observers = []
        
    def registerObserver(self, observer):
        if not observer in self.__observers:
            self.__observers.append(observer)
            
    def registerObservers(self, observers):
        map(self.registerObserver, observers)
    
    def unregisterObserver(self, observer):
        if observer in self.__observers:
            self.__observers.remove(observer)
            
    def _unregisterAllObservers(self):
        if self.__observers:
            del self.__observers[:]
            
    def _notifyObservers(self, *args, **kwargs):
        for observer in self.__observers:
            observer.notify(*args, **kwargs)
            
    def _tearDownObservers(self):
        for observer in self.__observers:
            observer.tearDown()
    
class Observer(object):
    __metaclass__ = ABCMeta
    
    @abstractmethod
    def notify(self, *args, **kwargs):
        pass
        
    def tearDown(self):
        pass