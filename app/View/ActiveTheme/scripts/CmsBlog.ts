/// <reference path="../../../../../../../public/static/bower_components/minute/_all.d.ts" />

module Admin {
    export class BlogController {
        constructor(public $scope: any, public $minute: any, public $ui: any, public $timeout: ng.ITimeoutService, public $sce: ng.ISCEService,
                    public gettext: angular.gettext.gettextFunction, public gettextCatalog: angular.gettext.gettextCatalog) {

            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $scope.settings = $scope.configs[0].attr('data_json').blog;
        }

        toDate = (dateStr) => {
            let date = new Date(dateStr);
            let months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            let html = '<b>' + date.getDay() + '</b><br>' + months[date.getMonth()];


            return this.$sce.trustAsHtml(html);
        };
    }

    angular.module('blogApp', ['MinuteFramework', 'MinuteDirectives', 'MinuteFilters', 'gettext'])
        .controller('blogController', ['$scope', '$minute', '$ui', '$timeout', '$sce', 'gettext', 'gettextCatalog', BlogController]);
}
