/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />

module App {
    export class ProofListController {
        constructor(public $scope: any, public $minute: any, public $ui: any, public $timeout: ng.ITimeoutService,
                    public gettext: angular.gettext.gettextFunction, public gettextCatalog: angular.gettext.gettextCatalog) {

            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $scope.content = $scope.contents[0];
        }

        create = () => {
            let proof = this.$scope.content.proofs.create().attr('hash', Minute.Utils.randomString(16)).attr('status', 'pending').attr('original_json', angular.toJson(this.$scope.content.data_json));
            this.edit(proof);
        };

        edit = (proof) => {
            this.$ui.popupUrl('/proof-edit-popup.html', false, null, {ctrl: this, proof: proof});
        };

        merge = (proof) => {
            this.$ui.popupUrl('/merge-popup.html', false, null, {ctrl: this, proof: proof, changes: this.diff(proof)});
        };

        diff = (proof) => {
            let updates = proof.attr('proof_json');
            let original = proof.attr('original_json');
            let changes = [];

            angular.forEach(updates, function (v) {
                v.old = _.get(original, v.path);

                if (v.old != v.value) {
                    changes.push(v);
                }
            });

            return changes;
        };

        mergeChanges = (proof, changes) => {
            let original = proof.attr('original_json');

            angular.forEach(changes, function (v) {
                _.set(original, v.path, v.value);
            });

            proof.attr('status', 'merged').save(this.gettext('Proof updated')).then(() => {
                this.$scope.content.attr('data_json', angular.toJson(original)).save(this.gettext('Page content updated')).then(() => {
                    this.$ui.popupUrl('/complete-it-popup.html', true, null, {ctrl: this, content: this.$scope.content});
                });
            });

            this.$ui.closePopup();
        };

        save = (proof) => {
            proof.save(this.gettext('Proof updated'));
            this.$ui.closePopup();
        };
    }

    angular.module('proofListApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('proofListController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', ProofListController]);
}
