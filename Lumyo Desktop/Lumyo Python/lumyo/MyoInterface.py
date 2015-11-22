# Coded by Walker Argendeli, Lumyo Capstone Group
# Based on example code by Thalmic Labs and Niklas Rosenstein

from lumyo import MyoConfig
from lumyo import Listener
from lumyo import MyoOutput
from lumyo import MyoREST

import myo as libmyo
libmyo.init()
from myo.lowlevel.enums import EventType, Pose, Arm, XDirection
from myo.utils.threading import TimeoutClock
from myo.vector import Vector
from myo.quaternion import Quaternion

# import abc
import six
import threading
# import warnings

import time
import sys
from collections import namedtuple
import csv
from math import atan2, asin, sqrt
import os

class DataCaptureListener(Listener.Observable, libmyo.DeviceListener):
    """
    This class implements the :class:`DeviceListener` interface
    to collect all data and make it available to another thread
    on-demand.
    
    Return False from any function to
    stop the Hub.

    .. code-block:: python

        import myo as libmyo
        feed = libmyo.device_listener.Feed()
        hub = libmyo.Hub()
        hub.run(1000, feed)

        try:
            while True:
                myos = feed.get_connected_devices()
                if myos:
                    print myos[0], myos[0].orientation
                time.sleep(0.5)
        finally:
            hub.stop(True)
            hub.shutdown()
            
    This code is adapted from example code accompanying the framework.
    """

    class MyoProxy(object):

        __slots__ = (
            'synchronized,_pair_time,_unpair_time,_connect_time,'
            '_disconnect_time,_myo,_emg,_orientation,_acceleration,'
            '_gyroscope,_pose,_arm,_xdir,_rssi,_firmware_version,'
            '_locked,_emg_enabled,_battery_level,_locking_policy').split(',')

        def __init__(self, low_myo, timestamp, firmware_version):
            super(DataCaptureListener.MyoProxy, self).__init__()
            self.synchronized = threading.Condition()
            self._pair_time = timestamp
            self._unpair_time = None
            self._connect_time = None
            self._disconnect_time = None
            self._locked = False
            self._emg_enabled = False
            self._myo = low_myo
            self._emg = None
            self._orientation = Quaternion.identity()
            self._acceleration = Vector(0, 0, 0)
            self._gyroscope = Vector(0, 0, 0)
            self._pose = Pose.rest
            self._arm = None
            self._xdir = None
            self._rssi = None
            self._battery_level = None
            self._locking_policy = libmyo.LockingPolicy.standard
            self._firmware_version = firmware_version

        def __repr__(self):
            result = '<MyoProxy ('
            with self.synchronized:
                if self.connected:
                    result += 'connected) at 0x{0:x}>'.format(self._myo.value)
                else:
                    result += 'disconnected)>'
            return result

        def __assert_connected(self):
            if not self.connected:
                raise RuntimeError('Myo was disconnected')

        @property
        def connected(self):
            with self.synchronized:
                return (
                    self._connect_time is not None and
                    self._disconnect_time is None
                )

        @property
        def paired(self):
            with self.synchronized:
                return (self.myo_ is None or self._unpair_time is not None)

        @property
        def pair_time(self):
            return self._pair_time

        @property
        def unpair_time(self):
            with self.synchronized:
                return self._unpair_time

        @property
        def connect_time(self):
            return self._connect_time

        @property
        def disconnect_time(self):
            with self.synchronized:
                return self._disconnect_time

        @property
        def firmware_version(self):
            return self._firmware_version

        @property
        def orientation(self):
            with self.synchronized:
                return self._orientation.copy()

        @property
        def acceleration(self):
            with self.synchronized:
                return self._acceleration.copy()

        @property
        def gyroscope(self):
            with self.synchronized:
                return self._gyroscope.copy()

        @property
        def pose(self):
            with self.synchronized:
                return self._pose

        @property
        def arm(self):
            with self.synchronized:
                return self._arm

        @property
        def x_direction(self):
            with self.synchronized:
                return self._xdir

        @property
        def rssi(self):
            with self.synchronized:
                return self._rssi
                
        @property
        def locked(self):
            with self.synchronized:
                return self._locked
                
        @property
        def emg_enabled(self):
            with self.synchronized:
                return self._emg_enabled
                
        @property
        def battery_level(self):
            with self.synchronized:
                return self._battery_level
                
        @property
        def locking_policy(self):
            with self.synchronized:
                return self._locking_policy

        def set_locking_policy(self, locking_policy):
            with self.synchronized:
                self.__assert_connected()
                self._myo.set_locking_policy(locking_policy)
                self._locking_policy = locking_policy

        def set_stream_emg(self, emg):
            with self.synchronized:
                self.__assert_connected()
                self._myo.set_stream_emg(emg)
                if emg == libmyo.StreamEmg.enabled:
                    self._emg_enabled = True
                else:
                    self._emg_enabled = False
                    self._emg = None

        def enable_emg_streaming(self):
            self.set_stream_emg(libmyo.StreamEmg.enabled)
            
        def disble_emg_streaming(self):
            self.set_stream_emg(libmyo.StreamEmg.disabled)
        
        def vibrate(self, vibration_type):
            with self.synchronized:
                self.__assert_connected()
                self._myo.vibrate(vibration_type)

        def request_rssi(self):
            """
            Requests the RSSI of the Myo armband. Until the RSSI is
            retrieved, :attr:`rssi` returns None.
            """
            with self.synchronized:
                self.__assert_connected()
                self._rssi = None
                self._myo.request_rssi()
                
        def request_battery_level(self):
            """
            Requests the battery level of the Myo armband. Until the RSSI is
            retrieved, :attr:`battery_level` returns None.
            """
            with self.synchronized:
                self.__assert_connected()
                self._battery_level = None
                self._myo.request_battery_level()
                
    # Listener
    
    # interval = 0.05  # Output only 0.05 seconds
    
    def __init__(self):
        super(DataCaptureListener, self).__init__()
        
        self.synchronized = threading.Condition()
        self._myos = {}
        self.last_time = 0
        self.proxy = None

    # def __del__(self):
    #     try:
    #         if self.emgFile:
    #             self.emgFile.close()
    #         if self.gyroFile:
    #             self.gyroFile.close()
    #         if self.orientFile:
    #             self.orientFile.close()
    #         if self.orientEulFile:
    #             self.orientEulFile.close()
    #         if self.accelFile:
    #             self.accelFile.close()
    #     except:
    #         pass
    
    def get_devices(self):
        """
        get_devices() -> list of Feed.MyoProxy

        Returns a list of paired and connected Myo's.
        """
        with self.synchronized:
            return list(self._myos.values())

    def get_connected_devices(self):
        """
        get_connected_devices(self) -> list of Feed.MyoProxy

        Returns a list of connected Myo's.
        """
        with self.synchronized:
            return [myo for myo in self._myos.values() if myo.connected]

    def wait_for_single_device(self, timeout=None, interval=0.5):
        """
        wait_for_single_device(timeout) -> Feed.MyoProxy or None

        Waits until a Myo is was paired **and** connected with the Hub
        and returns it. If the *timeout* is exceeded, returns None.
        This function will not return a Myo that is only paired but
        not connected.

        :param timeout: The maximum time to wait for a device.
        :param interval: The interval at which the function should
            exit sleeping. We can not sleep endlessly, otherwise
            the main thread can not be exit, eg. through a
            KeyboardInterrupt.
        """
        timer = TimeoutClock(timeout)
        start = time.time()
        with self.synchronized:
            # As long as there are no Myo's connected, wait until we
            # get notified about a change.
            while not timer.exceeded:
                # Check if we found a Myo that is connected.
                for proxy in six.itervalues(self._myos):
                    if proxy.connected:
                        return proxy
                
                remaining = timer.remaining
                if interval is not None and remaining > interval:
                    remaining = interval
                self.synchronized.wait(remaining)
        return None
        
    def get_proxy(self, myo):
        with self.synchronized:
            try:
                proxy = self._myos[myo.value]
            except KeyError:
                message = "Myo 0x{0:x} was not in the known Myo's list"
                warnings.warn(message.format(myo.value), RuntimeWarning)
        return proxy
        
    @staticmethod
    def euler_from_quaternion(orientation):
        (x, y, z, w) = orientation
        roll = atan2(2.0 * (w * x + y * z), 1.0 - 2.0 * (x * x + y * y))
        pitch = asin(max(-1.0, min(1.0, 2.0 * (w * y - z * x))))
        yaw = atan2(2.0 * (w * z + x * y), 1.0 - 2.0 * (y * y + z * z))
        return (roll, pitch, yaw)
    
    # def output(self, myo):
    #     if self.output_options.console:
    #         ctime = time.time()
    #         if (ctime - self.last_time) < self.interval:
    #             return
    #         self.last_time = ctime
    #
    #         parts = []
    #         proxy = self.get_proxy(myo)
    #         with proxy.synchronized:
    #             orientation = proxy._orientation
    #             if orientation:
    #                 for comp in orientation:
    #                     parts.append(str(comp).ljust(15))
    #             parts.append(str(proxy._pose).ljust(10))
    #             parts.append('E' if proxy._emg_enabled else ' ')
    #             parts.append('L' if proxy._locked else ' ')
    #             parts.append(proxy._rssi or 'NORSSI')
    #             emg = proxy._emg
    #             if emg:
    #                 for comp in emg:
    #                     parts.append(str(comp).ljust(5))
    #             print('\r' + ''.join('[{0}]'.format(p) for p in parts), end='')
    #             sys.stdout.flush()
    #     if self.output_options.files:
    #         pass
    #     if self.output_options.db:
    #         pass

    # DeviceListener

    def on_event(self, kind, event):
        """
        Called before any of the event callbacks.
        """
        myo = event.myo
#         timestamp = event.timestamp
#         with self.synchronized:
#             if kind == EventType.paired:
#                 fmw_version = event.firmware_version
#                 self._myos[myo.value] = self.MyoProxy(myo, timestamp, fmw_version)
#                 self.synchronized.notify_all()
#                 return True
#             elif kind == EventType.unpaired:
#                 try:
#                     proxy = self._myos.pop(myo.value)
#                 except KeyError:
#                     message = "Myo 0x{0:x} was not in the known Myo's list"
#                     warnings.warn(message.format(myo.value), RuntimeWarning)
#                 else:
#                     # Remove the reference handle from the Myo proxy.
#                     with proxy.synchronized:
#                         proxy._unpair_time = timestamp
#                         proxy._myo = None
#                 finally:
#                     self.synchronized.notify_all()
#                 return True
#             else:
#                 try:
#                     proxy = self._myos[myo.value]
#                 except KeyError:
#                     message = "Myo 0x{0:x} was not in the known Myo's list"
#                     warnings.warn(message.format(myo.value), RuntimeWarning)
#                     return True
#
#         with proxy.synchronized:
#             if kind == EventType.connected:
#                 proxy._connect_time = timestamp
#             elif kind == EventType.disconnected:
#                 proxy._disconnect_time = timestamp
#             elif kind == EventType.emg:
#                 proxy._emg = event.emg
#             elif kind == EventType.arm_synced:
#                 proxy._arm = event.arm
#                 proxy._xdir = event.x_direction
#             elif kind == EventType.rssi:
#                 proxy._rssi = event.rssi
#             elif kind == EventType.pose:
#                 proxy._pose = event.pose
#             elif kind == EventType.orientation:
#                 proxy._orientation = event.orientation
#                 proxy._gyroscope = event.gyroscope
#                 proxy._acceleration = event.acceleration
        self.synchronized.acquire()
        if kind != EventType.paired and kind != EventType.unpaired:
            self.proxy = self.get_proxy(myo)

    def on_event_finished(self, kind, event):
        """
        Called after the respective event callbacks have been
        invoked. This method is *always* triggered, even if one of
        the callbacks requested the stop of the Hub.
        """
        self.proxy = None
        self.synchronized.release()
        
    def on_pair(self, myo, timestamp, firmware_version):
        """
        Called when a Myo armband is paired.
        """
        # with self.synchronized:
        self._myos[myo.value] = self.MyoProxy(myo, timestamp, firmware_version)
        self.synchronized.notify_all()

    def on_unpair(self, myo, timestamp):
        """
        Called when a Myo armband is unpaired.
        """
        # with self.synchronized:
        try:
            proxy = self._myos.pop(myo.value)
        except KeyError:
            message = "Myo 0x{0:x} was not in the known Myo's list"
            warnings.warn(message.format(myo.value), RuntimeWarning)
        else:
            # Remove the reference handle from the Myo proxy.
            with proxy.synchronized:
                proxy._unpair_time = timestamp
                proxy._myo = None
        finally:
            self.synchronized.notify_all()

    def on_connect(self, myo, timestamp, firmware_version):
        # print("Myo connected")
        # self.proxy = self.get_self.proxy(myo)
        # with self.proxy.synchronized:
        self.proxy._connect_time = timestamp
        self.proxy.vibrate('short')
        self.proxy.vibrate('short')
        self.proxy.request_rssi()
        self.proxy.request_battery_level()

    def on_disconnect(self, myo, timestamp):
        """
        Called when a Myo is disconnected.
        """
        # self.proxy = self.get_self.proxy(myo)
        # with self.proxy.synchronized:
        self.proxy._disconnect_time = timestamp

    def on_arm_sync(self, myo, timestamp, arm, x_direction, rotation, warmup_state):
        """
        Called when a Myo armband and an arm is synced.
        """
        # self.proxy = self.get_self.proxy(myo)
        # with self.proxy.synchronized:
        self.proxy._arm = arm
        self.proxy._xdir = x_direction

    def on_arm_unsync(self, myo, timestamp):
        """
        Called when a Myo armband and an arm is unsynced.
        """
        # self.proxy = self.get_self.proxy(myo)
        # with self.proxy.synchronized:
        self.proxy._arm = None
        self.proxy._xdir = None

    def on_warmup_completed(self, myo, timestamp, warmup_result):
        """
        Called when the warmup completed.
        """
        # self.proxy = self.get_self.proxy(myo)
        # with self.proxy.synchronized:
        
        # self.proxy._warmed_up = True
        pass
        
    def on_rssi(self, myo, timestamp, rssi):
        # self.proxy = self.get_self.proxy(myo)
        # with self.proxy.synchronized:
        self.proxy._rssi = rssi
        # self.output(myo)
    
    def on_battery_level_received(self, myo, timestamp, level):
        """
        Called when the requested battery level received.
        """
        # self.proxy = self.get_self.proxy(myo)
        # with self.proxy.synchronized:
        self.proxy._battery_level = level

    def on_lock(self, myo, timestamp):
        # self.proxy = self.get_self.proxy(myo)
        # with self.proxy.synchronized:
        self.proxy._locked = True
        # self.output(myo)
    
    def on_unlock(self, myo, timestamp):
        # self.proxy = self.get_self.proxy(myo)
        # with self.proxy.synchronized:
        self.proxy._locked = False
        # self.output(myo)
    
    def on_pose(self, myo, timestamp, pose):
        # self.proxy = self.get_self.proxy(myo)
        # with self.proxy.synchronized:
        self.proxy._pose = pose
        # Use double-tap gesture to enable/disable emg-streaming
        # if pose == libmyo.Pose.double_tap:
        #     self.proxy.set_stream_emg(libmyo.StreamEmg.enabled)
        # elif pose == libmyo.Pose.fingers_spread:
        #     self.proxy.set_stream_emg(libmyo.StreamEmg.disabled)
        # self.output(myo)

    def on_orientation_data(self, myo, timestamp, orientation):
        # self.proxy = self.get_self.proxy(myo)
        # with self.proxy.synchronized:
        self.proxy._orientation = orientation
        self._notifyObservers(MyoOutput.DataType.ORIENT, int(timestamp), list(trim_dec(orientation)))
        # if self.output_options.files and self.orientFile:
        #     try:
        #         self.orientWriter.writerow([int(timestamp)] + list(trim_dec(orientation)))
        #         euler = DataCaptureListener.euler_from_quaternion(orientation)
        #         self.orientEulWriter.writerow([int(timestamp)] + list(trim_dec(euler)))
        #     except:
        #         raise
        # self.output(myo)

    def on_accelerometor_data(self, myo, timestamp, acceleration):
        # self.proxy = self.get_self.proxy(myo)
        # with self.proxy.synchronized:
        self.proxy._acceleration = acceleration
        self._notifyObservers(MyoOutput.DataType.ACCEL, int(timestamp), list(trim_dec(acceleration)))
        # if self.output_options.files and self.accelFile:
        #     try:
        #         self.accelWriter.writerow([int(timestamp)] + list(trim_dec(acceleration)))
        #     except:
        #         raise

    def on_gyroscope_data(self, myo, timestamp, gyroscope):
        # self.proxy = self.get_self.proxy(myo)
        # with self.proxy.synchronized:
        self.proxy._gyroscope = gyroscope
        self._notifyObservers(MyoOutput.DataType.GYRO, int(timestamp), list(gyroscope))
        # if self.output_options.files and self.gyroFile:
        #     try:
        #         self.gyroWriter.writerow([int(timestamp)] + list(gyroscope))
        #     except:
        #         raise

    def on_emg_data(self, myo, timestamp, emg):
        # self.proxy = self.get_self.proxy(myo)
        # with self.proxy.synchronized:
        self.proxy._emg = emg
        self._notifyObservers(MyoOutput.DataType.EMG, int(timestamp), list(emg))
        # if self.output_options.files and self.emgFile:
        #     try:
        #         self.emgWriter.writerow([int(timestamp)] + list(emg))
        #     except:
        #         raise
        # self.output(myo)
        
def trim_dec(nums, dec=9):
    # return map(lambda num : round(num, dec), nums)
    return map(lambda num : '{:.9f}'.format(num), nums)
    
class MyoError(EnvironmentError):
     pass
    
def shutdown_myo(hub, listener):
    if hub:
        # print("Shutting down Myo Hub...")
        # hub.stop()
        # hub.stop(True)
        hub.shutdown()
    if listener:
        listener._tearDownObservers()
        
def initialize_myo(*output_observers):
    print("Initializing...")
    
    # print("Creating Myo Connection Hub...")
    try:
        hub = libmyo.Hub()
        hub.set_locking_policy(libmyo.LockingPolicy.none) # Doesn't seem to be working properly
    except MemoryError:
        raise MyoError("Could not create a Myo Hub.  Make sure Myo Connect is running.")
    listener = None
    try:
        print("Looking for a Myo...10 seconds remaining...")
        # feed = libmyo.device_listener.Feed()
        listener = DataCaptureListener()
        listener.registerObservers(output_observers)
        
        hub.run(1000, listener)
        myo = listener.wait_for_single_device(timeout=10.0) # seconds
        if not myo:
            raise MyoError("No Myo connected after 10 seconds.")
        print("Myo connected!")
        # myo.set_locking_policy(libmyo.LockingPolicy.none)
        myo.enable_emg_streaming()
    except:
        shutdown_myo(hub, listener)
        raise
    print("Initialization complete.")
    return hub, listener, myo

        
# TODO Can't seem to setlockingpolicy on myo (hub version doesn't carry over, and myo version absent)
# TODO Encapsulate state better (e.g. after unsync, warmup set false--will require more use of setters)
# TODO Upload to RESTful API (asynchronously)
# TODO Wait for sync before continuing
# TODO hello-myo.cpp code