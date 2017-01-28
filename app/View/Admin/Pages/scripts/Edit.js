/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />
var Page;
(function (Page) {
    var ContentListController = (function () {
        function ContentListController($scope, $minute, $ui, $timeout, gettext, gettextCatalog) {
            var _this = this;
            this.$scope = $scope;
            this.$minute = $minute;
            this.$ui = $ui;
            this.$timeout = $timeout;
            this.gettext = gettext;
            this.gettextCatalog = gettextCatalog;
            this.actions = function (item) {
                var gettext = _this.gettext;
                var content_id = item.page_content_id;
                var actions = [
                    { 'text': gettext('Edit..'), 'icon': 'fa-edit', 'hint': gettext('Edit content'), 'click': 'ctrl.editPage(item)' },
                    { 'text': gettext('Rename'), 'icon': 'fa-pencil', 'hint': gettext('Rename this variation'), 'click': 'ctrl.renamePage(item)' },
                    { 'text': gettext('Preview..'), 'icon': 'fa-eye', 'hint': gettext('Preview variation'), 'href': '/admin/pages/render/' + content_id, 'target': '_blank' },
                    { 'text': gettext('Template'), 'icon': 'fa-paint-brush', 'hint': gettext('Change template'), 'click': 'ctrl.pickTemplate(item)' },
                    { 'text': gettext('Proof'), 'icon': 'fa-check-square-o', 'hint': gettext('Proofread page'), 'href': '/admin/pages/proofs/' + content_id, 'target': '_blank' },
                    { 'text': gettext('Clone'), 'icon': 'fa-copy', 'hint': gettext('Clone content'), 'click': 'ctrl.clone(item)' },
                    { 'text': gettext('Remove'), 'icon': 'fa-trash', 'hint': gettext('Delete this content'), 'click': 'item.removeConfirm("Removed")' },
                ];
                _this.$ui.bottomSheet(actions, gettext('Actions for ') + (item.name || gettext('content')), _this.$scope, { item: item, ctrl: _this });
            };
            this.editPage = function (content) {
                if (!content.attr('theme_template_id')) {
                    _this.pickTemplate(content);
                }
                else {
                    top.location.href = '/admin/pages/contents/edit/' + content.page_content_id;
                }
            };
            this.savePage = function () {
                _this.$scope.page.save(_this.gettext("Page saved")).then(function () {
                    _this.$scope.data.showProps = false;
                    if (!_this.$scope.page.contents.length) {
                        _this.addContent();
                    }
                });
            };
            this.addContent = function () {
                _this.$scope.page.contents.create().attr('enabled', true).attr('name', '').save(_this.gettext('Content created')).then(function (content) { return _this.pickTemplate(content.item); });
            };
            this.pickTemplate = function (content) {
                _this.$ui.popupUrl('/pick-template.html', false, _this.$scope, { content: content });
            };
            this.setTemplate = function (content, template) {
                content.attr('theme_template_id', template.attr('theme_template_id')).save(_this.gettext('Content updated'));
                _this.$ui.closePopup();
            };
            this.renamePage = function (item) {
                _this.$ui.prompt('New name', 'my-new-name').then(function (name) {
                    item.attr('name', name).save(_this.gettext('Saved'));
                });
            };
            this.clone = function (content) {
                content.clone().save(_this.gettext('Content cloned'));
            };
            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $scope.page = $scope.pages[0] || $scope.pages.create().attr('enabled', true).attr('feed', true).attr('type', 'page');
            $scope.data = { showProps: false, types: [{ value: 'page', label: 'Normal page' }, { value: 'support', label: 'Support page' }, { value: 'blog', label: 'Blog post' }] };
        }
        return ContentListController;
    }());
    Page.ContentListController = ContentListController;
    angular.module('contentListApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('contentListController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', ContentListController]);
})(Page || (Page = {}));
