/// <reference path="../../../../../../../public/static/bower_components/minute/_all.d.ts" />

module Admin {
    let key = 'localstore-files';

    export class HostingConfigController {
        constructor(public $scope: any, public $minute: any, public $ui: any, public $timeout: ng.ITimeoutService,
                    public gettext: angular.gettext.gettextFunction, public gettextCatalog: angular.gettext.gettextCatalog) {

            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');

            $scope.data = {files: [], local: []};

            try {
                $scope.data.local = JSON.parse(localStorage.getItem(key));
            } catch (e) {
            }

            if (!angular.isArray($scope.data.local)) {
                $scope.data.local = [];
            }

            $scope.$watch('data.files', (files) => {
                let local = $scope.data.local;
                angular.forEach(files, (url) => {
                    if (local.indexOf(url) === -1) {
                        local.push(url);
                    }
                });
            }, true);

            $scope.$watch('data.local', (local) => {
                localStorage.setItem(key, JSON.stringify(local));
            }, true);
        }

        basename = (url) => {
            return Minute.Utils.basename(url);
        };

        save = () => {
            this.$scope.config.save(this.gettext('Hosting saved successfully!'));
        };
    }

    angular.module('hostingConfigApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('hostingConfigController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', HostingConfigController]);
}


