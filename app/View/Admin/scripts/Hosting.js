/// <reference path="../../../../../../../public/static/bower_components/minute/_all.d.ts" />
var Admin;
(function (Admin) {
    var key = 'localstore-files';
    var HostingConfigController = (function () {
        function HostingConfigController($scope, $minute, $ui, $timeout, gettext, gettextCatalog) {
            var _this = this;
            this.$scope = $scope;
            this.$minute = $minute;
            this.$ui = $ui;
            this.$timeout = $timeout;
            this.gettext = gettext;
            this.gettextCatalog = gettextCatalog;
            this.basename = function (url) {
                return Minute.Utils.basename(url);
            };
            this.save = function () {
                _this.$scope.config.save(_this.gettext('Hosting saved successfully!'));
            };
            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $scope.data = { files: [], local: [] };
            try {
                $scope.data.local = JSON.parse(localStorage.getItem(key));
            }
            catch (e) {
            }
            if (!angular.isArray($scope.data.local)) {
                $scope.data.local = [];
            }
            $scope.$watch('data.files', function (files) {
                var local = $scope.data.local;
                angular.forEach(files, function (url) {
                    if (local.indexOf(url) === -1) {
                        local.push(url);
                    }
                });
            }, true);
            $scope.$watch('data.local', function (local) {
                localStorage.setItem(key, JSON.stringify(local));
            }, true);
        }
        return HostingConfigController;
    }());
    Admin.HostingConfigController = HostingConfigController;
    angular.module('hostingConfigApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('hostingConfigController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', HostingConfigController]);
})(Admin || (Admin = {}));
