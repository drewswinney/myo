# Coded by Walker Argendeli, Lumyo Capstone Group

from __future__ import print_function

import sys
sys.dont_write_bytecode = True

import time

from lumyo.MyoInterface import initialize_myo, MyoError, shutdown_myo
from lumyo.MyoOutput import *

def main():
    print("Welcome to Lumyo Desktop!  Press Ctrl-C to quit.")
    
    # Listen to keyboard interrupts and stop the hub in that case.
    try:
        hub = None
        listener = None
        try:
            hub, listener, myo = initialize_myo(FileOutput())
        except MyoError as e:
            print(str(e))
            sys.exit(1)
            
        print("Beginning data collection.")
        # while hub.running:
        while hub.running and myo.connected:
            # print(myo.orientation)
            time.sleep(0.25)
    except KeyboardInterrupt:
        sys.exit(0)
    else:
        print("\nMyo disconnected.")
    finally:
        print("\nPreparing to quit...")
        # print("Cleaning up...")
        shutdown_myo(hub, listener)
        print("Goodbye!")

if __name__ == "__main__":
    main()

