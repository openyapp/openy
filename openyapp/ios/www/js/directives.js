var pomodoro = function ($interval) {
    return {
        scope: {
          seconds: '@seconds'
        },
        restrict: 'E',
        template: function () {
            var templateStr = '<div class="text-center" style="position:absolute; width:100%">'+
                    '<h2 class="text-center waitSeconds" style="height:{{$parent.width/2}};line-height:{{$parent.width/2}}px">{{seconds - timeWait}}</h2>'+
                '</div>'+
                '<div class="text-center">'+
                    '<canvas id="gauge" style="margin-left:{{($parent.width/4) - 10}}px" height="{{$parent.width}}px" width="{{$parent.width}}px"></canvas>'+
                '</div>';
            return templateStr;
        },
        link: function ($scope, elem, attrs) {
            var timer;
            ionic.DomUtil.ready(function(){
                $scope.timeWait = 0;
                var width = $scope.$parent.width / 4,
                    canvasWait = document.getElementById("gauge"),
                    lineWidth = 4;
                console.log(document.getElementById("gauge"));

                var ctx = canvasWait.getContext("2d");
                ctx.translate(5, 5);
                ctx.beginPath();
                var start = -90 * (Math.PI / 180);
                var end = 270 * (Math.PI / 180);
                ctx.arc(width, width, width, start, end);
                ctx.lineWidth = lineWidth;
                ctx.strokeStyle = "#cccccc";
                ctx.stroke();

                var start2 = -90 * (Math.PI / 180);
                var end2 = -80 * (Math.PI / 180);
                var ctx2 = canvasWait.getContext("2d");
                ctx2.translate(0, 0);

                timer = $interval(function () {
                    console.log('timeWait', $scope.timeWait);
                    ctx2.beginPath();
                    end2 = end2 + 3 * (Math.PI / 180);
                    console.log('end2', end2)
                    ctx2.arc(width, width, width, start2, end2);
                    ctx2.lineWidth = lineWidth;
                    ctx2.strokeStyle = "#ff9e1b";
                    ctx2.stroke();
                    if ($scope.timeWait++ == ($scope.seconds - 1)){
                        $scope.$emit('waitComplete', true);
                    }
                }, 1000, $scope.seconds);
            });
            $scope.$on('$destroy', function(){
                $interval.cancel(timer);
            });

        }
    }
}



angular.module('starter')
    .directive('pomodoro', ['$interval', pomodoro])
    .directive('iframeOnload', [function(){
        return {
        scope: {
            callBack: '&iframeOnload'
        },
        link: function(scope, element, attrs){
            element.on('load', function(){
                return scope.callBack();
            })
        }
    }}]);
