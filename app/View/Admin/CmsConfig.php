<div class="content-wrapper ng-cloak" ng-app="cmsConfigApp" ng-controller="cmsConfigController as mainCtrl" ng-init="init()">
    <div class="admin-content">
        <section class="content-header">
            <h1>
                <span translate="">Cms settings</span>
            </h1>

            <ol class="breadcrumb">
                <li><a href="" ng-href="/admin"><i class="fa fa-dashboard"></i> <span translate="">Admin</span></a></li>
                <li class="active"><i class="fa fa-cog"></i> <span translate="">Cms settings</span></li>
            </ol>
        </section>

        <section class="content">
            <form class="form-horizontal" name="cmsForm" ng-submit="mainCtrl.save()">
                <fieldset>
                    <div class="box box-{{cmsForm.$valid && 'success' || 'danger'}}">

                        <div class="box-body">
                            <div class="tabs-panel">
                                <ul class="nav nav-tabs">
                                    <li ng-class="{active: page === data.tabs.selectedPage}" ng-repeat="page in data.pages" ng-init="data.tabs.selectedPage = data.tabs.selectedPage || page">
                                        <a href="" ng-click="data.tabs.selectedPage = page">{{page.name}}</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade in active" ng-if="data.tabs.selectedPage.type === 'blog'">
                                        <h3 class="form-title text-bold">Setup Blog</h3>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="blog.title"><span translate="">Blog title:</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="blog.title" placeholder="Enter Blog title" ng-model="settings.blog.title" ng-required="true">
                                                <p class="help-block"><span translate="">The blog page maintains a fully searchable list of all your blogs posts.</span></p>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="settings.blog.url"><span translate="">Blog URL:</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="settings.blog.url" placeholder="Relative URL of blog on site" ng-model="settings.blog.url"
                                                       ng-required="true"
                                                       pattern="[a-z0-9\-\/]+">
                                                <p class="help-block"><span translate="">Full path:</span> {{session.site.host}}/{{mainCtrl.trim(settings.blog.url)}}</p>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="blog.posts"><span translate="">Posts per page:</span></label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" id="blog.posts" placeholder="Enter Number of posts" ng-model="settings.blog.posts" ng-required="true">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="blog.more"><span translate="">Read more button text:</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="blog.more" placeholder="Read more, View Full Article, Learn more, etc" ng-model="settings.blog.more"
                                                       ng-required="false">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade in active" ng-if="data.tabs.selectedPage.type === 'support'">
                                        <h3 class="form-title text-bold">Setup Knowledge Base (KB) articles</h3>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="support.title"><span translate="">Title for KB:</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="support.title" placeholder="Enter KB title" ng-model="settings.support.title" ng-required="true">
                                                <p class="help-block"><span translate="">The Knowledge base page maintains a fully searchable list of all your support articles.</span></p>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="settings.support.url"><span translate="">KB URL:</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="settings.support.url" placeholder="Relative URL of knowledgebase on site" ng-model="settings.support.url"
                                                       ng-required="true" pattern="[a-z0-9\-\/]+">
                                                <p class="help-block"><span translate="">Full path:</span> {{session.site.host}}/{{mainCtrl.trim(settings.support.url)}}</p>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="support.posts"><span translate="">Posts per page:</span></label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" id="support.posts" placeholder="Enter Number of posts" ng-model="settings.support.posts" ng-required="true">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="box-footer with-border">
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button type="submit" class="btn btn-primary"><span translate="">Save changes</span> <i class="fa fa-fw fa-angle-right"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>
        </section>
    </div>
</div>
