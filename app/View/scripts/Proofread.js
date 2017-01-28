/// <reference path="E:/var/Dropbox/projects/minutephp/public/static/bower_components/minute/_all.d.ts" />
var App;
(function (App) {
    var ProofreadController = (function () {
        function ProofreadController($scope, $minute, $ui, $timeout, gettext, gettextCatalog) {
            var _this = this;
            this.$scope = $scope;
            this.$minute = $minute;
            this.$ui = $ui;
            this.$timeout = $timeout;
            this.gettext = gettext;
            this.gettextCatalog = gettextCatalog;
            this.dotify = function (obj, current) {
                if (current === void 0) { current = null; }
                for (var key in obj) {
                    var value = obj[key];
                    var newKey = (current ? current + "." + key : key); // joined key with dot
                    if (!/^model.global/.test(newKey)) {
                        if (value && typeof value === "object") {
                            _this.dotify(value, newKey); // it's a nested object, so do it again
                        }
                        else {
                            var text = $('<div>').html(value).text();
                            if (value && /(\w+\s+){3}/.test(text)) {
                                var lines = value ? value.split(/\r\n|\r|\n/).length : 1;
                                _this.$scope.fields.push({ path: newKey, value: value, name: _this.unCamelize(key), lines: lines }); // it's not an object, so set the property
                            }
                        }
                    }
                }
            };
            this.unCamelize = function (stringValue) {
                var string = stringValue ? stringValue.replace(/([A-Z]+)/g, " $1").replace(/([A-Z][a-z])/g, " $1") : '';
                return string.charAt(0).toUpperCase() + string.slice(1);
            };
            this.save = function () {
                _this.$scope.proof.attr('status', 'proofed').attr('proof_json', _this.$scope.fields).save('Thank you. Proof has been saved.');
            };
            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $scope.proof = $scope.proofs[0];
            $scope.fields = [];
            this.dotify($scope.proof.original_json);
            console.log("$scope.proof.original_json: ", $scope.proof.data_json);
        }
        return ProofreadController;
    }());
    App.ProofreadController = ProofreadController;
    angular.module('ProofreadApp', ['MinuteFramework', 'gettext'])
        .controller('ProofreadController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', ProofreadController]);
})(App || (App = {}));
