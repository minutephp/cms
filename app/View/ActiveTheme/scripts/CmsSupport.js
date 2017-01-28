/// <reference path="../../../../../../../public/static/bower_components/minute/_all.d.ts" />
var Admin;
(function (Admin) {
    var SupportController = (function () {
        function SupportController($scope, $minute, $ui, $timeout, $sce, gettext, gettextCatalog) {
            var _this = this;
            this.$scope = $scope;
            this.$minute = $minute;
            this.$ui = $ui;
            this.$timeout = $timeout;
            this.$sce = $sce;
            this.gettext = gettext;
            this.gettextCatalog = gettextCatalog;
            this.toDate = function (dateStr) {
                var date = new Date(dateStr);
                var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                var html = '<b>' + date.getDay() + '</b><br>' + months[date.getMonth()];
                return _this.$sce.trustAsHtml(html);
            };
            this.ucFirst = function (string) {
                return (string || '').charAt(0).toUpperCase() + (string || '').slice(1);
            };
            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $scope.settings = $scope.configs[0].attr('data_json').support;
        }
        return SupportController;
    }());
    Admin.SupportController = SupportController;
    angular.module('supportApp', ['MinuteFramework', 'MinuteDirectives', 'MinuteFilters', 'gettext'])
        .controller('supportController', ['$scope', '$minute', '$ui', '$timeout', '$sce', 'gettext', 'gettextCatalog', SupportController]);
})(Admin || (Admin = {}));
