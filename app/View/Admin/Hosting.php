<div class="content-wrapper ng-cloak" ng-app="hostingConfigApp" ng-controller="hostingConfigController as mainCtrl" ng-init="init()">
    <div class="admin-content">
        <section class="content-header">
            <h1>
                <span translate="">S3 Hosting</span>
            </h1>

            <ol class="breadcrumb">
                <li><a href="" ng-href="/admin"><i class="fa fa-dashboard"></i> <span translate="">Admin</span></a></li>
                <li class="active"><i class="fa fa-cog"></i> <span translate="">S3 Hosting</span></li>
            </ol>
        </section>

        <section class="content">
            <form class="form-horizontal" name="hostingForm" ng-submit="mainCtrl.save()">
                <div class="box box-{{hostingForm.$valid && 'success' || 'danger'}}">
                    <div class="box-header with-border">
                        <span>Upload files</span>
                    </div>

                    <div class="box-body">
                        <minute-uploader ng-model="data.files" multiple="true" label="Upload files.."></minute-uploader>
                    </div>
                </div>

                <div class="box box-default" ng-show="!!data.local.length">
                    <div class="box-header with-border">
                        <span><b>Recent uploads</b></span>
                    </div>
                    <div class="box-body">
                        <div ng-repeat="file in data.local" class="row">
                            <div class="col-xs-2"><a href="{{file}}" target="_blank" class="thumbnail"><img src="{{file}}" alt=""></a></div>
                            <div class="col-xs-9"><input class="form-control" type="text" value="{{file}}" readonly title="{{file}}" onfocus="this.select()" /></div>
                            <div class="col-xs-1"><a href="" ng-click="data.local.splice($index, 1)"><i class="fa fa-times"></i></a></div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>
