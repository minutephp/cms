<div class="content-wrapper ng-cloak" ng-app="contentEditApp" ng-controller="contentEditController as mainCtrl" ng-init="init()">
    <div class="admin-full-width">
        <section class="content">
            <div class="wrap">
                <div id="MySplitter" class="{{!!data.inline && 'resizable pull-left' || 'full-width'}}">
                    <div>
                        <h2 class="heading-no-margin pull-left">{{content.page.name}} <span class="muted">({{content.page.slug}})</span></h2>

                        <ol class="breadcrumb toolbar pull-right" ng-show="!data.inline">
                            <li><a class="btn btn-default btn-flat btn-sm" href="" ng-click="mainCtrl.popIn()" tooltip="{{'Inline Live preview' | translate}}">
                                    <i class="fa fa-minus"></i></a></li>
                        </ol>

                        <ol class="breadcrumb toolbar pull-right hidden-xs hidden-sm" ng-show="!!data.inline">
                            <li><a href="" ng-href="/admin"><i class="fa fa-dashboard"></i> <span translate="">Admin</span></a></li>
                            <li><a href="" ng-href="/admin/pages"><i class="fa fa-content"></i> <span translate="">Pages</span></a></li>
                            <li><a href="" ng-href="/admin/pages/edit/{{content.page_id}}"><i class="fa fa-content"></i> <span translate="">{{content.page.name || content.page.slug}}</span></a></li>
                            <li class="active"><i class="fa fa-edit"></i> <span translate="">Page content</span></li>
                        </ol>

                        <div class="clearfix"></div>
                    </div>

                    <form name="contentForm" ng-submit="mainCtrl.save()" novalidate>
                        <div class="box box-{{contentForm.$valid && 'success' || 'danger'}}">
                            <div class="box-body">
                                <div class="tabs-panel" ng-init="tabs = {}">
                                    <ul class="nav nav-tabs">
                                        <li ng-class="{active: relation.component === tabs.selectedComponent}" ng-repeat="relation in content.relations"
                                            ng-init="tabs.selectedComponent = tabs.selectedComponent || relation.component" class="text-{{relation.valid && 'success' || 'danger'}}">
                                            <a href="" ng-click="tabs.selectedComponent = relation.component">
                                                {{relation.relation_name | ucfirst}}
                                                <button class="close closeTab" type="button" ng-show="!!data.debug" ng-click="data.component = relation.component"><i class="fa fa-cog"></i></button>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" ng-show="!!data.component">
                                        <div class="tab-pane fade in active">
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="component_html">
                                                    <span translate="">Layout</span>
                                                </label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control" rows="15" placeholder="Enter Layout" ng-model="data.component.component_html" ng-required="false"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-content" ng-show="!data.component">
                                        <div class="tab-pane fade in active" ng-repeat="relation in content.relations" ng-show="relation.component === tabs.selectedComponent"
                                             ng-init="key = relation.component.name; data.scopes[key] = data.scopes[key] || relation.component.form_data_json.scopes[0].name || 'local'">
                                            <div class="form-horizontal">
                                                <div class="form-group" ng-if="!!relation.component.form_data_json.scopes.length">
                                                    <label class="col-sm-2 control-label"><span translate="">Scope:</span></label>
                                                    <div class="col-sm-10">
                                                        <label class="radio-inline" ng-repeat="scope in relation.component.form_data_json.scopes">
                                                            <input type="radio" ng-model="data.scopes[key]" ng-value="scope.name"> {{scope.label}}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <angular-json-form ng-repeat="theScope in scopes" data="data.model[data.scopes[key]][key]" schema="relation.component.form_data_json.schema"
                                                               ng-if="theScope === data.scopes[key]" valid="relation.valid"
                                                               ng-init="data.model[data.scopes[key]][key] = data.model[data.scopes[key]][key] || {}"></angular-json-form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="box-footer with-border">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" class="btn btn-flat btn-primary" ng-show="!data.component">
                                            <span translate="">Update content</span> <i class="fa fa-fw fa-angle-right"></i>
                                        </button>
                                        <div ng-show="!!data.component">
                                            <button type="button" class="btn btn-flat btn-primary" ng-click="data.component.save('Saved')">
                                                <i class="fa fa-check"></i> <span translate="">Update component</span>
                                            </button>
                                            <button type="button" class="btn btn-flat btn-default" ng-click="data.component = null">
                                                <i class="fa fa-angle-left"></i> <span translate="">Edit form</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="resizable pull-right" ng-show="!!data.inline">
                    <div>
                        <h2 class="heading-no-margin pull-left"><span translate="">Live preview</span></h2>

                        <ol class="breadcrumb toolbar pull-right">
                            <li ng-if="!content.enabled"><a class="btn btn-default btn-flat btn-sm" href="" ng-click="mainCtrl.enable(true)" tooltip="{{'Enable this variation' | translate}}">
                                    <i class="fa fa-bolt"></i></a></li>
                            <li ng-if="!!content.enabled"><a class="btn btn-default btn-flat btn-sm" href="" ng-click="mainCtrl.enable(false)" tooltip="{{'Disable this variation' | translate}}">
                                    <i class="fa fa-power-off"></i></a></li>
                            <li><a class="btn btn-default btn-flat btn-sm" href="" ng-click="mainCtrl.save()" tooltip="{{'Quick save' | translate}}">
                                    <i class="fa fa-floppy-o"></i></a></li>
                            <li><a class="btn btn-default btn-flat btn-sm" href="" ng-click="mainCtrl.preview()" tooltip="Preview">
                                    <i class="fa fa-eye"></i></a></li>
                            <li><a class="btn btn-default btn-flat btn-sm" href="" ng-click="mainCtrl.popOut()" tooltip="{{'Popup live preview' | translate}}">
                                    <i class="fa fa-desktop"></i></a></li>
                            <li ng-if="!data.debug"><a class="btn btn-default btn-flat btn-sm" href="" ng-click="data.debug = true" tooltip="{{'Enable developer mode' | translate}}">
                                    <i class="fa fa-flask"></i></a></li>
                            <li ng-if="!!data.debug"><a class="btn btn-default btn-flat btn-sm" href="" ng-click="mainCtrl.showSource()" tooltip="{{'Show source code' | translate}}">
                                    <i class="fa fa-wrench"></i></a></li>
                            <li ng-if="!!data.debug"><a class="btn btn-default btn-flat btn-sm" href="" ng-click="mainCtrl.customHtml()" tooltip="{{'Add custom HTML' | translate}}">
                                    <i class="fa fa-code"></i></a></li>
                        </ol>

                        <div class="clearfix"></div>
                    </div>

                    <iframe src="about:blank" frameborder="0" width="100%" height="900" id="previewWindow" name="theIframe"></iframe>
                </div>
                <div class="clearfix"></div>
            </div>
        </section>
    </div>

    <script type="text/ng-template" id="/source-code-popup.html">
        <div class="box">
            <div class="box-header with-border">
                <b><span translate="">View page source</span></b>
            </div>

            <div class="box-body">
                <div class="box-body">
                    <div ng-jsoneditor options="{modes: ['tree', 'code'], name: type}" ng-model="data.model" style="height:350px;"></div>
                </div>
            </div>

            <div class="box-footer with-border">
                <button type="button" class="btn btn-flat btn-primary pull-right close-button" ng-click="ctrl.updateForm(data)">
                    <span translate>Save</span> <i class="fa fa-fw fa-angle-right"></i>
                </button>
            </div>
        </div>
    </script>


    <script type="text/ng-template" id="/custom-html-popup.html">
        <div class="box">
            <div class="box-header with-border">
                <b class="pull-left"><span translate="">Add Custom HTML</span></b>
                <a class="pull-right close-button" href=""><i class="fa fa-times"></i></a>
                <div class="clearfix"></div>
            </div>

            <div class="box-body">
                <textarea rows="5" class="form-control" ng-model="content.custom_html" placeholder="Paste custom HTML here"></textarea>
                <p class="help-block"><span translate="">Custom HTML is not added to page during page preview / editing</span></p>
            </div>
        </div>
    </script>


</div>
