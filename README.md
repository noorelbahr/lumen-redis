## How to run ##

npm install

yarn add react-native-elements

for running on ios : 

1. Open terminal 

2. Go to the ios folder 

3. Quit Xcode

4. run pod install 

5. Open .xcworkspace file.

6. Clean (cmd+shift+k) and build (cmd+b) the project. 

7. run emulator via xcode 

for running android :

1. open android studio

2. open emulator

3. open terminal 

4. go to the project folder

5. run react-native run-android

for build apk (debug) : 

1. In your root project directory, make sure you have already directory **android/app/src/main/assets/**, if not create directory, after that create new file and save as index.android.bundle and put your file in like this **android/app/src/main/assets/index.android.bundle**

2. After that, run this : "**react-native bundle --platform android --dev false --entry-file index.js --bundle-output android/app/src/main/assets/index.android.bundle --assets-dest android/app/src/main/res/**"

3. If found error 'warnOnce' **please run rm -rf node_modules && npm install, then use npx when running step number 2** 

4. If have error regarding duplicate resource, **remove drawable folder on android/app/src/main/res/drawable**

5. After that, run this : "**cd android && ./gradlew assembleDebug**"

also dont forget to (android)

1. brew install gradle / brew update gradle for android running

2. generate your debug keystore on android/app

3. setup sdk location on local.properties , with your own sdk location


**For build bundle (.aab)**
cd android && ./gradlew bundleRelease



when have problem with npm cache

"react-native start --reset-cache"


**For releasing version via codepus (submit changes to production without submiting to playstore or appstore) : **

1. install code-push cli
2. iOS = code-push release-react jarvis_ios ios -t <version_release>
3. android = code-push release-react jarvis_android android

**when have error with development server, run this : **

- adb reverse tcp:8081 tcp:8081