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

<div class="col-md-4 col-md-offset-4" style="display:none;" id="form-auth">
    <div class="login-panel panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Connexion au site</h3>
        </div>
        <div class="panel-body">
            <form role="form" method="post" action="<?php echo Router::url('auth'); ?>">
                <fieldset>
                    <div class="form-group">
                        <input class="form-control" id="inputuser" placeholder="Pseudo Minecraft" type="text" autofocus>
                    </div>
                    <div class="form-group">
                        <input class="form-control" id="inputpassword" placeholder="Mot de passe" type="password" value="">
                    </div>
                    <div class="checkbox">
                        <label>
                            <input name="remember" id="inputremember" type="checkbox" value="Remember Me">Se souvenir de moi
                        </label>
                    </div>
                    <h4>Mot de passe perdu ?</h4>
                    <p><a class="color-green" href="<?php echo Router::url('auth/forgotpassword'); ?>">Cliquez ici</a> pour r&eacute;initialiser le mot de passe.</p>

                    <!-- Change this to a button or input when using this as a form -->
                    <button name="submit" id="inputsubmit" type="submit" class="btn btn-lg btn-success btn-block">Connexion</button>
                </fieldset>
            </form>
        </div>
    </div>
</div>