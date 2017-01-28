/// <reference path="../../../../../../../public/static/bower_components/minute/_all.d.ts" />
var Admin;
(function (Admin) {
    var BlogController = (function () {
        function BlogController($scope, $minute, $ui, $timeout, $sce, gettext, gettextCatalog) {
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
            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $scope.settings = $scope.configs[0].attr('data_json').blog;
        }
        return BlogController;
    }());
    Admin.BlogController = BlogController;
    angular.module('blogApp', ['MinuteFramework', 'MinuteDirectives', 'MinuteFilters', 'gettext'])
        .controller('blogController', ['$scope', '$minute', '$ui', '$timeout', '$sce', 'gettext', 'gettextCatalog', BlogController]);
})(Admin || (Admin = {}));
