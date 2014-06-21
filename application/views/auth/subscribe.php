<?php 
if (isset($error) && count($error)) {
echo '<div class="well well-small grd-orange">
<div>Attention!</div>';
	foreach($error AS $k => $v) {
		echo $v.'<br>';
	}
echo '</div>';
}
?>

<div class="col-md-4 col-md-offset-4" style="" id="form-auth">
    <div class="login-panel panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Cr&eacute;ation de votre compte</h3>
        </div>
        <div class="panel-body">
            <p>
                Votre compte vous permettra de vous connecter sur le site et le serveur.
                Celui-ci doit &ecirc;tre identique &agrave; celui de votre compte Minecraft.net si vous en poss&eacute;dez un.
                Attention aux majuscules dans votre nom de compte.
            </p>
            <form role="form" method="post">
                <fieldset>
                    <div class="form-group">
                        <input class="form-control" type="text" required="required" name="loginmember" placeholder="Nom de compte">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="text" required="required" name="mailmember" placeholder="Adresse email">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="text" required="required" name="othermail" placeholder="Confirmer l'adresse email">
                    </div>

                    <div class="form-group">
                        <input class="form-control" type="password" required="required" name="passmember" placeholder="Votre mot de passe">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" required="required" name="otherpass" placeholder="Confirmer le mot de passe">
                    </div>

                    <div class="form-group input-group">
                    <span class="input-group-addon" style="padding: 1px;"><?php echo Captcha::generateImgTags(".."); ?></i>
                    </span>
                        <?php echo Captcha::generateHiddenTags().Captcha::generateInputTags(); ?>
                    </div>

                    <!-- Change this to a button or input when using this as a form -->
                    <button name="submit" id="inputsubmit" type="submit" class="btn btn-lg btn-success btn-block">Inscription</button>
                </fieldset>
            </form>
        </div>
    </div>
</div>

