/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />

module Page {
    export class ContentListController {
        constructor(public $scope: any, public $minute: any, public $ui: any, public $timeout: ng.ITimeoutService,
                    public gettext: angular.gettext.gettextFunction, public gettextCatalog: angular.gettext.gettextCatalog) {

            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $scope.page = $scope.pages[0] || $scope.pages.create().attr('enabled', true).attr('feed', true).attr('type', 'page');
            $scope.data = {showProps: false, types: [{value: 'page', label: 'Normal page'}, {value: 'support', label: 'Support page'}, {value: 'blog', label: 'Blog post'}]};
        }

        actions = (item) => {
            let gettext = this.gettext;
            let content_id = item.page_content_id;
            let actions = [
                {'text': gettext('Edit..'), 'icon': 'fa-edit', 'hint': gettext('Edit content'), 'click': 'ctrl.editPage(item)'},
                {'text': gettext('Rename'), 'icon': 'fa-pencil', 'hint': gettext('Rename this variation'), 'click': 'ctrl.renamePage(item)'},
                {'text': gettext('Preview..'), 'icon': 'fa-eye', 'hint': gettext('Preview variation'), 'href': '/admin/pages/render/' + content_id, 'target': '_blank'},
                {'text': gettext('Template'), 'icon': 'fa-paint-brush', 'hint': gettext('Change template'), 'click': 'ctrl.pickTemplate(item)'},
                {'text': gettext('Proof'), 'icon': 'fa-check-square-o', 'hint': gettext('Proofread page'), 'href': '/admin/pages/proofs/' + content_id, 'target': '_blank'},
                {'text': gettext('Clone'), 'icon': 'fa-copy', 'hint': gettext('Clone content'), 'click': 'ctrl.clone(item)'},
                {'text': gettext('Remove'), 'icon': 'fa-trash', 'hint': gettext('Delete this content'), 'click': 'item.removeConfirm("Removed")'},
            ];

            this.$ui.bottomSheet(actions, gettext('Actions for ') + (item.name || gettext('content')), this.$scope, {item: item, ctrl: this});
        };

        editPage = (content) => {
            if (!content.attr('theme_template_id')) {
                this.pickTemplate(content);
            } else {
                top.location.href = '/admin/pages/contents/edit/' + content.page_content_id;
            }
        };

        savePage = () => {
            this.$scope.page.save(this.gettext("Page saved")).then(() => {
                this.$scope.data.showProps = false;

                if (!this.$scope.page.contents.length) {
                    this.addContent();
                }
            });
        };

        addContent = () => {
            this.$scope.page.contents.create().attr('enabled', true).attr('name', '').save(this.gettext('Content created')).then((content) => this.pickTemplate(content.item));
        };

        pickTemplate = (content) => {
            this.$ui.popupUrl('/pick-template.html', false, this.$scope, {content: content});
        };

        setTemplate = (content, template) => {
            content.attr('theme_template_id', template.attr('theme_template_id')).save(this.gettext('Content updated'));
            this.$ui.closePopup();
        };

        renamePage = (item) => {
            this.$ui.prompt('New name', 'my-new-name').then((name)=> {
                item.attr('name', name).save(this.gettext('Saved'));
            });
        };

        clone = (content) => {
            content.clone().save(this.gettext('Content cloned'));
        }
    }

    angular.module('contentListApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('contentListController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', ContentListController]);
}
