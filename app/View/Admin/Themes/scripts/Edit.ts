/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />

module Admin {
    export class ThemeEditController {
        constructor(public $scope: any, public $minute: any, public $ui: any, public $timeout: ng.ITimeoutService,
                    public gettext: angular.gettext.gettextFunction, public gettextCatalog: angular.gettext.gettextCatalog) {

            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $scope.theme = $scope.themes[0] || $scope.themes.create();
        }

        addAsset = () => {
            this.$scope.theme.assets.create().attr('type', 'auto');
        };

        save = () => {
            this.$scope.theme.save(this.gettext('Theme saved')).then(() => {
                this.$scope.theme.assets.saveAll(this.gettext('All assets successfully saved'));
            })
        };
    }

    angular.module('themeEditApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('themeEditController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', ThemeEditController]);
}
