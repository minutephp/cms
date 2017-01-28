/// <reference path="../../../../../../../public/static/bower_components/minute/_all.d.ts" />

module Admin {
    export class SupportController {
        constructor(public $scope: any, public $minute: any, public $ui: any, public $timeout: ng.ITimeoutService, public $sce: ng.ISCEService,
                    public gettext: angular.gettext.gettextFunction, public gettextCatalog: angular.gettext.gettextCatalog) {

            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $scope.settings = $scope.configs[0].attr('data_json').support;
        }

        toDate = (dateStr) => {
            let date = new Date(dateStr);
            let months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            let html = '<b>' + date.getDay() + '</b><br>' + months[date.getMonth()];


            return this.$sce.trustAsHtml(html);
        };

        ucFirst = (string) => {
            return (string || '').charAt(0).toUpperCase() + (string || '').slice(1);
        };
    }

    angular.module('supportApp', ['MinuteFramework', 'MinuteDirectives', 'MinuteFilters', 'gettext'])
        .controller('supportController', ['$scope', '$minute', '$ui', '$timeout', '$sce', 'gettext', 'gettextCatalog', SupportController]);
}
