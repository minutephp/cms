/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />

module Admin {
    export class ContentEditController {
        private socket = null;

        constructor(public $scope: any, public $minute: any, public $ui: any, public $timeout: ng.ITimeoutService, public $window: ng.IWindowService,
                    public gettext: angular.gettext.gettextFunction, public gettextCatalog: angular.gettext.gettextCatalog) {

            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');

            $scope.content = $scope.contents[0];
            $scope.page = angular.extend({}, $scope.session.site, {
                page_id: $scope.content.page_id,
                page_content_id: $scope.content.page_content_id,
                url: $scope.session.site.host + '/' + ($scope.content.page.slug || '').replace(/^\//, '')
            });

            $scope.data = angular.extend({model: {global: {}, local: {}}, scopes: {}, components: $scope.content.relations, page: $scope.page}, $scope.content.data_json);
            $scope.tmp = {};
            $scope.scopes = ['global', 'local'];

            angular.forEach($scope.content.relations, (relation) => {
                let component = relation.component;

                if (relation.component.global_data) {
                    $scope.data.model['global'][component.name] = component.global_data.global_data_json;
                }


                if (component.sample_data_json) {
                    for (let type in component.sample_data_json) {
                        if (component.sample_data_json.hasOwnProperty(type)) {
                            if (!$scope.data.model[type][component.name]) {
                                $scope.data.model[type][component.name] = angular.copy(component.sample_data_json[type]);
                            }
                        }
                    }
                }
            });

            $scope.$watch(() => this.socket, this.update);
            $scope.$watch('data.model', this.update, true);
            $scope.$watch('data.scopes', this.update, true);
            $scope.$watch('data.component.component_html', this.update);

            $window.onbeforeunload = this.check;

            $timeout(this.splitter, 1000);
            $timeout(this.popIn, 1);
            $timeout(this.checkpoint, 1);
        }

        check = () => {
            if (angular.toJson(this.$scope.data.model) != this.$scope.tmp.lastSave) {
                return 'Are you sure?';
            }
        };

        updateForm = (data) => {
            delete(this.$scope.scopes);

            this.$timeout(() => {
                this.$scope.data.model = data.model;
                this.$scope.scopes = ['global', 'local']; //to re-render json form
            });
        };

        showSource = () => {
            let copy = angular.toJson(this.$scope.data.model);
            this.$ui.popupUrl('/source-code-popup.html', false, null, {data: {model: angular.fromJson(copy)}, ctrl: this});
        };

        customHtml = () => {
            this.$ui.popupUrl('/custom-html-popup.html', false, null, {content: this.$scope.content, ctrl: this});
        };

        splitter = () => {
            let div: any = $("#MySplitter");
            let parent = div.parent();

            div.resizable({
                autoHide: true,
                handles: 'e',
                start: () => this.$scope.iframe.style.pointerEvents = 'none',
                resize: (e, ui) => {
                    var remainingSpace = parent.width() - ui.element.outerWidth();
                    var divTwo = ui.element.next();
                    var divTwoWidth = (remainingSpace - (divTwo.outerWidth() - divTwo.width())) / parent.width() * 100 + "%";
                    divTwo.width(divTwoWidth);
                },
                stop: (e, ui) => {
                    ui.element.css({width: ui.element.width() / parent.width() * 100 + "%"});
                    this.$scope.iframe.style.pointerEvents = 'auto';
                }
            });
        };

        connect = () => {
            try {
                let iframe = this.$scope.iframe;
                if (this.socket = iframe && iframe.contentWindow ? iframe.contentWindow.socket : (iframe && iframe.socket ? iframe.socket : null)) {
                    if (this.socket.ping() !== 'ok') {
                        this.socket = null;
                    }
                }
            } catch (e) {
                this.socket = null;
            }

            this.$timeout(this.connect, this.socket ? 5000 : 500);
        };

        update = () => {
            if (this.socket) {
                this.socket.setData(angular.extend({html: this.$scope.content.template.html_code}, this.$scope.data));
            }
        };

        save = (afterSave) => {
            if (this.socket && this.socket.ping() === 'ok') {
                var component, html;
                let noop = () => 1;

                angular.forEach(this.$scope.data.scopes, (scope, name) => {
                    if (scope === 'global') {
                        if ((html = this.socket.getComponentHtml(name)) && (component = this.findComponentByName(name))) {
                            component.global_data.attr('global_data_json', angular.copy(this.$scope.data.model.global[name])).attr('compiled_html', html).save().then(() => (afterSave || noop)());
                        } else {
                            console.log("Warning: Unable to compile ", name);
                        }
                    }
                });

                if (html = this.socket.getPageHtml()) {
                    let data = this.$scope.data;
                    this.$scope.content.attr('data_json', {model: data.model, scopes: data.scopes}).attr('compiled_html', html).save(this.gettext('Content saved successfully'));
                }

                this.checkpoint();
            } else {
                this.$timeout(this.$scope.save, 1000);
            }
        };

        checkpoint = () => {
            this.$scope.tmp.lastSave = angular.toJson(this.$scope.data.model);
        };

        preview = () => {
            var w = window.open('/admin/pages/render/' + this.$scope.page.page_content_id, '_blank');
            this.save(() => w.location.reload());
        };

        popOut = () => {
            this.$scope.data.inline = false;

            if (this.$scope.iframe) {
                this.$scope.iframe.src = 'about:blank';
            }

            this.$scope.iframe = window.open(this.getSrc(), 'preview');
            this.$timeout(this.connect, 1000);
        };

        popIn = () => {
            this.$scope.data.inline = true;

            if (this.$scope.iframe) {
                this.$scope.iframe.close();
            }

            this.$scope.iframe = document.getElementById('previewWindow');
            this.$scope.iframe.src = this.getSrc();
            this.$timeout(this.connect, 500);
        };

        findComponentByName = (name) => {
            for (var relation of this.$scope.data.components) {
                if (relation.component.name === name) {
                    return relation.component;
                }
            }

            return null;
        };

        enable = (enabled) => {
            this.$scope.content.attr('enabled', enabled).save(enabled ? this.gettext('Page enabled') : this.gettext('Page disabled'));
        };

        private getSrc = () => {
            return '/admin/pages/preview/' + this.$scope.content.page_content_id;
        }
    }

    angular.module('contentEditApp', ['MinuteFramework', 'AdminApp', 'gettext', 'AngularJsonForm', 'ng.jsoneditor'])
        .controller('contentEditController', ['$scope', '$minute', '$ui', '$timeout', '$window', 'gettext', 'gettextCatalog', ContentEditController]);
}