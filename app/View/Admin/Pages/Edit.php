<div class="content-wrapper ng-cloak" ng-app="contentListApp" ng-controller="contentListController as mainCtrl" ng-init="init()">
    <div class="admin-content" minute-hot-keys="{'ctrl+s':mainCtrl.savePage}">
        <section class="content-header">
            <h1><span translate="">Contents inside</span> {{page.name}} <small><span translate="">(for A/B split testing)</span></small></h1>

            <ol class="breadcrumb">
                <li><a href="" ng-href="/admin"><i class="fa fa-dashboard"></i> <span translate="">Admin</span></a></li>
                <li><a href="" ng-href="/admin/pages"><i class="fa fa-sitemap"></i> <span translate="">Page builder</span></a></li>
                <li class="active"><span translate="">Content list</span></li>
            </ol>
        </section>

        <section class="content">
            <ng-switch on="!page.page_id || !!data.showProps">
                <form class="form-horizontal" name="pageForm" ng-submit="mainCtrl.savePage()">
                    <div class="box box-{{pageForm.$valid && 'success' || 'danger'}}" ng-switch-when="true">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <span translate="" class="hidden-xs">Page properties</span>
                            </h3>

                            <div class="box-tools" ng-show="!!page.page_id">
                                <button type="button" class="btn btn-sm btn-default" ng-click="data.showProps = false"><i class="fa fa-times"></i></button>
                            </div>
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="slug"><span translate="">Page slug:</span></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control auto-focus" id="slug" placeholder="/access-url" ng-model="page.slug" ng-pattern="/^[a-zA-Z0-9\-\/]+$/" ng-required="true">
                                    <p class="help-block" translate="">(Slug is the relative URL for this page)</p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 control-label" for="name"><span translate="">Page name:</span></label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="name" placeholder="Enter Page name" ng-model="page.name" ng-required="false">
                                    <p class="help-block" translate="">(optional)</p>
                                </div>

                                <label class="col-sm-2 control-label" for="category"><span translate="">Category:</span></label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="category" placeholder="Enter Category" ng-model="page.category" ng-required="false">
                                    <p class="help-block" translate="">(optional)</p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span translate="">Page type:</span></label>
                                <div class="col-sm-10">
                                    <label class="radio-inline" ng-repeat="type in data.types">
                                        <input type="radio" ng-model="page.type" ng-value="type.value"> {{type.label}}
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="name"><span translate="">Settings:</span></label>
                                <div class="col-sm-10">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" ng-model="page.enabled"> <span translate="">Enabled</span>
                                    </label>

                                    <label class="checkbox-inline">
                                        <input type="checkbox" ng-model="page.feed"> <span translate="">Include in RSS feed</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer with-border">
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-flat btn-primary">
                                        <span translate ng-show="!page.page_id">Create page</span>
                                        <span translate ng-show="!!page.page_id">Update page</span>
                                        <i class="fa fa-fw fa-angle-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="box box-default" ng-switch-when="false">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <span translate="" class="hidden-xs">All contents</span>
                        </h3>

                        <div class="box-tools">
                            <button type="button" class="btn btn-sm btn-primary btn-flat" ng-click="mainCtrl.addContent()">
                                <i class="fa fa-plus-circle"></i> <span translate="">Create new content</span>
                            </button>
                            <button type="button" class="btn btn-sm btn-default" ng-click="data.showProps = true" tooltip="{{'Edit page settings' | translate}}"><i class="fa fa-cog"></i></button>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="list-group">
                            <div class="list-group-item list-group-item-bar list-group-item-bar-{{content.enabled && 'success' || 'danger'}}"
                                 ng-repeat="content in page.contents" ng-click-container="mainCtrl.actions(content)">
                                <div class="pull-left">
                                    <h4 class="list-group-item-heading" ng-show="!!content.name">{{content.name}}</h4>
                                    <h4 class="list-group-item-heading" ng-show="!content.name"><span translate="">Variation #</span>{{$index + 1}}</h4>

                                    <p class="list-group-item-text hidden-xs">
                                        <span translate="">Created:</span> {{content.created_at | timeAgo}}.
                                        <span translate="">Title:</span> {{content.data_json.model.local.about.title || 'None'}}.
                                    </p>
                                    <p class="list-group-item-text hidden-xs">
                                        <span translate="">Raw Hits: </span> {{content.stats.raw_hits}}.
                                        <span translate="">Unique Hits: </span> {{content.stats.unique_hits}}.
                                        <span translate="">Total Signups: </span> {{content.stats.signups}}.
                                        <span translate="">Paid Signups: </span> {{content.stats.conversions}}.
                                    </p>
                                </div>
                                <div class="md-actions pull-right">
                                    <button type="button" class="btn btn-default btn-flat btn-sm" ng-click="mainCtrl.editPage(content)">
                                        <i class="fa fa-pencil-square-o"></i> <span translate="">Edit..</span>
                                    </button>
                                </div>

                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <div class="row">
                            <div class="col-xs-12 col-md-6 col-md-push-6">
                                <minute-pager class="pull-right" on="page.contents" no-results="{{'No contents found' | translate}}"></minute-pager>
                            </div>
                            <div class="col-xs-12 col-md-6 col-md-pull-6">
                                <minute-search-bar on="page.contents" columns="name, title" label="{{'Search content..' | translate}}"></minute-search-bar>
                            </div>
                        </div>
                    </div>
                </div>
            </ng-switch>
        </section>
    </div>

    <script type="text/ng-template" id="/pick-template.html">
        <div class="box">
            <div class="box-body">
                <div class="tabs-panel">
                    <ul class="nav nav-tabs">
                        <li ng-class="{active: theme === data.selectedTheme}" ng-repeat="theme in themes" ng-init="data.selectedTheme = data.selectedTheme || theme">
                            <a href="" ng-click="data.selectedTheme = theme">{{theme.name | ucfirst}}</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade in active" ng-repeat="theme in themes" ng-if="theme === data.selectedTheme">
                            <div class="list-group-item list-group-item-bar" ng-repeat="template in theme.templates">
                                <div class="row">
                                    <div class="hidden-xs col-xs-3">
                                        <a class="thumbnail no-margin" href="" ng-href="{{template.screenshot}}" target="_blank"><img ng-src="{{template.screenshot}}"></a>
                                    </div>
                                    <div class="col-xs-6">
                                        <h4 class="list-group-item-heading">{{template.name | ucfirst}}</span></h4>
                                        <p class="list-group-item-text hidden-xs">
                                            {{template.description}}
                                        </p>
                                    </div>
                                    <div class="col-xs-3">
                                        <button type="button" class="btn btn-default btn-flat" ng-click="mainCtrl.setTemplate(content, template)">
                                            <i class="fa fa-check-circle" ng-show="content.theme_template_id === template.theme_template_id"></i>
                                            <span translate="">Pick</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <minute-pager on="theme.templates" auto-hide="true"></minute-pager>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer with-border pull-center">
                <span translate="">Please select a template for this page.</span>
            </div>
        </div>
    </script>
</div>
