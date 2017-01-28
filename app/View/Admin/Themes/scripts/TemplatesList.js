/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />
var Admin;
(function (Admin) {
    var TemplateListController = (function () {
        function TemplateListController($scope, $minute, $ui, $timeout, gettext, gettextCatalog) {
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
                    { 'text': gettext('Edit..'), 'icon': 'fa-edit', 'hint': gettext('Edit template'), 'href': '/admin/themes/templates/edit/' + item.theme_template_id },
                    { 'text': gettext('Clone'), 'icon': 'fa-copy', 'hint': gettext('Clone template'), 'click': 'ctrl.clone(item)' },
                    { 'text': gettext('Remove'), 'icon': 'fa-trash', 'hint': gettext('Delete this template'), 'click': 'item.removeConfirm("Removed")' },
                ];
                _this.$ui.bottomSheet(actions, gettext('Actions for: ') + item.name, _this.$scope, { item: item, ctrl: _this });
            };
            this.clone = function (template) {
                var gettext = _this.gettext;
                template.relations.setItemsPerPage(99, false);
                template.relations.reloadAll(true).then(function () {
                    _this.$ui.prompt(gettext('Enter new list'), gettext('new-name')).then(function (name) {
                        template.clone().attr('name', name).save(gettext('Template duplicated')).then(function (copy) {
                            angular.forEach(template.relations, function (relation) { return copy.item.relations.cloneItem(relation).save(); });
                        });
                    });
                });
            };
            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $scope.theme = $scope.themes[0];
        }
        return TemplateListController;
    }());
    Admin.TemplateListController = TemplateListController;
    angular.module('templateListApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('templateListController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', TemplateListController]);
})(Admin || (Admin = {}));
