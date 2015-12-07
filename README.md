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

  To install the Iphone application load the iOS project into XCode and compile. Upload to your device and pair with the Myo device to use. (Note: The device must have bluetooth capabilities)

(NOTE: The Android App is only a baseline. It has no actual code linking to the Myo. This is due to the lack of direct access to Raw EMG data in the Android API provided by Thalmic Labs.

# Current Status (version 1.0.0)
- Web Application: Stable
- Android Application: Alpha
- iOS Application: Stable

# Known Bugs


#How to Use
