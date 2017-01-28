/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />

module Admin {
    let cache: any = {};

    export class PreviewController {
        private lastTimeout;

        constructor(public $scope: any, public $minute: any, public $ui: any, public $timeout: ng.ITimeoutService, public $window: WindowServiceEx) {
            $window.socket = this;
            $scope.data = {html: '', model: {}, components: {}, page: {}};
        }

        ping = () => 'ok';

        replaceTags = function (str, tags) {
            //console.log("str, tags: ", str, tags);
            return str.replace(/%(\w+)%/g, function (all) {
                return tags[all] || all;
            });
        };

        setData = (data, timeout = 100) => {
            this.$timeout.cancel(this.lastTimeout);
            this.lastTimeout = this.$timeout(() => angular.extend(this.$scope.data, data), timeout);
            //console.log("data: ", data);
        };

        getComponentHtml = function (key) {
            var ele = $('div[ng-component="' + (key) + '"][scope="global"]');
            return ele.length > 0 ? this.clean(ele) : '';
        };

        getPageHtml = function () {
            var copy = $('<div>').append($('div#renderer').html());

            copy.find('div[ng-component][scope="global"]').each(function () {
                var ele = $(this);
                ele.replaceWith('<minute-even' + 't name="import.cms.component" theme="' + (ele.attr('theme') || 'global') + '" component="' + ele.attr('ng-component') + '"></minute-event>');
            });

            return this.clean(copy);
        };

        clean = function (ele) {
            let copy = $('<div></div>').append(ele.html());
            copy.find('*').addBack('div').contents().each(function () {
                if (this.nodeType === Node.COMMENT_NODE) {
                    $(this).remove();
                } else {
                    let attrs = 'ng-repeat ng-show ng-if ng-href ng-class ng-component scope ng-bind-html ng-class-even ng-class-odd ng-style ng-src ng-click ng-switch ng-switch-default ng-switch-when';
                    $(this).removeAttr(attrs).removeClass('ng-binding ng-scope ng-isolate-scope');
                }

                if (this.tagName === 'IMG') {
                    let ele = $(this);
                    let src = ele.attr('src');

                    if (/^http/i.test(src)) {
                        ele.attr('src', src.replace(/^https?:/i, ''));
                    }
                }
            });

            return copy.html();
        };
    }

    export class NgComponent implements ng.IDirective {
        restrict = 'A';
        replace = true;
        scope = {};

        constructor(private $compile: ng.ICompileService, public $window: WindowServiceEx) {
        }

        static factory(): ng.IDirectiveFactory {
            var directive: ng.IDirectiveFactory = ($compile: ng.ICompileService, $window: WindowServiceEx) => new NgComponent($compile, $window);
            directive.$inject = ["$compile", "$window"];
            return directive;
        }

        link = ($scope: any, element: ng.IAugmentedJQuery, attrs: any) => {
            $scope.global = this.$window.socket.$scope.data;

            let currentScope = 'local';
            let _find = (name) => {
                for (var relation of $scope.global.components) {
                    if (relation.component.name === name) {
                        return relation.component;
                    }
                }

                return null;
            };

            $scope.$watch(() => {
                let component = _find(attrs.ngComponent);
                return component ? component.component_html : null
            }, (html) => {
                if (html) {
                    element.html(html || 'no html');
                    this.$compile(element.contents())($scope);
                }
            });

            $scope.$watch(() => $scope.global && $scope.global.model ? $scope.global.model[currentScope][attrs.ngComponent] : null, (model) => {
                element.attr('scope', currentScope);
                $scope.model = model;
                $scope.page = $scope.global.page;
            }, true);

            $scope.$watch('global.scopes["' + attrs.ngComponent + '"]', function (scope) {
                currentScope = scope || 'local';
            });
        }
    }

    export function chunkFilter() {
        return function (arr, len) {
            var count = Math.ceil(len);
            var key = angular.toJson(arr) + count.toString();
            var chunk = (array, chunkSize) => {
                return array && array.length ? [].concat.apply([],
                        array.map(function (elem, i) {
                            return i % chunkSize ? [] : [array.slice(i, i + chunkSize)];
                        })
                    ) : [];
            };

            if (typeof(cache[key]) === 'undefined') {
                cache[key] = chunk(arr, count);
            }

            return cache[key];
        }
    }

    export function replaceTags() {
        return function (str, tags) {
            return str.replace(/\{(\w+)\}/g, function (all, match) {
                return tags[match] || all;
            });
        }
    }

    interface WindowServiceEx extends ng.IWindowService {
        socket: any;
    }

    angular.element(document.body).attr('ng-app', "previewApp").attr('ng-controller', "previewController as mainCtrl");

    angular.module('previewApp', ['MinuteFramework', 'ngSanitize', 'AngularMarkdown', 'AngularDynamicHtml'])
        .directive('ngComponent', NgComponent.factory())
        .filter('chunk', chunkFilter)
        .filter('tags', replaceTags)
        .controller('previewController', ['$scope', '$minute', '$ui', '$timeout', '$window', PreviewController]);
}