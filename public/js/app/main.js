function GreetCtrl($scope) {
    $scope.name = 'World';
    $scope.action = function () {
        $scope.name = 'Wilton';
    }
}

function ListCtrl($scope) {
    $scope.names = ['Igor', 'Misko', 'Vojta', 'Wilton'];
}

/**
 *
 */
angular.module('My', [])
    .directive('datepicker', function () {
        return {
            restrict: 'E',
            // This HTML will replace the zippy directive.
            replace: true,
            transclude: true,
            scope: {
                title: '@zippyTitle'
            },
            template: '<input class="datepicker" name="datepicker" id="datepicker" />',
            // The linking function will add behavior to the template
            link: function (scope, element, attrs) {
                element.datepicker({
                    inline: true,
                    dateFormat: 'dd.mm.yy',
                    onSelect: function (dateText) {
                        var modelPath = $(this).attr('ng-model');
                        putObject(modelPath, scope, dateText);
                        scope.$apply();
                    }
                });
            }
        }
    });
