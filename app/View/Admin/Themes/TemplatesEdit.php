<div class="content-wrapper ng-cloak" ng-app="templateEditApp" ng-controller="templateEditController as mainCtrl" ng-init="init()">
    <div class="admin-content">
        <section class="content-header">
            <h1>
                <span translate="" ng-show="!template.theme_template_id">Create new</span>
                <span translate="" ng-show="!!template.theme_template_id">Edit</span>
                <span translate="">template</span>
            </h1>

            <ol class="breadcrumb">
                <li><a href="" ng-href="/admin"><i class="fa fa-dashboard"></i> <span translate="">Admin</span></a></li>
                <li><a href="" ng-href="/admin/themes"><i class="fa fa-code"></i> <span translate="">Themes</span></a></li>
                <li><a href="" ng-href="/admin/themes/templates/list/{{template.theme_id}}"><i class="fa fa-file"></i> <span translate="">Templates</span></a></li>
                <li class="active"><i class="fa fa-edit"></i> <span translate="">Edit template</span></li>
            </ol>
        </section>

        <section class="content">
            <form class="form-horizontal" name="templateForm" ng-submit="mainCtrl.save()">
                <div class="box box-{{templateForm.$valid && 'success' || 'danger'}}">
                    <div class="box-header with-border">
                        <span translate="" ng-show="!template.theme_template_id">New template</span>
                        <span ng-show="!!template.theme_template_id"><span translate="">Edit</span> {{template.name}}</span>
                    </div>

                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="name"><span translate="">Name:</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" placeholder="Enter Name" ng-model="template.name" ng-required="true">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="description"><span translate="">Description:</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="description" placeholder="Enter Description" ng-model="template.description" ng-required="false">
                            </div>
                        </div>

                        <div ng-if="template.theme_template_id">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="name"><span translate="">Components:</span></label>

                                <div class="col-sm-10">
                                    <div minute-list-sorter="template.relations" sort-index="priority">
                                        <div class="list-group-item list-group-item-bar list-group-item-bar-sortable" ng-repeat="relation in template.relations | orderBy:'priority'">
                                            <div class="pull-left">
                                                <h4 class="list-group-item-heading">
                                                    {{relation.relation_name | ucfirst}}
                                                </h4>
                                            </div>
                                            <div class="pull-right">
                                                <a class="btn btn-default btn-flat btn-xs" ng-click="mainCtrl.rename(relation)"><i class="fa fa-pencil-square-o"></i> <span
                                                        translate="">Rename</span></a>
                                                <a class="btn btn-default btn-flat btn-xs" ng-click="relation.removeConfirm()"><i class="fa fa-trash"></i> <span translate="">Remove</span></a>
                                            </div>

                                            <div class="clearfix"></div>
                                        </div>
                                    </div>

                                    <hr ng-show="!!template.relations.length">

                                    <button type="button" class="btn btn-flat btn-default btn-sm" ng-click="mainCtrl.pickComponents()">
                                        <i class="fa fa-plus-circle"></i> <span translate="">Add component</span>
                                    </button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="layout">
                                    <span translate="">Html code:</span>
                                </label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" rows="10" placeholder="Enter Html Layout" ng-model="template.html_code" ng-required="true"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="screenshot"><span translate="">Screenshot:</span></label>
                                <div class="col-sm-10">
                                    <minute-uploader ng-model="template.screenshot" type="image" preview="true" remove="true" label="Upload.."></minute-uploader>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="box-footer with-border">
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-flat btn-primary">
                                    <span translate="" ng-show="!template.theme_template_id">Create</span>
                                    <span translate="" ng-show="!!template.theme_template_id">Update</span>
                                    <span translate="">template</span>
                                    <i class="fa fa-fw fa-angle-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>

    <script type="text/ng-template" id="/components-popup.html">
        <div class="box">
            <div class="box-header with-border">
                <b class="pull-left"><span translate="">All components</span></b>
                <a class="pull-right close-button" href=""><i class="fa fa-times"></i></a>
            </div>

            <div class="box-body">
                <div class="list-group-item list-group-item-bar" ng-repeat="component in components">
                    <div class="pull-left">
                        <h4 class="list-group-item-heading">{{component.name | ucfirst}}</h4>
                        <p class="list-group-item-text hidden-xs">
                            {{component.description}}
                        </p>
                    </div>
                    <div class="pull-right">
                        <a class="btn btn-default btn-flat close-button" ng-click="ctrl.addComponent(component)">
                            <span translate="">Add</span>
                        </a>
                    </div>

                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="box-footer with-border">
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-md-push-6">
                        <minute-pager class="pull-right" on="components" no-results="{{'No components found' | translate}}"></minute-pager>
                    </div>
                    <div class="col-xs-12 col-md-6 col-md-pull-6">
                        <minute-search-bar on="components" columns="name, description" label="{{'Search components..' | translate}}"></minute-search-bar>
                    </div>
                </div>
            </div>
        </div>
    </script>

</div>