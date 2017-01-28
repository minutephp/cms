/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />
var Admin;
(function (Admin) {
    var ThemeEditController = (function () {
        function ThemeEditController($scope, $minute, $ui, $timeout, gettext, gettextCatalog) {
            var _this = this;
            this.$scope = $scope;
            this.$minute = $minute;
            this.$ui = $ui;
            this.$timeout = $timeout;
            this.gettext = gettext;
            this.gettextCatalog = gettextCatalog;
            this.addAsset = function () {
                _this.$scope.theme.assets.create().attr('type', 'auto');
            };
            this.save = function () {
                _this.$scope.theme.save(_this.gettext('Theme saved')).then(function () {
                    _this.$scope.theme.assets.saveAll(_this.gettext('All assets successfully saved'));
                });
            };
            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $scope.theme = $scope.themes[0] || $scope.themes.create();
        }
        return ThemeEditController;
    }());
    Admin.ThemeEditController = ThemeEditController;
    angular.module('themeEditApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('themeEditController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', ThemeEditController]);
})(Admin || (Admin = {}));
