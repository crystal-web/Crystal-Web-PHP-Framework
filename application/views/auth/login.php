<script type="application/javascript">
    jQuery(document).ready(function(){
        jQuery("#form-auth").show();
        jQuery(document).on('click', '#inputsubmit', function(ev){
            ev.preventDefault();
            $$ = jQuery(this);
            var val = $$.html();
            $$.html('<i class="fa fa-spinner fa-spin"></i>').attr('disabled', 'disabled');
            jQuery.ajax({
                url: '/auth',
                method: 'post',
                data: {
                    user: Aes.Ctr.encrypt(jQuery('#inputuser').val(), window.token, 256),
                    password: Aes.Ctr.encrypt(jQuery('#inputpassword').val(), window.token, 256),
                    remember: jQuery('#inputremember').is(':checked')
                },
                success: function(data){
                    try {
                        data = JSON.parse(data);
                        if (typeof data.error != 'undefined') {
                            $$.html(val).removeAttr('disabled');
                            bootbox.alert(data.error);
                        } else {
                            jQuery("#form-auth").fadeOut();
                            bootbox.alert("Bonjour " + data.success.user, function(){
                                window.location.href = "/";
                            });
                        }
                    } catch(err) {
                        bootbox.alert("Erreur interne");
                        $$.html(val).removeAttr('disabled');
                    }
                }
            });
        });
    });
</script>
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
            <h3 class="panel-title">Please Sign In</h3>
        </div>
        <div class="panel-body">
            <form role="form" method="post" action="<?php echo Router::url("auth"); ?>">
                <fieldset>
                    <div class="form-group">
                        <input class="form-control" id="inputuser" placeholder="Login" type="text" autofocus>
                    </div>
                    <div class="form-group">
                        <input class="form-control" id="inputpassword" placeholder="Password" type="password" value="">
                    </div>
                    <div class="checkbox">
                        <label>
                            <input name="remember" id="inputremember" type="checkbox" value="Remember Me">Remember Me
                        </label>
                    </div>
                    <!-- Change this to a button or input when using this as a form -->
                    <button name="submit" id="inputsubmit" type="submit" class="btn btn-lg btn-success btn-block">Login</button>
                </fieldset>
            </form>
        </div>
    </div>
</div>
