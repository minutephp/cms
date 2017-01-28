<div class="content-wrapper ng-cloak" ng-app="componentListApp" ng-controller="componentListController as mainCtrl" ng-init="init()">
    <div class="admin-content">
        <section class="content-header">
            <h1><span translate="">List of components</span></h1>

            <ol class="breadcrumb">
                <li><a href="" ng-href="/admin"><i class="fa fa-dashboard"></i> <span translate="">Admin</span></a></li>
                <li><a href="" ng-href="/admin/themes"><i class="fa fa-code"></i> <span translate="">Themes</span></a></li>
                <li class="active"><i class="fa fa-cog"></i> <span translate="">Components list</span></li>
            </ol>
        </section>

        <section class="content">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <span translate="">All components</span>
                    </h3>

                    <div class="box-tools">
                        <a class="btn btn-sm btn-primary btn-flat" ng-href="/admin/themes/components/edit/{{theme.theme_id}}">
                            <i class="fa fa-plus-circle"></i> <span translate="">Create new component</span>
                        </a>
                    </div>
                </div>

                <div class="box-body">
                    <div class="list-group">
                        <div class="list-group-item list-group-item-bar list-group-item-bar-default"
                             ng-repeat="component in theme.components" ng-click-container="mainCtrl.actions(component)">
                            <div class="pull-left">
                                <h4 class="list-group-item-heading">{{component.name | ucfirst}}</h4>
                                <p class="list-group-item-text hidden-xs">{{component.description}}</p>
                            </div>
                            <div class="md-actions pull-right">
                                <a class="btn btn-default btn-flat btn-sm" ng-href="/admin/themes/components/edit/{{theme.theme_id}}/{{component.theme_component_id}}">
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
                            <minute-pager class="pull-right" on="theme.components" no-results="{{'No components found' | translate}}"></minute-pager>
                        </div>
                        <div class="col-xs-12 col-md-6 col-md-pull-6">
                            <minute-search-bar on="theme.components" columns="name, description" label="{{'Search components..' | translate}}"></minute-search-bar>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
