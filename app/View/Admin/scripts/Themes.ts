/// <reference path="../../../../../../../public/static/bower_components/minute/_all.d.ts" />

module Admin {
    export class ThemeListController {
        constructor(public $scope: any, public $minute: any, public $ui: any, public $timeout: ng.ITimeoutService,
                    public gettext: angular.gettext.gettextFunction, public gettextCatalog: angular.gettext.gettextCatalog) {

            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
        }

        actions = (item) => {
            let gettext = this.gettext;
            let actions = [
                {'text': gettext('Edit..'), 'icon': 'fa-edit', 'hint': gettext('Edit templates'), 'href': '/admin/themes/edit/' + item.theme_id},
                {'text': gettext('Templates'), 'icon': 'fa-file', 'hint': gettext('Edit templates'), 'href': '/admin/themes/templates/list/' + item.theme_id},
                {'text': gettext('Components'), 'icon': 'fa-code', 'hint': gettext('Edit components'), 'href': '/admin/themes/components/list/' + item.theme_id},
                {'text': gettext('Clone..'), 'icon': 'fa-copy', 'hint': gettext('Clone theme'), 'click': 'ctrl.clone(item)'},
                {'text': gettext('Remove'), 'icon': 'fa-trash', 'hint': gettext('Delete this theme'), 'click': 'item.removeConfirm()'},
            ];

            this.$ui.bottomSheet(actions, gettext('Actions for: ') + item.name, this.$scope, {item: item, ctrl: this});
        };

        clone = (theme) => {
            let gettext = this.gettext;

            theme.templates.setItemsPerPage(99, false);
            theme.templates.reloadAll(true).then(() => {
                this.$ui.prompt(gettext('Enter new theme name'), gettext('NewTheme')).then(function (name) {
                    theme.clone().attr('name', name).save(gettext('Theme duplicated')).then(function (copy) {
                        angular.forEach(theme.templates, (template) => {
                            copy.item.templates.cloneItem(template).save().then((newTemplate)=> {
                                template.relations.setItemsPerPage(99, false);
                                template.relations.reloadAll(true).then(() => {
                                    angular.forEach(template.relations, (relation) => {
                                        newTemplate.item.relations.cloneItem(relation).save();
                                    });
                                });
                            });
                        });
                    });
                });
            });
        }
    }

    angular.module('themeListApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('themeListController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', ThemeListController]);
}
