<div class="content-wrapper ng-cloak" ng-app="proofListApp" ng-controller="proofListController as mainCtrl" ng-init="init()">
    <div class="admin-content">
        <section class="content-header">
            <h1><span translate="">List of proofs</span> <small>(for <a ng-href="{{content.page.slug}}" target="_blank">{{content.page.name}}</a>)</small></h1>

            <ol class="breadcrumb">
                <li><a href="" ng-href="/admin"><i class="fa fa-dashboard"></i> <span translate="">Admin</span></a></li>
                <li class="active"><i class="fa fa-proof"></i> <span translate="">Proofreads</span></li>
            </ol>
        </section>

        <section class="content">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <span translate="">List of proofreads</span>
                    </h3>

                    <div class="box-tools">
                        <button class="btn btn-sm btn-primary btn-flat" ng-click="mainCtrl.create()">
                            <i class="fa fa-plus-circle"></i> <span translate="">Create new proof</span>
                        </button>
                    </div>
                </div>

                <div class="box-body">
                    <div class="list-group">
                        <div class="list-group-item list-group-item-bar list-group-item-bar-{{proof.status === 'proofed' && 'success' || 'danger'}}"
                             ng-repeat="proof in content.proofs" ng-click-container="mainCtrl.actions(proof)">
                            <div class="pull-left">
                                <h4 class="list-group-item-heading"><b>{{proof.status | ucfirst}}</b>: {{proof.name | ucfirst}}</h4>
                                <p class="list-group-item-text hidden-xs">
                                    <span translate="">Last updated:</span> {{proof.updated_at | timeAgo}}.
                                </p>
                                <p class="list-group-item-text hidden-xs" ng-show="proof.status == 'pending'">
                                    <span translate="">Proofreader's link:</span> <span class="fake-link">{{session.site.host}}/_proofread/{{proof.hash}}</span>
                                </p>
                            </div>
                            <div class="md-actions pull-right">
                                <button class="btn btn-default btn-flat btn-sm" ng-click="mainCtrl.edit(proof)" ng-show="proof.status == 'pending'">
                                    <i class="fa fa-pencil-square-o"></i> <span translate="">Edit..</span>
                                </button>
                                <button class="btn btn-default btn-flat btn-sm" ng-click="mainCtrl.merge(proof)" ng-show="proof.status == 'proofed'">
                                    <i class="fa fa-exchange"></i> <span translate="">Merge..</span>
                                </button>
                            </div>

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <div class="row">
                        <div class="col-xs-12 col-md-6 col-md-push-6">
                            <minute-pager class="pull-right" on="content.proofs" no-results="{{'No proofs found' | translate}}"></minute-pager>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script type="text/ng-template" id="/proof-edit-popup.html">
        <div class="box">
            <div class="box-header with-border">
                <b class="pull-left">
                    <span translate="" ng-show="!proof.page_proof_id">Create proof</span>
                    <span translate="" ng-show="!!proof.page_proof_id">Edit proof</span>
                </b>
                <a class="pull-right close-button" href=""><i class="fa fa-times"></i></a>
                <div class="clearfix"></div>
            </div>

            <form class="form-horizontal" ng-submit="ctrl.save(proof)" name="proofForm">
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="name"><span translate="">Name:</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" placeholder="Proofread for John" ng-model="proof.name" ng-required="true" minlength="3">
                        </div>
                    </div>
                </div>

                <div class="box-footer with-border">
                    <button type="submit" class="btn btn-flat btn-primary pull-right" ng-disabled="!proofForm.$valid">
                        <span translate>Save proof</span> <i class="fa fa-fw fa-angle-right"></i>
                    </button>
                </div>

            </form>
        </div>
    </script>

    <script type="text/ng-template" id="/merge-popup.html">
        <div class="box">
            <div class="box-header with-border">
                <b class="pull-left"><span translate="">List of changes</span></b>
                <a class="pull-right close-button" href=""><i class="fa fa-times"></i></a>
                <div class="clearfix"></div>
            </div>

            <div class="box-body">
                <div class="pre-scrollable">
                    <div ng-show="!changes.length"><span translate="">No changes were made.</span></div>

                    <div class="list-group-item list-group-item-bar" ng-repeat="change in changes">
                        <a class="pull-right" ng-click="changes.splice($index, 1)" tooltip="Ignore">&times;</a>

                        <p class="list-group-item-text">
                            <b translate="">Original:</b> {{change.old}}
                        </p>
                        <p class="list-group-item-text">
                            <b translate="">Changed:</b> {{change.value}}
                        </p>

                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <div class="box-footer with-border">
                <button type="button" class="btn btn-flat btn-primary pull-right" ng-disabled="!changes.length" ng-click="ctrl.mergeChanges(proof, changes)">
                    <span translate>Merge changes</span> <i class="fa fa-fw fa-angle-right"></i>
                </button>
            </div>
        </div>
    </script>

    <script type="text/ng-template" id="/complete-it-popup.html">
        <div class="box">
            <div class="box-header with-border">
                <b class="pull-left"><span translate="">Finalize merge</span></b>
                <a class="pull-right close-button" href=""><i class="fa fa-times"></i></a>
                <div class="clearfix"></div>
            </div>

            <div class="box-body">
                <span translate="">The page data has been updated. To finalize changes, please click the button below to open the page editor. Then click the "Update content" button to
                    re-compile your page to make these changes permanent.</span>

                <p>&nbsp;</p>

                <p><a class="btn btn-primary btn-lg" ng-href="/admin/pages/contents/edit/{{content.page_content_id}}"><span translate="">Open page in editor</span></a></p>
            </div>
        </div>
    </script>

</div>
