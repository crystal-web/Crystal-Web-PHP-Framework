jQuery(document).on('click', '[data-report].feedback-spam', function(ev){
    ev.preventDefault();
    var id = jQuery(this).attr('data-report');
    jQuery.ajax({
        url: '/feedback',
        method: 'post',
        data: {spam: id},
        success: function(data){
            data = parseInt(data);
            jQuery('[data-report-spam=' + id + ']').fadeOut(400, function(){
                if (data > 0) { jQuery('[data-report-spam=' + id + ']').html(data).fadeIn(); }
            });
        }
    });
});//*/
jQuery(document).on('click', '[data-report-reply].feedback-spam', function(ev){
    ev.preventDefault();
    var id = jQuery(this).attr('data-report-reply');
    jQuery.ajax({
        url: '/feedback',
        method: 'post',
        data: {spamReply: id},
        success: function(data){
            data = parseInt(data);
            jQuery('[data-report-reply-spam=' + id + ']').fadeOut(400, function(){
                if (data > 0) { jQuery('[data-report-reply-spam=' + id + ']').html(data).fadeIn(); }
            });
        }
    });
});//*/

jQuery(document).on('click', 'a.feedback-reply', function(ev){
    ev.preventDefault();
    var id = jQuery(this).attr('data-id');
    jQuery('[data-id-form]').slideUp();
    jQuery('[data-id-form=' + id + ']').slideDown(400);
});
jQuery(document).on('submit', 'form[data-id-form]', function(ev){
    ev.preventDefault();
    var id = jQuery(this).attr('data-id-form');

    jQuery.ajax({
        url: '/feedback',
        method: 'post',
        data: {
            reply: id,
            mail: jQuery('#inputmail-' + id).val(),
            description: jQuery('#inputreply-' + id).val()
        },
        success: function(data){
            try {
                var json = JSON.parse(data);
                if (typeof json.error != 'undefined')  {
                    var msg = "";
                    if (typeof json.error.mail != 'undefined'){
                        msg = json.error.mail + '<br>';
                    }
                    if (typeof json.error.description != 'undefined'){
                        msg += json.error.description;
                    }
                    if (typeof json.error.message != 'undefined'){
                        msg += json.error.message;
                    }
                    bootbox.alert(msg);
                } else {
                    window.location.reload();
                }
            } catch(e) {
                bootbox.alert('Oops.. Une erreur interne c\'est produite.');
            }
        }
    });
});//*/

jQuery(document).ready(function(){
    var $window = jQuery(window);
    var page = parseInt(jQuery('[data-page]:last-child').attr('data-page'));
        page = (isNaN(page)) ? 1 : page;

    $window.scroll(function () {
        if ($window.height() + $window.scrollTop() == jQuery(document).height()) {
            var cat = jQuery('.nav.nav-tabs .active').attr('id');
            if (jQuery('#load').is(":visible") || jQuery('#nomore').is(":visible")) {
                return;
            }
            page++;
            jQuery('#load').fadeIn(400, function(){
                jQuery.ajax({
                    url: '/feedback/' + cat + '?page=' + page,
                    success: function(data){
                        if (data.length <10){
                            jQuery('#load').fadeOut(400, function(){
                                jQuery('#nomore').fadeIn();
                            })
                            return;
                        }
                        jQuery('#load').fadeOut(400);

                        jQuery('#listen').append(data);
                    }
                });
            });
        }
    });
});
