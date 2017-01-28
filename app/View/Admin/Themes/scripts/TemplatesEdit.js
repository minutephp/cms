/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />
var Admin;
(function (Admin) {
    var TemplateEditController = (function () {
        function TemplateEditController($scope, $minute, $ui, $timeout, gettext, gettextCatalog) {
            var _this = this;
            this.$scope = $scope;
            this.$minute = $minute;
            this.$ui = $ui;
            this.$timeout = $timeout;
            this.gettext = gettext;
            this.gettextCatalog = gettextCatalog;
            this.rename = function (relation) {
                _this.$ui.prompt(_this.gettext('New relation name')).then(function (name) {
                    relation.attr('relation_name', name).save(_this.gettext('Name changed'));
                });
            };
            this.save = function () {
                _this.$scope.template.save(_this.gettext('Template saved successfully')).then(function () {
                    var args = [];
                    for (var _i = 0; _i < arguments.length; _i++) {
                        args[_i - 0] = arguments[_i];
                    }
                    return console.log("args: ", args);
                });
            };
            this.pickComponents = function () {
                _this.$ui.popupUrl('/components-popup.html', false, _this.$scope, { ctrl: _this });
            };
            this.addComponent = function (component) {
                var relations = _this.$scope.template.relations;
                relations.create().attr('relation_name', Minute.Utils.unique(relations, 'relation_name', component.name)).attr('theme_component_id', component.theme_component_id)
                    .attr('priority', relations.length).save(_this.gettext("Component added"));
            };
            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            console.log("$scope.session: ", $scope.session.params.theme_id);
            $scope.template = $scope.templates[0] || $scope.templates.create().attr('theme_id', $scope.session.params.theme_id);
        }
        return TemplateEditController;
    }());
    Admin.TemplateEditController = TemplateEditController;
    angular.module('templateEditApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('templateEditController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', TemplateEditController]);
})(Admin || (Admin = {}));
