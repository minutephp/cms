/// <reference path="../../../../../../../public/static/bower_components/minute/_all.d.ts" />
var App;
(function (App) {
    var PageController = (function () {
        function PageController($scope, $minute, $ui, $timeout, $window) {
            this.$scope = $scope;
            this.$minute = $minute;
            this.$ui = $ui;
            this.$timeout = $timeout;
            this.$window = $window;
            this.$ = window['jQuery'];
            this.WOW = window['WOW'];
            this.bindSpecialLinks();
            this.animate();
            var tooltips = $('[data-toggle="tooltip"]');
            var selector = '#navbar a[href="' + location.pathname + '"]';
            tooltips.tooltip();
            $(selector).parent('li').addClass('active');
            var _loop_1 = function(event_1) {
                var redir = $('div[data-' + event_1 + '-redirect]').data(event_1 + '-redirect');
                if (redir) {
                    $scope.$on('session_user_' + event_1, function () { return top.location.href = redir; });
                }
            };
            for (var _i = 0, _a = ['login', 'signup']; _i < _a.length; _i++) {
                var event_1 = _a[_i];
                _loop_1(event_1);
            }
        }
        PageController.prototype.bindSpecialLinks = function () {
            var _this = this;
            var iframe = $('#ytframe');
            var popup = $('#ytPopupVideo').on('hidden.bs.modal', function () { return iframe.attr('src', 'about:blank'); });
            var _loop_2 = function(type) {
                selector = 'a[href$="#/' + type + '"],[data-link-type="' + type + '"]';
                $(selector).each(function (i, a) { return $(a).click(function () {
                    _this.$scope.session[type]();
                    return false;
                }); });
            };
            var selector;
            for (var _i = 0, _a = ['login', 'signup']; _i < _a.length; _i++) {
                var type = _a[_i];
                _loop_2(type);
            }
            $('a[href*="youtube.com"],a[href*="youtu.be"]').each(function (i, a) {
                var href = a.href;
                var match = href.match(/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/);
                if (match && match[7].length == 11) {
                    $(a).click(function () {
                        iframe.attr('src', 'https://www.youtube.com/embed/' + match[7] + '?rel=0&amp;autoplay=1&amp;autohide=1&amp;hd=1&amp;showinfo=0');
                        popup.modal('show');
                        return false;
                    });
                }
            });
        };
        PageController.prototype.animate = function () {
            if (angular.isDefined(this.WOW)) {
                new this.WOW().init();
            }
        };
        return PageController;
    }());
    App.PageController = PageController;
    angular.module('pageApp', ['MinuteFramework'])
        .controller('pageController', ['$scope', '$minute', '$ui', '$timeout', '$window', PageController]);
    angular.element(document).ready(function () {
        var body = angular.element('body');
        body.addClass('ng-cloak').attr('ng-controller', "pageController as mainCtrl");
        angular.bootstrap(body, ['pageApp']);
    });
})(App || (App = {}));
