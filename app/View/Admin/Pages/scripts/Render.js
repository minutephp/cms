/// <reference path="E:/var/Dropbox/projects/minutephp/public/static/bower_components/minute/_all.d.ts" />
var Admin;
(function (Admin) {
    var RenderController = (function () {
        function RenderController($scope, $minute, $ui, $timeout, gettext, gettextCatalog) {
            this.$scope = $scope;
            this.$minute = $minute;
            this.$ui = $ui;
            this.$timeout = $timeout;
            this.gettext = gettext;
            this.gettextCatalog = gettextCatalog;
            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
        }
        return RenderController;
    }());
    Admin.RenderController = RenderController;
    angular.module('RenderApp', ['MinuteFramework', 'gettext'])
        .controller('RenderController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', RenderController]);
})(Admin || (Admin = {}));
