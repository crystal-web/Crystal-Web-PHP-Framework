<div class="col-md-4 col-md-offset-4">
    <div class="login-panel panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Mot de passe perdu ?</h3>
        </div>
        <div class="panel-body">
            <p>
                ...Pas de problème, veuillez indiquer votre adresse e-mail.
                Un e-mail vous sera envoyer avec les instruction de modification du mot de passe.
            </p>
            <p class="color-red">
                <strong>ATTENTION:</strong> Si vous ne recevez pas l'e-mail, vérifiez vos courriers indésirables (SPAM)
            </p>
            <form role="form" method="post">
                <fieldset>
                    <div class="form-group">
                        <input class="form-control" type="text" required="required" name="mailmember" placeholder="Adresse email">
                    </div>

                    <div class="form-group input-group">
                        <span class="input-group-addon" style="padding: 1px;">
                            <?php echo Captcha::generateImgTags(".."); ?>
                        </span>
                        <?php echo Captcha::generateHiddenTags().Captcha::generateInputTags(); ?>
                    </div>

                    <!-- Change this to a button or input when using this as a form -->
                    <input type="submit" name="submit" value="Valider" class="btn btn-lg btn-success btn-block">
                </fieldset>
            </form>
        </div>
    </div>
</div>