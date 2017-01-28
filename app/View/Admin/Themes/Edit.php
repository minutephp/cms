<div class="content-wrapper ng-cloak" ng-app="themeEditApp" ng-controller="themeEditController as mainCtrl" ng-init="init()">
    <div class="admin-content" minute-hot-keys="{'ctrl+s':mainCtrl.save}">
        <section class="content-header">
            <h1>
                <span translate="" ng-show="!theme.theme_id">Create new</span>
                <span translate="" ng-show="!!theme.theme_id">Edit</span>
                <span translate="">theme</span>
            </h1>

            <ol class="breadcrumb">
                <li><a href="" ng-href="/admin"><i class="fa fa-dashboard"></i> <span translate="">Admin</span></a></li>
                <li><a href="" ng-href="/admin/themes"><i class="fa fa-theme"></i> <span translate="">Themes</span></a></li>
                <li class="active"><i class="fa fa-edit"></i> <span translate="">Edit theme</span></li>
            </ol>
        </section>

        <section class="content">
            <form class="form-horizontal" name="themeForm" ng-submit="mainCtrl.save()">
                <div class="box box-{{themeForm.$valid && 'success' || 'danger'}}">
                    <div class="box-header with-border">
                        <span translate="" ng-show="!theme.theme_id">New theme</span>
                        <span ng-show="!!theme.theme_id"><span translate="">Edit</span> {{theme.name}}</span>
                    </div>

                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="name"><span translate="">Theme name:</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" placeholder="Enter Theme name" ng-model="theme.name" ng-required="true">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="layout">
                                <span translate="">Layout HTML:</span>
                            </label>
                            <div class="col-sm-10">
                                <textarea class="form-control" rows="15" placeholder="Enter Layout HTML" ng-model="theme.layout_html" ng-required="false"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span translate="">Assets:</span></label>
                            <div class="col-sm-10">
                                <div class="row" style="margin-bottom: 10px" ng-repeat="asset in theme.assets">
                                    <div class="col-sm-5"><input type="text" class="form-control input-sm" placeholder="Asset name (alphanumeric only)" ng-model="asset.name" ng-required="true"></div>
                                    <div class="col-sm-4"><input type="text" class="form-control input-sm" placeholder="text/css, image/jpg, video/mp4, etc" ng-model="asset.type" ng-required="true">
                                    </div>
                                    <div class="col-sm-3">
                                        <minute-uploader ng-model="asset.url" type="other" preview="true" remove="false" label="Upload.."></minute-uploader>
                                        <a href="" ng-click="asset.removeConfirm()" class="pull-right">&times;</a>
                                    </div>

                                    <div class="col-sm-12" ng-if="!!asset.theme_asset_id">
                                        <p class="help-block text-sm"><i class="fa fa-caret-right"></i> <span class="fake-link">{{session.site.host}}/static/themes/{{theme.name}}/assets/{{asset.name}}</span></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12">
                                        <button type="button" class="btn btn-flat btn-default btn-sm" ng-click="mainCtrl.addAsset()">
                                            <i class="fa fa-plus-circle"></i> <span translate="">Add new asset</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="box-footer with-border">
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-flat btn-primary">
                                    <span translate="" ng-show="!theme.theme_id">Create</span>
                                    <span translate="" ng-show="!!theme.theme_id">Update</span>
                                    <span translate="">theme</span>
                                    <i class="fa fa-fw fa-angle-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>
