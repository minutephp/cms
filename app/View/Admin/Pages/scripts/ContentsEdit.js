/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />
var Admin;
(function (Admin) {
    var ContentEditController = (function () {
        function ContentEditController($scope, $minute, $ui, $timeout, $window, gettext, gettextCatalog) {
            var _this = this;
            this.$scope = $scope;
            this.$minute = $minute;
            this.$ui = $ui;
            this.$timeout = $timeout;
            this.$window = $window;
            this.gettext = gettext;
            this.gettextCatalog = gettextCatalog;
            this.socket = null;
            this.check = function () {
                if (angular.toJson(_this.$scope.data.model) != _this.$scope.tmp.lastSave) {
                    return 'Are you sure?';
                }
            };
            this.updateForm = function (data) {
                delete (_this.$scope.scopes);
                _this.$timeout(function () {
                    _this.$scope.data.model = data.model;
                    _this.$scope.scopes = ['global', 'local']; //to re-render json form
                });
            };
            this.showSource = function () {
                var copy = angular.toJson(_this.$scope.data.model);
                _this.$ui.popupUrl('/source-code-popup.html', false, null, { data: { model: angular.fromJson(copy) }, ctrl: _this });
            };
            this.customHtml = function () {
                _this.$ui.popupUrl('/custom-html-popup.html', false, null, { content: _this.$scope.content, ctrl: _this });
            };
            this.splitter = function () {
                var div = $("#MySplitter");
                var parent = div.parent();
                div.resizable({
                    autoHide: true,
                    handles: 'e',
                    start: function () { return _this.$scope.iframe.style.pointerEvents = 'none'; },
                    resize: function (e, ui) {
                        var remainingSpace = parent.width() - ui.element.outerWidth();
                        var divTwo = ui.element.next();
                        var divTwoWidth = (remainingSpace - (divTwo.outerWidth() - divTwo.width())) / parent.width() * 100 + "%";
                        divTwo.width(divTwoWidth);
                    },
                    stop: function (e, ui) {
                        ui.element.css({ width: ui.element.width() / parent.width() * 100 + "%" });
                        _this.$scope.iframe.style.pointerEvents = 'auto';
                    }
                });
            };
            this.connect = function () {
                try {
                    var iframe = _this.$scope.iframe;
                    if (_this.socket = iframe && iframe.contentWindow ? iframe.contentWindow.socket : (iframe && iframe.socket ? iframe.socket : null)) {
                        if (_this.socket.ping() !== 'ok') {
                            _this.socket = null;
                        }
                    }
                }
                catch (e) {
                    _this.socket = null;
                }
                _this.$timeout(_this.connect, _this.socket ? 5000 : 500);
            };
            this.update = function () {
                if (_this.socket) {
                    _this.socket.setData(angular.extend({ html: _this.$scope.content.template.html_code }, _this.$scope.data));
                }
            };
            this.save = function (afterSave) {
                if (_this.socket && _this.socket.ping() === 'ok') {
                    var component, html;
                    var noop_1 = function () { return 1; };
                    angular.forEach(_this.$scope.data.scopes, function (scope, name) {
                        if (scope === 'global') {
                            if ((html = _this.socket.getComponentHtml(name)) && (component = _this.findComponentByName(name))) {
                                component.global_data.attr('global_data_json', angular.copy(_this.$scope.data.model.global[name])).attr('compiled_html', html).save().then(function () { return (afterSave || noop_1)(); });
                            }
                            else {
                                console.log("Warning: Unable to compile ", name);
                            }
                        }
                    });
                    if (html = _this.socket.getPageHtml()) {
                        var data = _this.$scope.data;
                        _this.$scope.content.attr('data_json', { model: data.model, scopes: data.scopes }).attr('compiled_html', html).save(_this.gettext('Content saved successfully'));
                    }
                    _this.checkpoint();
                }
                else {
                    _this.$timeout(_this.$scope.save, 1000);
                }
            };
            this.checkpoint = function () {
                _this.$scope.tmp.lastSave = angular.toJson(_this.$scope.data.model);
            };
            this.preview = function () {
                var w = window.open('/admin/pages/render/' + _this.$scope.page.page_content_id, '_blank');
                _this.save(function () { return w.location.reload(); });
            };
            this.popOut = function () {
                _this.$scope.data.inline = false;
                if (_this.$scope.iframe) {
                    _this.$scope.iframe.src = 'about:blank';
                }
                _this.$scope.iframe = window.open(_this.getSrc(), 'preview');
                _this.$timeout(_this.connect, 1000);
            };
            this.popIn = function () {
                _this.$scope.data.inline = true;
                if (_this.$scope.iframe) {
                    _this.$scope.iframe.close();
                }
                _this.$scope.iframe = document.getElementById('previewWindow');
                _this.$scope.iframe.src = _this.getSrc();
                _this.$timeout(_this.connect, 500);
            };
            this.findComponentByName = function (name) {
                for (var _i = 0, _a = _this.$scope.data.components; _i < _a.length; _i++) {
                    var relation = _a[_i];
                    if (relation.component.name === name) {
                        return relation.component;
                    }
                }
                return null;
            };
            this.enable = function (enabled) {
                _this.$scope.content.attr('enabled', enabled).save(enabled ? _this.gettext('Page enabled') : _this.gettext('Page disabled'));
            };
            this.getSrc = function () {
                return '/admin/pages/preview/' + _this.$scope.content.page_content_id;
            };
            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $scope.content = $scope.contents[0];
            $scope.page = angular.extend({}, $scope.session.site, {
                page_id: $scope.content.page_id,
                page_content_id: $scope.content.page_content_id,
                url: $scope.session.site.host + '/' + ($scope.content.page.slug || '').replace(/^\//, '')
            });
            $scope.data = angular.extend({ model: { global: {}, local: {} }, scopes: {}, components: $scope.content.relations, page: $scope.page }, $scope.content.data_json);
            $scope.tmp = {};
            $scope.scopes = ['global', 'local'];
            angular.forEach($scope.content.relations, function (relation) {
                var component = relation.component;
                if (relation.component.global_data) {
                    $scope.data.model['global'][component.name] = component.global_data.global_data_json;
                }
                if (component.sample_data_json) {
                    for (var type in component.sample_data_json) {
                        if (component.sample_data_json.hasOwnProperty(type)) {
                            if (!$scope.data.model[type][component.name]) {
                                $scope.data.model[type][component.name] = angular.copy(component.sample_data_json[type]);
                            }
                        }
                    }
                }
            });
            $scope.$watch(function () { return _this.socket; }, this.update);
            $scope.$watch('data.model', this.update, true);
            $scope.$watch('data.scopes', this.update, true);
            $scope.$watch('data.component.component_html', this.update);
            $window.onbeforeunload = this.check;
            $timeout(this.splitter, 1000);
            $timeout(this.popIn, 1);
            $timeout(this.checkpoint, 1);
        }
        return ContentEditController;
    }());
    Admin.ContentEditController = ContentEditController;
    angular.module('contentEditApp', ['MinuteFramework', 'AdminApp', 'gettext', 'AngularJsonForm', 'ng.jsoneditor'])
        .controller('contentEditController', ['$scope', '$minute', '$ui', '$timeout', '$window', 'gettext', 'gettextCatalog', ContentEditController]);
})(Admin || (Admin = {}));
