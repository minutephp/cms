/// <reference path="../../../../../../../public/static/bower_components/minute/_all.d.ts" />
var Admin;
(function (Admin) {
    var CmsConfigController = (function () {
        function CmsConfigController($scope, $minute, $ui, $timeout, gettext, gettextCatalog) {
            var _this = this;
            this.$scope = $scope;
            this.$minute = $minute;
            this.$ui = $ui;
            this.$timeout = $timeout;
            this.gettext = gettext;
            this.gettextCatalog = gettextCatalog;
            this.trim = function (v) {
                return (v || '').replace(/^\//, '');
            };
            this.save = function () {
                _this.$scope.config.save(_this.gettext('Cms saved successfully'));
            };
            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            var defaults = {
                blog: { title: $scope.session.site.site_name + "'s Blog", url: '/blog', posts: 10, category: 'blog' },
                support: { title: $scope.session.site.site_name + "'s Knowledge base", url: '/help', posts: 10, category: 'support' }
            };
            $scope.data = { pages: [{ type: 'blog', name: gettext('Blog (main page)') }, { type: 'support', name: gettext('Knowledge base') }] };
            $scope.config = $scope.configs[0] || $scope.configs.create().attr('type', 'cms').attr('data_json', {});
            $scope.settings = $scope.config.attr('data_json');
            $scope.settings.blog = angular.isObject($scope.settings.blog) ? $scope.settings.blog : defaults['blog'];
            $scope.settings.support = angular.isObject($scope.settings.support) ? $scope.settings.support : defaults['support'];
        }
        return CmsConfigController;
    }());
    Admin.CmsConfigController = CmsConfigController;
    angular.module('cmsConfigApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('cmsConfigController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', CmsConfigController]);
})(Admin || (Admin = {}));
