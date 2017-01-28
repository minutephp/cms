/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />

module Admin {
    export class ComponentListController {
        constructor(public $scope:any, public $minute:any, public $ui:any, public $timeout:ng.ITimeoutService,
                    public gettext:angular.gettext.gettextFunction, public gettextCatalog:angular.gettext.gettextCatalog) {

            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $scope.theme = $scope.themes[0];
        }

        actions = (item) => {
            let gettext = this.gettext;
            let actions = [
                {'text': gettext('Edit..'), 'icon': 'fa-edit', 'hint': gettext('Edit component'), 'href': '/admin/themes/components/edit/' + item.theme_component_id},
                {'text': gettext('Clone'), 'icon': 'fa-copy', 'hint': gettext('Clone component'), 'click': 'ctrl.clone(item)'},
                {'text': gettext('Remove'), 'icon': 'fa-trash', 'hint': gettext('Delete this component'), 'click': 'item.removeConfirm("Removed")'},
            ];

            this.$ui.bottomSheet(actions, gettext('Actions for: ') + item.name, this.$scope, {item: item, ctrl: this});
        };

        clone = (component) => {
            this.$ui.prompt(this.gettext('Enter new component name')).then((name) => {
                component.clone().attr('name', name).save(this.gettext('Component cloned'));
            });
        }
    }

    angular.module('componentListApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('componentListController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', ComponentListController]);
}
