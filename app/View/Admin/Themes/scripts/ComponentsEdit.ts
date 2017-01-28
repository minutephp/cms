/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />

module Admin {
    export class ComponentEditController {
        constructor(public $scope:any, public $minute:any, public $ui:any, public $timeout:ng.ITimeoutService,
                    public gettext:angular.gettext.gettextFunction, public gettextCatalog:angular.gettext.gettextCatalog) {

            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $scope.component = $scope.components[0] || $scope.components.create().attr('theme_id', $scope.session.params.theme_id);
        }

        save = () => {
            this.$scope.component.save(this.gettext('Component saved successfully'));
        };
    }

    angular.module('componentEditApp', ['MinuteFramework', 'AdminApp', 'gettext', 'ng.jsoneditor'])
        .controller('componentEditController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', ComponentEditController]);
}
