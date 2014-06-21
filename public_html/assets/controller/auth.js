jQuery(document).ready(function(){
    jQuery("#form-auth").show();
    jQuery("#form-recovery").show();

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
            success: function(data) {
                //console.log(data);
                try {
                    data = JSON.parse(data);
                    if (typeof data.error != 'undefined') {
                        $$.html(val).removeAttr('disabled');
                        bootbox.alert(data.error);
                    } else {
                        jQuery("#form-auth").fadeOut();
                        bootbox.alert("<img src=\"/minecraft/face/" + data.success.name + "\" style=\"float: left;padding: 5px;\"><h3 style=\"line-height: 64px;\"> Bonjour " + data.success.name + "</h3>", function(){
                            window.location.href = data.url;
                        });
                    }

                } catch(err) {
                    console.log(err);
                    bootbox.alert("Erreur interne");
                    $$.html(val).removeAttr('disabled');
                }
            }
        });
    });

    jQuery(document).on('click', '#inputsubmitrecovery', function(ev){
        ev.preventDefault();
        $$ = jQuery(this);
        var val = $$.html();
        $$.html('<i class="fa fa-spinner fa-spin"></i>').attr('disabled', 'disabled');
        jQuery.ajax({
            url: jQuery('#form-recovery form').attr('action'),
            method: 'post',
            data: {
                password: Aes.Ctr.encrypt(jQuery('#inputpassword').val(), window.token, 256),
                passwordctrl: Aes.Ctr.encrypt(jQuery('#inputpassword').val(), window.token, 256)
            },
            success: function(data) {
                //console.log(data);
                try {
                    data = JSON.parse(data);
                    if (typeof data.error != 'undefined') {
                        $$.html(val).removeAttr('disabled');
                        bootbox.alert(data.error);
                    } else {
                        jQuery("#form-auth").fadeOut();
                        window.location.href = "/auth";
                    }
                } catch(err) {
                     console.log(err);
                    bootbox.alert("Erreur interne");
                    $$.html(val).removeAttr('disabled');
                }
            }
        });
    });

});