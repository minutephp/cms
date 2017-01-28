/// <reference path="E:/var/Dropbox/projects/minutephp/public/static/bower_components/minute/_all.d.ts" />

module App {
    export class ProofreadController {
        constructor(public $scope: any, public $minute: any, public $ui: any, public $timeout: ng.ITimeoutService,
                    public gettext: angular.gettext.gettextFunction, public gettextCatalog: angular.gettext.gettextCatalog) {

            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');

            $scope.proof = $scope.proofs[0];
            $scope.fields = [];

            this.dotify($scope.proof.original_json);
            console.log("$scope.proof.original_json: ", $scope.proof.data_json);
        }

        dotify = (obj, current = null) => {
            for (var key in obj) {
                var value = obj[key];
                var newKey = (current ? current + "." + key : key);  // joined key with dot

                if (!/^model.global/.test(newKey)) {
                    if (value && typeof value === "object") {
                        this.dotify(value, newKey);  // it's a nested object, so do it again
                    } else {
                        let text = $('<div>').html(value).text();

                        if (value && /(\w+\s+){3}/.test(text)) {
                            let lines = value ? value.split(/\r\n|\r|\n/).length : 1;
                            this.$scope.fields.push({path: newKey, value: value, name: this.unCamelize(key), lines: lines}); // it's not an object, so set the property
                        }
                    }
                }
            }
        };

        unCamelize = (stringValue) => {
            var string = stringValue ? stringValue.replace(/([A-Z]+)/g, " $1").replace(/([A-Z][a-z])/g, " $1") : '';
            return string.charAt(0).toUpperCase() + string.slice(1);
        };

        save = () => {
            this.$scope.proof.attr('status', 'proofed').attr('proof_json', this.$scope.fields).save('Thank you. Proof has been saved.');
        };
    }

    angular.module('ProofreadApp', ['MinuteFramework', 'gettext'])
        .controller('ProofreadController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', ProofreadController]);
}
