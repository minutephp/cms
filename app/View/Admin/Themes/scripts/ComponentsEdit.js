/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />
var Admin;
(function (Admin) {
    var ComponentEditController = (function () {
        function ComponentEditController($scope, $minute, $ui, $timeout, gettext, gettextCatalog) {
            var _this = this;
            this.$scope = $scope;
            this.$minute = $minute;
            this.$ui = $ui;
            this.$timeout = $timeout;
            this.gettext = gettext;
            this.gettextCatalog = gettextCatalog;
            this.save = function () {
                _this.$scope.component.save(_this.gettext('Component saved successfully'));
            };
            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $scope.component = $scope.components[0] || $scope.components.create().attr('theme_id', $scope.session.params.theme_id);
        }
        return ComponentEditController;
    }());
    Admin.ComponentEditController = ComponentEditController;
    angular.module('componentEditApp', ['MinuteFramework', 'AdminApp', 'gettext', 'ng.jsoneditor'])
        .controller('componentEditController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', ComponentEditController]);
})(Admin || (Admin = {}));
