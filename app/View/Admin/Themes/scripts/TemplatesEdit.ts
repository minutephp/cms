/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />

module Admin {
    export class TemplateEditController {
        constructor(public $scope:any, public $minute:any, public $ui:any, public $timeout:ng.ITimeoutService,
                    public gettext:angular.gettext.gettextFunction, public gettextCatalog:angular.gettext.gettextCatalog) {

            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            console.log("$scope.session: ", $scope.session.params.theme_id);
            $scope.template = $scope.templates[0] || $scope.templates.create().attr('theme_id', $scope.session.params.theme_id);
        }

        rename = (relation) => {
            this.$ui.prompt(this.gettext('New relation name')).then((name)=> {
                relation.attr('relation_name', name).save(this.gettext('Name changed'));
            });
        };

        save = () => {
            this.$scope.template.save(this.gettext('Template saved successfully')).then((...args)=>console.log("args: ", args));
        };

        pickComponents = () => {
            this.$ui.popupUrl('/components-popup.html', false, this.$scope, {ctrl: this});
        };

        addComponent = (component) => {
            let relations = this.$scope.template.relations;

            relations.create().attr('relation_name', Minute.Utils.unique(relations, 'relation_name', component.name)).attr('theme_component_id', component.theme_component_id)
                .attr('priority', relations.length).save(this.gettext("Component added"));
        };
    }

    angular.module('templateEditApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('templateEditController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', TemplateEditController]);
}