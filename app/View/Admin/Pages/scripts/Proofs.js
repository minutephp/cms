/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />
var App;
(function (App) {
    var ProofListController = (function () {
        function ProofListController($scope, $minute, $ui, $timeout, gettext, gettextCatalog) {
            var _this = this;
            this.$scope = $scope;
            this.$minute = $minute;
            this.$ui = $ui;
            this.$timeout = $timeout;
            this.gettext = gettext;
            this.gettextCatalog = gettextCatalog;
            this.create = function () {
                var proof = _this.$scope.content.proofs.create().attr('hash', Minute.Utils.randomString(16)).attr('status', 'pending').attr('original_json', angular.toJson(_this.$scope.content.data_json));
                _this.edit(proof);
            };
            this.edit = function (proof) {
                _this.$ui.popupUrl('/proof-edit-popup.html', false, null, { ctrl: _this, proof: proof });
            };
            this.merge = function (proof) {
                _this.$ui.popupUrl('/merge-popup.html', false, null, { ctrl: _this, proof: proof, changes: _this.diff(proof) });
            };
            this.diff = function (proof) {
                var updates = proof.attr('proof_json');
                var original = proof.attr('original_json');
                var changes = [];
                angular.forEach(updates, function (v) {
                    v.old = _.get(original, v.path);
                    if (v.old != v.value) {
                        changes.push(v);
                    }
                });
                return changes;
            };
            this.mergeChanges = function (proof, changes) {
                var original = proof.attr('original_json');
                angular.forEach(changes, function (v) {
                    _.set(original, v.path, v.value);
                });
                proof.attr('status', 'merged').save(_this.gettext('Proof updated')).then(function () {
                    _this.$scope.content.attr('data_json', angular.toJson(original)).save(_this.gettext('Page content updated')).then(function () {
                        _this.$ui.popupUrl('/complete-it-popup.html', true, null, { ctrl: _this, content: _this.$scope.content });
                    });
                });
                _this.$ui.closePopup();
            };
            this.save = function (proof) {
                proof.save(_this.gettext('Proof updated'));
                _this.$ui.closePopup();
            };
            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $scope.content = $scope.contents[0];
        }
        return ProofListController;
    }());
    App.ProofListController = ProofListController;
    angular.module('proofListApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('proofListController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', ProofListController]);
})(App || (App = {}));
