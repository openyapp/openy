(function() {
  'use strict';

  angular
    .module('dbaq.ionNumericKeyboard', [])
    .directive('ionNumericKeyboard', IonNumericKeyboard);

  function IonNumericKeyboard() {

    var appendDefaultCss = function(headElem) {
      var css =  '<style type="text/css">@charset "UTF-8";' +
                    '.ion-numeric-keyboard {' +
                    '    bottom: 0;' +
                    '    left: 0;' +
                    '    right: 0;' +
                    '    position: absolute; ' +
                    '    width: 100%;' +
                    '}' +
                    '.ion-numeric-keyboard .row {' +
                    '    padding: 0;' +
                    '    margin: 0;' +
                    '}' +
                    '.ion-numeric-keyboard .key {' +
                    '    border: 0;' +
                    '    border-radius: 0;' +
                    '    padding: 0;' +
                    '    background-color: transparent;' +
                    '    font-size: 180%;' +
                    '    border-style: solid;' +
                    '    color: #fefefe;' +
                    '    border-color: #444;' +
                    '    background-color: #333;' +
                    '}' +
                    '.ion-numeric-keyboard .key.activated {'+
                    '    box-shadow: inset 0 1px 4px rgba(0, 0, 0, .1);'+
                    '    background-color: rgba(68, 68, 68, 0.5);'+
                    '}' +
                    '.ion-numeric-keyboard .row:nth-child(1) .key {' +
                    '    border-top-width: 1px;' +
                    '}' +
                    '.ion-numeric-keyboard .row:nth-child(1) .key,' +
                    '.ion-numeric-keyboard .row:nth-child(2) .key,' +
                    '.ion-numeric-keyboard .row:nth-child(3) .key {' +
                    '    border-bottom-width: 1px;' +
                    '}' +
                    '.ion-numeric-keyboard .row .key:nth-child(1),' +
                    '.ion-numeric-keyboard .row .key:nth-child(2) {' +
                    '    border-right-width: 1px;' +
                    '}' +
                    '.has-ion-numeric-keyboard {' +
                    '    bottom: 188px;' +
                    '}' +
                  '</style>';
      headElem.append(css);
    };

    return {
      restrict: 'E',
      replace: true,
      template: '<div class="ion-numeric-keyboard">' +
                  '<div class="row">' +
                    '<button class="col key button" ng-click="onKeyPress(1)">1</button>' +
                    '<button class="col key button" ng-click="onKeyPress(2)">2</button>' +
                    '<button class="col key button" ng-click="onKeyPress(3)">3</button>' +
                  '</div>' +
                  '<div class="row">' +
                    '<button class="col key button" ng-click="onKeyPress(4)">4</button>' +
                    '<button class="col key button" ng-click="onKeyPress(5)">5</button>' +
                    '<button class="col key button" ng-click="onKeyPress(6)">6</button>' +
                  '</div>' +
                  '<div class="row">' +
                    '<button class="col key button" ng-click="onKeyPress(7)">7</button>' +
                    '<button class="col key button" ng-click="onKeyPress(8)">8</button>' +
                    '<button class="col key button" ng-click="onKeyPress(9)">9</button>' +
                  '</div>' +
                  '<div class="row">' +
                    '<div class="col key"></div>' +
                    '<button class="col key button" ng-click="onKeyPress(0)">0</button>' +
                    '<button class="col key button" ng-click="onDeletePress()"><i class="icon ion-backspace-outline"></i></button>' +
                  '</div>' +
                '</div>',
      scope: {
            onKeyPress: '=',
            onDeletePress: '=',
      },
      link: function($scope, $element, $attr) {
        // add default css to <head>
        appendDefaultCss(angular.element(document).find('head'));

        // add .has-ion-numeric-keyboard to the content if exists
        var ionContentElem = $element.parent().find('ion-content');
        if (ionContentElem) {
          ionContentElem.addClass('has-ion-numeric-keyboard');
        }
       
      }
    };
  }
})();