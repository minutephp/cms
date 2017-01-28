/// <reference path="../../../../../../../public/static/bower_components/minute/_all.d.ts" />
var Admin;
(function (Admin) {
    var ThemeListController = (function () {
        function ThemeListController($scope, $minute, $ui, $timeout, gettext, gettextCatalog) {
            var _this = this;
            this.$scope = $scope;
            this.$minute = $minute;
            this.$ui = $ui;
            this.$timeout = $timeout;
            this.gettext = gettext;
            this.gettextCatalog = gettextCatalog;
            this.actions = function (item) {
                var gettext = _this.gettext;
                var actions = [
                    { 'text': gettext('Edit..'), 'icon': 'fa-edit', 'hint': gettext('Edit templates'), 'href': '/admin/themes/edit/' + item.theme_id },
                    { 'text': gettext('Templates'), 'icon': 'fa-file', 'hint': gettext('Edit templates'), 'href': '/admin/themes/templates/list/' + item.theme_id },
                    { 'text': gettext('Components'), 'icon': 'fa-code', 'hint': gettext('Edit components'), 'href': '/admin/themes/components/list/' + item.theme_id },
                    { 'text': gettext('Clone..'), 'icon': 'fa-copy', 'hint': gettext('Clone theme'), 'click': 'ctrl.clone(item)' },
                    { 'text': gettext('Remove'), 'icon': 'fa-trash', 'hint': gettext('Delete this theme'), 'click': 'item.removeConfirm()' },
                ];
                _this.$ui.bottomSheet(actions, gettext('Actions for: ') + item.name, _this.$scope, { item: item, ctrl: _this });
            };
            this.clone = function (theme) {
                var gettext = _this.gettext;
                theme.templates.setItemsPerPage(99, false);
                theme.templates.reloadAll(true).then(function () {
                    _this.$ui.prompt(gettext('Enter new theme name'), gettext('NewTheme')).then(function (name) {
                        theme.clone().attr('name', name).save(gettext('Theme duplicated')).then(function (copy) {
                            angular.forEach(theme.templates, function (template) {
                                copy.item.templates.cloneItem(template).save().then(function (newTemplate) {
                                    template.relations.setItemsPerPage(99, false);
                                    template.relations.reloadAll(true).then(function () {
                                        angular.forEach(template.relations, function (relation) {
                                            newTemplate.item.relations.cloneItem(relation).save();
                                        });
                                    });
                                });
                            });
                        });
                    });
                });
            };
            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
        }
        return ThemeListController;
    }());
    Admin.ThemeListController = ThemeListController;
    angular.module('themeListApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('themeListController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', ThemeListController]);
})(Admin || (Admin = {}));
