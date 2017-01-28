/// <reference path="../../../../../../../public/static/bower_components/minute/_all.d.ts" />

module Admin {
    export class CmsConfigController {
        constructor(public $scope: any, public $minute: any, public $ui: any, public $timeout: ng.ITimeoutService,
                    public gettext: angular.gettext.gettextFunction, public gettextCatalog: angular.gettext.gettextCatalog) {

            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');

            let defaults = {
                blog: {title: $scope.session.site.site_name + "'s Blog", url: '/blog', posts: 10, category: 'blog'},
                support: {title: $scope.session.site.site_name + "'s Knowledge base", url: '/help', posts: 10, category: 'support'}
            };

            $scope.data = {pages: [{type: 'blog', name: gettext('Blog (main page)')}, {type: 'support', name: gettext('Knowledge base')}]};
            $scope.config = $scope.configs[0] || $scope.configs.create().attr('type', 'cms').attr('data_json', {});
            $scope.settings = $scope.config.attr('data_json');
            $scope.settings.blog = angular.isObject($scope.settings.blog) ? $scope.settings.blog : defaults['blog'];
            $scope.settings.support = angular.isObject($scope.settings.support) ? $scope.settings.support : defaults['support'];
        }

        trim = (v) => {
            return (v || '').replace(/^\//, '');
        };

        save = () => {
            this.$scope.config.save(this.gettext('Cms saved successfully'));
        };
    }

    angular.module('cmsConfigApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('cmsConfigController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', CmsConfigController]);
}
