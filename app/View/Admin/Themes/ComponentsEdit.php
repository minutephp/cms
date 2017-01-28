<div class="content-wrapper ng-cloak" ng-app="componentEditApp" ng-controller="componentEditController as mainCtrl" ng-init="init()">
    <div class="admin-content">
        <section class="content-header">
            <h1>
                <span translate="" ng-show="!component.theme_component_id">Create new</span>
                <span translate="" ng-show="!!component.theme_component_id">Edit</span>
                <span translate="">component</span>
            </h1>

            <ol class="breadcrumb">
                <li><a href="" ng-href="/admin"><i class="fa fa-dashboard"></i> <span translate="">Admin</span></a></li>
                <li><a href="" ng-href="/admin/themes"><i class="fa fa-code"></i> <span translate="">Themes</span></a></li>
                <li><a href="" ng-href="/admin/themes/components/list/{{component.theme_id}}"><i class="fa fa-code"></i> <span translate="">Components</span></a></li>
                <li class="active"><i class="fa fa-edit"></i> <span translate="">Edit component</span></li>
            </ol>
        </section>

        <section class="content">
            <form class="form-horizontal" name="componentForm" ng-submit="mainCtrl.save()">
                <div class="box box-{{componentForm.$valid && 'success' || 'danger'}}">
                    <div class="box-header with-border">
                        <span translate="" ng-show="!component.theme_component_id">New component</span>
                        <span ng-show="!!component.theme_component_id"><span translate="">Edit</span> {{component.name}}</span>
                    </div>

                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="name"><span translate="">Name:</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" placeholder="Enter Component name" ng-model="component.name" ng-required="true">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="description"><span translate="">Description:</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="description" placeholder="Enter Description" ng-model="component.description" ng-required="false">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="form_data"><span translate="">Form data:</span></label>
                            <div class="col-sm-10">
                                <div ng-jsoneditor ng-model="component.form_data_json" options="{modes: ['tree', 'code'], name: 'tabs'}" style="width: 100%; height: 420px;"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="html_code">
                                <span translate="">Html code:</span>
                            </label>
                            <div class="col-sm-10">
                                <textarea class="form-control" rows="12" placeholder="Enter Html code:" ng-model="component.component_html" ng-required="true"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="form_data"><span translate="">Sample data:</span></label>
                            <div class="col-sm-10">
                                <div ng-jsoneditor ng-model="component.sample_data_json" options="{modes: ['tree', 'code'], name: 'tabs'}" style="width: 100%; height: 420px;"></div>
                            </div>
                        </div>

                    </div>

                    <div class="box-footer with-border">
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-flat btn-primary">
                                    <span translate="" ng-show="!component.theme_component_id">Create</span>
                                    <span translate="" ng-show="!!component.theme_component_id">Update</span>
                                    <span translate="">component</span>
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
