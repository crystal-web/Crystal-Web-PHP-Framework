jQuery(function(){
    jQuery(document).on('click', '.btn-buy', function(event){
        var href = jQuery(this).attr('href');
        if (typeof href == undefined){
            return;
        }
        event.preventDefault();
        jQuery.ajax({
            url: href,
            success: function (data) {
                bootbox.confirm(data, function(result){
                    if (result) {
                        jQuery.ajax({
                            url: href + '?token=' + window.token,
                            success: function(rdata) {
                                bootbox.alert(rdata);
                            }
                        });
                    }
                });
            },
            error: function (request, status, error) {
                console.log(request, status, error);
                bootbox.alert('Erreur interne, r&eacute;essayer plus tard');
            }
        });
    });

    jQuery(document).on({
        mouseenter: function () {
            jQuery(".caption", this)
                .stop()
                .animate({top:"0px"},{queue:false,duration:300});
        },
        mouseleave: function () {
            jQuery(".caption", this)
                .stop()
                .animate({top:"115px"},{queue:false,duration:300});
        }
    }, '.box-caption-h150');

    jQuery(document).on({
        mouseenter: function () {
            jQuery(".caption", this)
                .stop()
                .animate({top:"0px"},{queue:false,duration:300});
        },
        mouseleave: function () {
            jQuery(".caption", this)
                .stop()
                .animate({top:"215px"},{queue:false,duration:300});
        }
    }, '.box-caption-h250');


    jQuery('.fancybox').fancybox();
    jQuery('.fancybox-media')
        .attr('rel', 'media-gallery')
        .fancybox({
            openEffect : 'none',
            closeEffect : 'none',
            prevEffect : 'none',
            nextEffect : 'none',

            arrows : false,
            helpers : {
                media : {},
                buttons : {}
            }
        });

});