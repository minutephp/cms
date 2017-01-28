<div class="content-wrapper ng-cloak" ng-app="RenderApp" ng-controller="RenderController as mainCtrl" ng-init="init()" ng-cloak="">

<section class="content debug">
<!-- var dump start -->
<div class="panel panel-default">
    <div class="panel-heading"><b>contents</b></div>
    <div class="panel-body">
        <pre class="pre-scrollable">{{contents.dump() | json}}</pre>
    </div>
</div>

<!-- var dump end -->
</section>

</div>
