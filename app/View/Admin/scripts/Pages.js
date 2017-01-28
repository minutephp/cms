/// <reference path="../../../../../../../public/static/bower_components/minute/_all.d.ts" />
var Admin;
(function (Admin) {
    var PageListController = (function () {
        function PageListController($scope, $minute, $ui, $timeout, gettext, gettextCatalog) {
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
                    { 'text': gettext('Edit..'), 'icon': 'fa-edit', 'hint': gettext('Edit page'), 'href': '/admin/pages/edit/' + item.page_id },
                    { 'text': gettext('Preview..'), 'icon': 'fa-eye', 'hint': gettext('Preview variation'), 'href': '/admin/pages/render/' + item.page_id, 'target': '_blank' },
                    { 'text': gettext('Clone..'), 'icon': 'fa-copy', 'hint': gettext('Clone page'), 'click': 'ctrl.clone(item)' },
                    { 'text': gettext('Redirect..'), 'icon': 'fa-arrow-right', 'hint': gettext('Redirect page'), 'click': 'ctrl.redirect(item)' },
                    { 'text': gettext('Remove'), 'icon': 'fa-trash', 'hint': gettext('Delete this page'), 'click': 'item.removeConfirm("Removed")' },
                ];
                _this.$ui.bottomSheet(actions, gettext('Actions for: ') + item.name, _this.$scope, { item: item, ctrl: _this });
            };
            this.redirect = function (page) {
                _this.$ui.prompt(_this.gettext('URL to which you want to redirect this page to?'), page.redirect).then(function (value) { return page.attr('redirect', value).save(_this.gettext('Redirection created')); }, function (fail) { return fail === null ? page.attr('redirect', null).save(_this.gettext('Redirection removed')) : 1; });
            };
            this.clone = function (page) {
                var gettext = _this.gettext;
                page.contents.setItemsPerPage(99, false);
                page.contents.reloadAll(true).then(function () {
                    _this.$ui.prompt(gettext('Enter new slug'), gettext('/new-slug')).then(function (slug) {
                        page.clone().attr('slug', slug).save(gettext('Page duplicated')).then(function (copy) {
                            angular.forEach(page.contents, function (content) { return copy.item.contents.cloneItem(content).save(); });
                        });
                    });
                });
            };
            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
        }
        return PageListController;
    }());
    Admin.PageListController = PageListController;
    angular.module('pageListApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('pageListController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', PageListController]);
})(Admin || (Admin = {}));
