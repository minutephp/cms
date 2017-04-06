/// <reference path="../../../../../../../public/static/bower_components/minute/_all.d.ts" />

module Admin {
    export class PageListController {
        constructor(public $scope: any, public $minute: any, public $ui: any, public $timeout: ng.ITimeoutService,
                    public gettext: angular.gettext.gettextFunction, public gettextCatalog: angular.gettext.gettextCatalog) {

            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
        }

        actions = (item) => {
            let gettext = this.gettext;
            let actions = [
                {'text': gettext('Edit..'), 'icon': 'fa-edit', 'hint': gettext('Edit page'), 'href': '/admin/pages/edit/' + item.page_id},
                {'text': gettext('Preview..'), 'icon': 'fa-eye', 'hint': gettext('Preview variation'), 'href': '/admin/pages/render/' + item.page_id, 'target': '_blank'},
                {'text': gettext('Clone..'), 'icon': 'fa-copy', 'hint': gettext('Clone page'), 'click': 'ctrl.clone(item)'},
                {'text': gettext('Redirect..'), 'icon': 'fa-arrow-right', 'hint': gettext('Redirect page'), 'click': 'ctrl.redirect(item)'},
                {'text': gettext('Remove'), 'icon': 'fa-trash', 'hint': gettext('Delete this page'), 'click': 'item.removeConfirm("Removed")'},
            ];

            this.$ui.bottomSheet(actions, gettext('Actions for: ') + item.name, this.$scope, {item: item, ctrl: this});
        };

        redirect = (page) => {
            this.$ui.prompt(this.gettext('URL to which you want to redirect this page to?'), page.redirect).then(
                (value)=> page.attr('redirect', value).save(this.gettext('Redirection created')),
                (fail) => fail === null ? page.attr('redirect', null).save(this.gettext('Redirection removed')) : 1
            );
        };

        clone = (page) => {
            let gettext = this.gettext;

            page.contents.setItemsPerPage(99, false);
            page.contents.reloadAll(true).then(() => {
                this.$ui.prompt(gettext('Enter new slug'), gettext('/new-slug')).then(function (slug) {
                    page.clone().attr('slug', slug).save(gettext('Page duplicated')).then(function (copy) {
                        angular.forEach(page.contents, (content) => copy.item.contents.cloneItem(content).save());
                    });
                });
            });
        }
    }

    angular.module('pageListApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('pageListController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', PageListController]);
}

