cordova.define('cordova/plugin_list', function(require, exports, module) {
module.exports = [
    {
        "id": "cordova-plugin-device.device",
        "file": "plugins/cordova-plugin-device/www/device.js",
        "pluginId": "cordova-plugin-device",
        "clobbers": [
            "device"
        ]
    },
    {
        "id": "cordova-plugin-splashscreen.SplashScreen",
        "file": "plugins/cordova-plugin-splashscreen/www/splashscreen.js",
        "pluginId": "cordova-plugin-splashscreen",
        "clobbers": [
            "navigator.splashscreen"
        ]
    },
    {
        "id": "nl.x-services.plugins.toast.Toast",
        "file": "plugins/nl.x-services.plugins.toast/www/Toast.js",
        "pluginId": "nl.x-services.plugins.toast",
        "clobbers": [
            "window.plugins.toast"
        ]
    },
    {
        "id": "nl.x-services.plugins.toast.tests",
        "file": "plugins/nl.x-services.plugins.toast/test/tests.js",
        "pluginId": "nl.x-services.plugins.toast"
    },
    {
        "id": "phonegap-plugin-push.PushNotification",
        "file": "plugins/phonegap-plugin-push/www/push.js",
        "pluginId": "phonegap-plugin-push",
        "clobbers": [
            "PushNotification"
        ]
    },
    {
        "id": "ionic-plugin-keyboard.keyboard",
        "file": "plugins/ionic-plugin-keyboard/www/ios/keyboard.js",
        "pluginId": "ionic-plugin-keyboard",
        "clobbers": [
            "cordova.plugins.Keyboard"
        ],
        "runs": true
    },
    {
        "id": "cordova-plugin-x-socialsharing.SocialSharing",
        "file": "plugins/cordova-plugin-x-socialsharing/www/SocialSharing.js",
        "pluginId": "cordova-plugin-x-socialsharing",
        "clobbers": [
            "window.plugins.socialsharing"
        ]
    },
    {
        "id": "plugin.google.maps.phonegap-googlemaps-plugin",
        "file": "plugins/plugin.google.maps/www/googlemaps-cdv-plugin.js",
        "pluginId": "plugin.google.maps",
        "clobbers": [
            "plugin.google.maps"
        ]
    }
];
module.exports.metadata = 
// TOP OF METADATA
{
    "cordova-plugin-crosswalk-webview": "1.2.0",
    "cordova-plugin-device": "1.0.1",
    "cordova-plugin-splashscreen": "2.1.0",
    "cordova-plugin-whitelist": "1.0.0",
    "nl.x-services.plugins.toast": "2.0.4",
    "phonegap-plugin-push": "1.3.0",
    "com.google.playservices": "23.0.0",
    "ionic-plugin-keyboard": "2.2.1",
    "cordova-plugin-x-socialsharing": "5.1.3",
    "com.googlemaps.ios": "1.9.2",
    "plugin.google.maps": "1.3.9"
};
// BOTTOM OF METADATA
});