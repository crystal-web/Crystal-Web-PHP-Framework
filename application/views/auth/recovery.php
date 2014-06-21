<script src="/assets/controller/auth.js"></script>
<noscript>
    <div class="col-lg-12">
        <div class="well well-small text-center">
            <h2>Mmmmh je suis confus</h2>
            <div>Le javascript de votre navigateur n'est pas actif.<br>
                Celui-ci est utilis&eacute; pour crypter votre mot de passe avant l'envois.</div>
        </div>
    </div>
</noscript>

<div class="col-md-4 col-md-offset-4" style="display:none;" id="form-recovery">
    <div class="login-panel panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Changement du mot de passe</h3>
        </div>
        <div class="panel-body">
            <form role="form" method="post" action="<?php echo Router::selfURL(); ?>">
                <fieldset>
                    <div class="form-group">
                        <input class="form-control" id="inputpassword" placeholder="Mot de passe" type="password" value="">
                    </div>
                    <div class="form-group">
                        <input class="form-control" id="inputpasswordctrl" placeholder="Mot de passe &agrave; nouveau" type="password" value="">
                    </div>
                    <!-- Change this to a button or input when using this as a form -->
                    <button name="submit" id="inputsubmitrecovery" type="submit" class="btn btn-lg btn-success btn-block">Enregistrer</button>
                </fieldset>
            </form>
        </div>
    </div>
</div>