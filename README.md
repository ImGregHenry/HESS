### Android Studio

https://developer.android.com/sdk/index.html#top


### Genymotion

https://www.genymotion.com/#!/download


### JDK
http://www.oracle.com/technetwork/java/javase/downloads/jdk8-downloads-2133151.html


### ConEMU
....

### Node JS / NPM
https://nodejs.org/download/
(windows msi)


### CommandLine (ConEMU)

```
npm install -g bower
npm install -g requirejs
npm install -g gulp
```

### Enable Developer Mode on Android

- Go to Settings > About Phone.
- Tap `Build Number` until you're a developer.
- Go back.  Go to `Developer Options`.
- Enable `Developer Options`.
- Enable `USB Debugging`.

- Device Manager > Other Devices > Nexus 4 > Properties > Update Driver.
- Target: c:\users\greg\appdata\local\android\sdk\extras\google\usb_driver\

Notification Bar > Touch for other USB Options > Camera (PTP)

- From Console, run `adb devices`.  
- Phone will bring up prompt to authorize the fingerprint for the computer > Enable USB Debugging.
- Running `adb devices` will now list your device correctly.


### Start MongoDB Server

Open console as admin.

Navigate to: `c:/Program Files/MongoDB/Server/3.0/bin` 

- Folder `db` must exist

```shell
mongod --dbpath db
```

### Start Node Server

Open console as admin.

Navigate to server project location: `c:/dev/HESS/HessServer`

```shell
gulp
```

