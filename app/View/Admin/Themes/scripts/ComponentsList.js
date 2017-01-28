/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />
var Admin;
(function (Admin) {
    var ComponentListController = (function () {
        function ComponentListController($scope, $minute, $ui, $timeout, gettext, gettextCatalog) {
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
                    { 'text': gettext('Edit..'), 'icon': 'fa-edit', 'hint': gettext('Edit component'), 'href': '/admin/themes/components/edit/' + item.theme_component_id },
                    { 'text': gettext('Clone'), 'icon': 'fa-copy', 'hint': gettext('Clone component'), 'click': 'ctrl.clone(item)' },
                    { 'text': gettext('Remove'), 'icon': 'fa-trash', 'hint': gettext('Delete this component'), 'click': 'item.removeConfirm("Removed")' },
                ];
                _this.$ui.bottomSheet(actions, gettext('Actions for: ') + item.name, _this.$scope, { item: item, ctrl: _this });
            };
            this.clone = function (component) {
                _this.$ui.prompt(_this.gettext('Enter new component name')).then(function (name) {
                    component.clone().attr('name', name).save(_this.gettext('Component cloned'));
                });
            };
            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $scope.theme = $scope.themes[0];
        }
        return ComponentListController;
    }());
    Admin.ComponentListController = ComponentListController;
    angular.module('componentListApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('componentListController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', ComponentListController]);
})(Admin || (Admin = {}));
