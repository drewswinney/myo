# Lumyo

The Lumyo is a full stack application (Mobile, Web app, and device) that allows you to view raw and aggregate data from sessions using the Myo EMG Armband.

# Installation Instructions

## Web Application
  The web application can be installed on any http server. During testing the web app ran on a linux server running Apache and MySQL. There is a backup of the database under Web Application\Database Backups. The backup file contains only the structure for the database. 
  
## REST API
  The REST API can be installed in the same manner as the web application, with a few extra steps to install dependencies. This link will provide more detail about the dependency installation for the lumen platform (the PHP library used to build this API): http://lumen.laravel.com/docs/installation
  
For more information on database structure and the REST API consult the Architecture Overview Documentation.

NOTE: In most of the code the API calls are directed towards drewswinney.com:8080. You must replace this with your host IP or URL.

## Iphone and Android Applications 

  Installing the Iphone application
  1. First install XCode on your computer, which can be found here: https://developer.apple.com/xcode/.
  2. Load the iOS project into XCode and compile.
  3. Upload to your device and pair with the Myo device to use. (Note: The device must have bluetooth capabilities.)

  Installing the Android application
  1. First install Android Studio on your computer, which can be found here: http://developer.android.com/sdk/index.html. If your computer does not have JDK 7 or higher installed, install it here: http://www.oracle.com/technetwork/java/javase/downloads/index.html.
  2. Load the Android project into Android Studio and compile.
  3. Upload to your device and pair with the Myo device to use. (Note: The device must have bluetooth capabilities.)

(NOTE: The Android App is only a baseline. It has no actual code linking to the Myo. This is due to the lack of direct access to Raw EMG data in the Android API provided by Thalmic Labs.

## Desktop Application
To install Lumyo Desktop, one can either install the dependencies listed in the Lumyo Python readme file, or simply run the installation script in the top level directory.

# Current Status (version 1.0.0)
- Web Application: Stable
- Android Application: Alpha
- iOS Application: Stable

# Known Bugs
### Web Application
  - Randomly, a day will not show it's quality (have a color) in the calendar view
  
### Android Application
  - Android application is unable to connect with the Myo device

### iOS Application
  - Lag in iOS app due to multiple asynchronous calls to the server each second

#How to Use
1. Install all of the applications
2. Run either the desktop app or mobile application and log in (make sure you have a login row with your information in the database)
3. Open up your instance of the web application and log in
4. For the Mobile App: Click the Start button and begin your session
5. For the Desktop App: Launch script RunLumyo.sh, keeping in mind that the automatic behavior outputs to a log file. If you want to output directly to the database you must change the logging parameters in the config file MyoConfig.ini.
6. After running a session you will see it in the sessions view on the web application. Click on the day the session ran to view the session data (or data for multiple sessions if you have ran more than one)

#Troubleshooting
My phone can't run the iOS/Android app!
The iOS can only be run on 7.1 or newer phones. The Android app can only run on Android 4.3 or newer phones.

My phone can't connect to the Myo!
Make sure the Myo is turned on. Be sure your app is on the "Scanning" screen, which can be accessed by pressing the "Start" button after logging in. Check if you phone is can detect bluetooth devices. If not, enable it or find a different device that can.