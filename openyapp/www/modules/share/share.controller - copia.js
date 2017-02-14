(function () {
    'use strict';
    angular.module('starter.controllers')
        .controller('shareCtrl', ['$scope', '$rootScope', '$q', 'store', function ($scope, $rootScope, $q, store) {
            $scope.loadingShare = true;
            var isAndroid = ionic.Platform.isAndroid();
            var url = 'https://www.entrecomercios.es/web/app/' + $rootScope.myUser.id;
            var img_pre = 'www/img/ionic.png';
            var img = 'https://www.entrecomercios.es/track-2-' + $rootScope.myUser.id + '.png';
            var subject = 'Tienes que ver esta app';
            var msg = 'Para todos los que, como yo, tenéis un pequeño comercio:\n\n';
            msg += 'Estoy usando Entrecomercios https://www.entrecomercios.es porque me ayuda a vender más y ahorrar unos cientos de euros cada mes.\n\n';
            msg += 'Probadlo y venid a hacerme a mi vuestra primera compra sin pagar en efectivo.\n\n';
            msg += 'Además, si usais mi código de invitación (' + $rootScope.myUser.commercial_code + ') al registraros,  ambos nos llevaremos un regalo de bienvenida.\n\n';
            msg += 'Podéis descargaros la app aquí:.\n\n';
            msg += 'Para Android: https://play.google.com/store/apps/details?id=es.entrecomercios.app\n';
            msg += 'Para iOS: https://itunes.apple.com/us/app/entrecomercios/id1019160836?ls=1&mt=8\n';
            msg += '\nEntrecomercios, en confianza';

            var msgB = 'Hola,\n';
            msgB += 'Soy ' + $rootScope.myUser.users.name + ' de ' + $rootScope.myUser.name + '.\n';
            msgB += 'Conozco tu comercio y me gustaría comprarte más y que tú me compres, por lo que te invito a unirte a Entrecomercios.\n\n';
            msgB += 'En Entrecomercios podemos comprarnos y vendernos unos a otros, aumentar ventas y ahorrar cada mes cientos de euros.\n\n';
            msgB += 'Descárgate la app, regístrate y llévate un regalo de bienvenida usando mi código de invitación: ' + $rootScope.myUser.commercial_code + '.\n\n';
            msgB += 'Para Android: https://play.google.com/store/apps/details?id=es.entrecomercios.app\n';
            msgB += 'Para iOS: https://itunes.apple.com/us/app/entrecomercios/id1019160836?ls=1&mt=8\n';
            msgB += '\nEntrecomercios, en confianza';

            var msgTwitter = 'Si, como yo, tienes un comercio únete a Entrecomercios y usa mi código al registrarte: ' + $rootScope.myUser.commercial_code + '. Android: goo.gl/uZxjrO. iOS: goo.gl/GSqXNS';

            var msgMSM = 'Únete a mí en www.entrecomercios.es, la red de crédito mutuo para el comercio local. Bájate la app, usa mi código ' + $rootScope.myUser.commercial_code + ' y llévate un regalo de bienvenida';

            var buttonsExpect = {
                twitter: {
                    position: 0,
                    name: 'Twitter',
                    app: isAndroid ? 'com.twitter.android' : 'com.apple.social.twitter',
                    msg: msgTwitter,
                    img: null,
                    icon: 'ion-social-twitter'
                },
                whatsapp: {
                    position: 1,
                    name: 'Whatsapp',
                    app: isAndroid ? 'com.whatsapp' : 'whatsappAppIOS',
                    msg: msgB,
                    img: null,
                    icon: 'ion-social-whatsapp'
                },
                facebook: {
                    position: 2,
                    name: 'Facebook',
                    app: isAndroid ? 'com.facebook.katana' : 'com.apple.social.facebook',
                    msg: url,
                    img: null,
                    icon: 'ion-social-facebook'
                },
                facebookMessenger: {
                    position: 3,
                    name: 'FB Messenger',
                    app: isAndroid ? 'com.facebook.orca' : 'facebookMessengerAppIOS',
                    msg: msgB,
                    img: null,
                    icon: 'icon-social-facebookMessenger'
                },
                googleTalk: {
                    position: 7,
                    name: 'Hangouts',
                    app: isAndroid ? 'com.google.android.talk' : 'googleTalkAppIOS',
                    msg: msgB,
                    img: null,
                    icon: 'icon-social-googleTalk'
                },
                googleplus: {
                    position: 8,
                    name: 'Google+',
                    app: isAndroid ? 'com.google.android.apps.plus' : 'googleAppIOS',
                    msg: msg,
                    img: null,
                    icon: 'ion-social-googleplus'
                },
                telegram: {
                    position: 9,
                    name: 'Telegram',
                    app: isAndroid ? 'org.telegram.messenger' : 'telegramAppIOS',
                    msg: msgB,
                    img: null,
                    icon: 'icon-social-telegram'
                },
                line: {
                    position: 10,
                    name: 'Line',
                    app: isAndroid ? 'jp.naver.line.android' : 'lineAppIOS',
                    msg: msgB,
                    img: null,
                    icon: 'icon-social-line'
                },
                linkedin: {
                    position: 11,
                    name: 'Linkedin',
                    app: isAndroid ? 'com.linkedin.android' : 'linkedinAppIOS',
                    msg: msg,
                    img: null,
                    icon: 'ion-social-linkedin'
                },
                instagram: {
                    position: 4,
                    name: 'Instagram',
                    app: isAndroid ? 'com.instagram.android' : 'instagramAppIOS',
                    msg: msg,
                    img: img,
                    icon: 'ion-social-instagram'
                },
                sms: {
                    position: 5,
                    name: 'SMS',
                    app: null,
                    msg: null,
                    img: null,
                    icon: 'ion-android-textsms'
                },
                email: {
                    position: 6,
                    name: 'Email',
                    app: null,
                    msg: null,
                    img: null,
                    icon: 'ion-email'
                }
            };
            var storeButtons = store.get('shareButtons') || null;
            if (storeButtons) {
                var j = 0;
                $scope.buttons = {};
                for (j; j < storeButtons.length; j = j + 1) {
                    $scope.buttons[storeButtons[j]] = buttonsExpect[storeButtons[j]];
                }
            } else {
                $scope.loading = true;
            }
            var checkShare = function (key) {
                var appName = buttonsExpect[key].app,
                    deferrer = $q.defer();
                window.plugins.socialsharing.canShareVia(appName, 'msg', null, img, null, function (e) {
                    deferrer.resolve({
                        app: key,
                        has: true
                    });
                }, function (e) {
                    deferrer.resolve({
                        app: key,
                        has: false
                    });
                });
                return deferrer.promise;

            };
            $q.all([checkShare('twitter'),
                   checkShare('whatsapp'),
                    checkShare('facebook'),
                    checkShare('facebookMessenger'),
                    checkShare('googleTalk'),
                    checkShare('googleplus'),
                    checkShare('telegram'),
                    checkShare('line'),
                    checkShare('linkedin'),
                    checkShare('instagram')
                ]).then(function (res) {
                var i = 0,
                    buttons = {
                        sms: buttonsExpect['sms'],
                        email: buttonsExpect['email']
                    },
                    save = ['sms', 'email'];
                for (i; i < res.length; i = i + 1) {
                    if (res[i].has) {
                        buttons[res[i].app] = buttonsExpect[res[i].app];
                        save.push(res[i].app);
                    }
                }
                store.set('shareButtons', save);
                $scope.buttons = buttons;
                $scope.loading = false;
            });
            var sendSMS = function () {
                window.plugins.socialsharing.shareViaSMS(msgMSM, null);
            };

            var sendEmail = function () {
                var onSuccess = function (ok) {
                    console.log('ok', ok);
                };
                var onError = function () {
                    console.log('error', error);
                };
                window.plugins.socialsharing.shareViaEmail(msg, subject, null, null, null, null, onSuccess, onError);
            };

            $scope.share = function (key) {
                if (key == 'sms') sendSMS();
                else if (key == 'email') sendEmail();
                else window.plugins.socialsharing.shareVia(buttonsExpect[key].app, buttonsExpect[key].msg, null, buttonsExpect[key].img, null, null);
            }


    }]);

}());

/*-
<div class="shareButtons">
    <div class="text-center" ng-if="loading">
        <ion-spinner></ion-spinner>
    </div>
    <div ng-if="!loading" ng-repeat="button in buttons | orderObjectBy | orderBy : 'position'">
        <button type="button" class="button button-block button-clear button-{{button.$key}}" ng-click="share(button.$key)"><i ng-class="button.icon" class="icon"></i> Invita por {{button.name}}</button>
    </div>
</div>
-*/