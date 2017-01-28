<div class="content-wrapper ng-cloak" ng-app="blogApp" ng-controller="blogController as mainCtrl" ng-init="init()">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h1>{{settings.title || 'Recent blog posts'}}</h1>
            </div>

            <div ng-repeat="page in pages" ng-init="data = page.content.data_json.model.local">
                <div class="col-xs-1">
                    <div class="alert alert-info" style="margin: 20px 0;">
                        <div class="text-center" ng-bind-html="mainCtrl.toDate(page.updated_at)"></div>
                    </div>
                </div>

                <div class="col-xs-11">
                    <a href="" ng-href="{{page.slug}}"><h3>{{(data.post.heading || data.about.title || page.name) | ucFirst}}</h3></a>
                    <p>{{(data.post.subHeading || data.about.description) | ucFirst}}</p>
                    <p><a href="" ng-href="{{page.slug}}">{{settings.more || 'Read more'}}</a> <i class="fa fa-angle-right"></i></p>
                </div>

                <div class="col-xs-12" ng-show="!$last">
                    <br>&nbsp;
                </div>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-xs-12 col-md-6 col-md-push-6">
                <minute-pager class="pull-right" on="pages" no-results="{{'No pages found' | translate}}"></minute-pager>
            </div>
            <div class="col-xs-12 col-md-6 col-md-pull-6">
                <minute-search-bar on="pages" columns="name, content.compiled_html" label="{{'Search pages..' | translate}}"></minute-search-bar>
            </div>
        </div>

    </div>
</div>