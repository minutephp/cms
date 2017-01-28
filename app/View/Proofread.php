<div class="container ng-cloak" ng-app="ProofreadApp" ng-controller="ProofreadController as mainCtrl" ng-init="init()" ng-cloak="">
    <div class="content-wrapper">
        <section class="content debug">
            <!-- var dump start -->
            <div class="panel panel-default">
                <div class="panel-heading"><b>Proofread text</b></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form" name="proofForm" ng-submit="mainCtrl.save()">
                                <fieldset>
                                    <legend>Proof read text in all the text boxes below</legend>

                                    <p class="text-danger">Please leave all asterisk signs, HTML tags (like &lt;i class=".."&gt;, &amp;nbsp;, etc) and line spacing intact.</p>

                                    <div class="form-group" ng-repeat="field in fields">
                                        <label>{{field.name}}:</label>

                                        <textarea class="form-control" ng-model="field.value" rows="{{field.lines}}" cols="80" ng-required="true"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <hr />
                                        <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-check-circle"></i> Save changes</button>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
