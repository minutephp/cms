/// <reference path="../../../../../../../public/static/bower_components/minute/_all.d.ts" />

module App {
    export class PageController {
        public $:any = window['jQuery'];
        public WOW:any = window['WOW'];

        constructor(public $scope:any, public $minute:any, public $ui:any, public $timeout:ng.ITimeoutService, public $window:ng.IWindowService) {
            this.bindSpecialLinks();
            this.animate();

            let tooltips:any = $('[data-toggle="tooltip"]');
            let selector = '#navbar a[href="' + location.pathname + '"]';

            tooltips.tooltip();
            $(selector).parent('li').addClass('active');

            for (let event of ['login', 'signup']) {
                let redir = $('div[data-' + event + '-redirect]').data(event + '-redirect');
                if (redir) {
                    $scope.$on('session_user_' + event, () => top.location.href = redir);
                }
            }
        }

        private bindSpecialLinks() {
            var iframe:any = $('#ytframe');
            var popup:any = $('#ytPopupVideo').on('hidden.bs.modal', () => iframe.attr('src', 'about:blank'));

            for (let type of ['login', 'signup']) {
                var selector = 'a[href$="#/' + type + '"],[data-link-type="' + type + '"]';
                $(selector).each((i, a) => $(a).click(() => {
                    this.$scope.session[type]();
                    return false;
                }));
            }

            $('a[href*="youtube.com"],a[href*="youtu.be"]').each((i, a:any) => {
                var href = a.href;
                var match = href.match(/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/);
                if (match && match[7].length == 11) {
                    $(a).click(() => {
                        iframe.attr('src', 'https://www.youtube.com/embed/' + match[7] + '?rel=0&amp;autoplay=1&amp;autohide=1&amp;hd=1&amp;showinfo=0');
                        popup.modal('show');
                        return false;
                    });
                }
            });
        }

        private animate() {
            if (angular.isDefined(this.WOW)) {
                new this.WOW().init();
            }
        }
    }

    angular.module('pageApp', ['MinuteFramework'])
        .controller('pageController', ['$scope', '$minute', '$ui', '$timeout', '$window', PageController]);

    angular.element(document).ready(function() {
        var body = angular.element('body');
        body.addClass('ng-cloak').attr('ng-controller', "pageController as mainCtrl");
        angular.bootstrap(body, ['pageApp']);
    });
}

