/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />

module Admin {
    export class TemplateListController {
        constructor(public $scope: any, public $minute: any, public $ui: any, public $timeout: ng.ITimeoutService,
                    public gettext: angular.gettext.gettextFunction, public gettextCatalog: angular.gettext.gettextCatalog) {

            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $scope.theme = $scope.themes[0];
        }

        actions = (item) => {
            let gettext = this.gettext;
            let actions = [
                {'text': gettext('Edit..'), 'icon': 'fa-edit', 'hint': gettext('Edit template'), 'href': '/admin/themes/templates/edit/' + item.theme_template_id},
                {'text': gettext('Clone'), 'icon': 'fa-copy', 'hint': gettext('Clone template'), 'click': 'ctrl.clone(item)'},
                {'text': gettext('Remove'), 'icon': 'fa-trash', 'hint': gettext('Delete this template'), 'click': 'item.removeConfirm("Removed")'},
            ];

            this.$ui.bottomSheet(actions, gettext('Actions for: ') + item.name, this.$scope, {item: item, ctrl: this});
        };

        clone = (template) => {
            let gettext = this.gettext;
            template.relations.setItemsPerPage(99, false);
            template.relations.reloadAll(true).then(() => {
                this.$ui.prompt(gettext('Enter new list'), gettext('new-name')).then(function (name) {
                    template.clone().attr('name', name).save(gettext('Template duplicated')).then(function (copy) {
                        angular.forEach(template.relations, (relation) => copy.item.relations.cloneItem(relation).save());
                    });
                });
            });
        }
    }

    angular.module('templateListApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('templateListController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', TemplateListController]);
}