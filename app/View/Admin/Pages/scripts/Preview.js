/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />
var Admin;
(function (Admin) {
    var cache = {};
    var PreviewController = (function () {
        function PreviewController($scope, $minute, $ui, $timeout, $window) {
            var _this = this;
            this.$scope = $scope;
            this.$minute = $minute;
            this.$ui = $ui;
            this.$timeout = $timeout;
            this.$window = $window;
            this.ping = function () { return 'ok'; };
            this.replaceTags = function (str, tags) {
                //console.log("str, tags: ", str, tags);
                return str.replace(/%(\w+)%/g, function (all) {
                    return tags[all] || all;
                });
            };
            this.setData = function (data, timeout) {
                if (timeout === void 0) { timeout = 100; }
                _this.$timeout.cancel(_this.lastTimeout);
                _this.lastTimeout = _this.$timeout(function () { return angular.extend(_this.$scope.data, data); }, timeout);
                //console.log("data: ", data);
            };
            this.getComponentHtml = function (key) {
                var ele = $('div[ng-component="' + (key) + '"][scope="global"]');
                return ele.length > 0 ? this.clean(ele) : '';
            };
            this.getPageHtml = function () {
                var copy = $('<div>').append($('div#renderer').html());
                copy.find('div[ng-component][scope="global"]').each(function () {
                    var ele = $(this);
                    ele.replaceWith('<minute-even' + 't name="import.cms.component" theme="' + (ele.attr('theme') || 'global') + '" component="' + ele.attr('ng-component') + '"></minute-event>');
                });
                return this.clean(copy);
            };
            this.clean = function (ele) {
                var copy = $('<div></div>').append(ele.html());
                copy.find('*').addBack('div').contents().each(function () {
                    if (this.nodeType === Node.COMMENT_NODE) {
                        $(this).remove();
                    }
                    else {
                        var attrs = 'ng-repeat ng-show ng-if ng-href ng-class ng-component scope ng-bind-html ng-class-even ng-class-odd ng-style ng-src ng-click ng-switch ng-switch-default ng-switch-when';
                        $(this).removeAttr(attrs).removeClass('ng-binding ng-scope ng-isolate-scope');
                    }
                    if (this.tagName === 'IMG') {
                        var ele_1 = $(this);
                        var src = ele_1.attr('src');
                        if (/^http/i.test(src)) {
                            ele_1.attr('src', src.replace(/^https?:/i, ''));
                        }
                    }
                });
                return copy.html();
            };
            $window.socket = this;
            $scope.data = { html: '', model: {}, components: {}, page: {} };
        }
        return PreviewController;
    }());
    Admin.PreviewController = PreviewController;
    var NgComponent = (function () {
        function NgComponent($compile, $window) {
            var _this = this;
            this.$compile = $compile;
            this.$window = $window;
            this.restrict = 'A';
            this.replace = true;
            this.scope = {};
            this.link = function ($scope, element, attrs) {
                $scope.global = _this.$window.socket.$scope.data;
                var currentScope = 'local';
                var _find = function (name) {
                    for (var _i = 0, _a = $scope.global.components; _i < _a.length; _i++) {
                        var relation = _a[_i];
                        if (relation.component.name === name) {
                            return relation.component;
                        }
                    }
                    return null;
                };
                $scope.$watch(function () {
                    var component = _find(attrs.ngComponent);
                    return component ? component.component_html : null;
                }, function (html) {
                    if (html) {
                        element.html(html || 'no html');
                        _this.$compile(element.contents())($scope);
                    }
                });
                $scope.$watch(function () { return $scope.global && $scope.global.model ? $scope.global.model[currentScope][attrs.ngComponent] : null; }, function (model) {
                    element.attr('scope', currentScope);
                    $scope.model = model;
                    $scope.page = $scope.global.page;
                }, true);
                $scope.$watch('global.scopes["' + attrs.ngComponent + '"]', function (scope) {
                    currentScope = scope || 'local';
                });
            };
        }
        NgComponent.factory = function () {
            var directive = function ($compile, $window) { return new NgComponent($compile, $window); };
            directive.$inject = ["$compile", "$window"];
            return directive;
        };
        return NgComponent;
    }());
    Admin.NgComponent = NgComponent;
    function chunkFilter() {
        return function (arr, len) {
            var count = Math.ceil(len);
            var key = angular.toJson(arr) + count.toString();
            var chunk = function (array, chunkSize) {
                return array && array.length ? [].concat.apply([], array.map(function (elem, i) {
                    return i % chunkSize ? [] : [array.slice(i, i + chunkSize)];
                })) : [];
            };
            if (typeof (cache[key]) === 'undefined') {
                cache[key] = chunk(arr, count);
            }
            return cache[key];
        };
    }
    Admin.chunkFilter = chunkFilter;
    function replaceTags() {
        return function (str, tags) {
            return str.replace(/\{(\w+)\}/g, function (all, match) {
                return tags[match] || all;
            });
        };
    }
    Admin.replaceTags = replaceTags;
    angular.element(document.body).attr('ng-app', "previewApp").attr('ng-controller', "previewController as mainCtrl");
    angular.module('previewApp', ['MinuteFramework', 'ngSanitize', 'AngularMarkdown', 'AngularDynamicHtml'])
        .directive('ngComponent', NgComponent.factory())
        .filter('chunk', chunkFilter)
        .filter('tags', replaceTags)
        .controller('previewController', ['$scope', '$minute', '$ui', '$timeout', '$window', PreviewController]);
})(Admin || (Admin = {}));
