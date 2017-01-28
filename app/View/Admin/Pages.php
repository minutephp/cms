<div class="content-wrapper ng-cloak" ng-app="pageListApp" ng-controller="pageListController as mainCtrl" ng-init="init()">
    <div class="admin-content">
        <section class="content-header">
            <h1><span translate="">Page builder</span> <small><span translate="">Minute page builder</span></small></h1>

            <ol class="breadcrumb">
                <li><a href="" ng-href="/admin"><i class="fa fa-page"></i> <span translate="">Admin</span></a></li>
                <li class="active"><span translate="">Page list</span></li>
            </ol>
        </section>

        <section class="content">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <span translate="">All pages</span>
                    </h3>

                    <div class="box-tools">
                        <a class="btn btn-sm btn-primary btn-flat" ng-href="/admin/pages/edit">
                            <i class="fa fa-plus-circle"></i> <span translate="">Create new page</span>
                        </a>
                    </div>
                </div>

                <div class="box-body">
                    <div class="list-group">
                        <div class="list-group-item list-group-item-bar list-group-item-bar-{{page.enabled && 'success' || 'danger'}}"
                             ng-repeat="page in pages" ng-click-container="mainCtrl.actions(page)">
                            <div class="pull-left">
                                <h4 class="list-group-item-heading">{{page.slug}} <span class="muted" ng-show="!!page.name">({{page.name | lowercase}})</span></h4>
                                <p class="list-group-item-text hidden-xs">
                                    <span translate="">Created:</span> {{page.created_at | timeAgo}} /
                                    <span translate="">Category:</span> {{page.category || '-'}} /
                                    <span translate="">Title:</span> {{page.contents[0].title || '-'}}
                                </p>
                            </div>
                            <div class="md-actions pull-right">
                                <a class="btn btn-default btn-flat btn-sm" ng-href="/admin/pages/edit/{{page.page_id}}">
                                    <i class="fa fa-pencil-square-o"></i> <span translate="">Edit..</span>
                                </a>
                            </div>

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <div class="row">
                        <div class="col-xs-12 col-md-6 col-md-push-6">
                            <minute-pager class="pull-right" on="pages" no-results="{{'No pages found' | translate}}"></minute-pager>
                        </div>
                        <div class="col-xs-12 col-md-6 col-md-pull-6">
                            <minute-search-bar on="pages" columns="name" label="{{'Search page..' | translate}}"></minute-search-bar>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
