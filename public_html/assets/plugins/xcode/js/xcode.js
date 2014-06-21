function selection() {
    var input = document.getElementsByClassName('xCodeContent')[0];
    input.focus();
    if(typeof document.selection != 'undefined') {
        var range = document.selection.createRange();
        var text_sel = range.text;
    } else if(typeof input.selectionStart != 'undefined') {
        var deb = input.selectionStart;
        var fin = input.selectionEnd;
        var text_sel = input.value.substring(deb,fin);
    } else {
        var text_sel = '';
    }
    return text_sel;
}
function validYoutube(url) {
    var p = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
    return (url.match(p)) ? RegExp.$1 : false;
}
function img_ins() {
    text_sel = selection();
    var txt_img = prompt('Veuillez indiquer l\'adresse internet de l\'image',text_sel);
    if(txt_img != null) {
        insertTag('[img]'+txt_img,'[/img]','non');
    }
}
function insertTag(startTag, endTag, tagType) {
    var field = document.getElementsByClassName('xCodeContent')[0];
    var scroll = field.scrollTop;
    field.focus();

    if (window.ActiveXObject) {
        var textRange = document.selection.createRange();
        var currentSelection = textRange.text;
    } else {
        var startSelection   = field.value.substring(0, field.selectionStart);
        var currentSelection = field.value.substring(field.selectionStart, field.selectionEnd);
        var endSelection     = field.value.substring(field.selectionEnd);
    }

    if (tagType) {
        switch (tagType) {
            case 'non':
                currentSelection = '';
                break;
            case "lien":
                endTag = "[/url]";
                if (currentSelection) {
                    if (currentSelection.indexOf("http://") == 0 || currentSelection.indexOf("https://") == 0 || currentSelection.indexOf("ftp://") == 0 || currentSelection.indexOf("www.") == 0) {
                        var label = prompt("Quel est le libell\350 du lien ?") || "";
                        startTag = "[url=" + currentSelection + "]";
                        currentSelection = label;
                    } else {
                        var URL = prompt("Quelle est l'url ?");
                        startTag = "[url=" + URL + "]";
                    }
                } else {
                    var URL = prompt("Quelle est l'url ?") || "";
                    var label = prompt("Quel est le libell\350 du lien ?") || "";
                    if (label.length > 0) {
                        startTag = "[url=" + URL + "]";
                        currentSelection = label;
                    } else {
                        startTag = "[url]" + URL;
                        currentSelection = label;
                    }
                }
                break;
            case "citation":
                endTag = "[/quote]";
                if (currentSelection) {
                    if (currentSelection.length > 30) {
                        var auteur = prompt("Quel est l'auteur de la citation ?") || "";
                        startTag = "[quote=" + auteur + "]";
                    } else {
                        var citation = prompt("Quelle est la citation ?") || "";
                        startTag = "[quote=" + currentSelection + "]";
                        currentSelection = citation;
                    }
                } else {
                    var auteur = prompt("Quel est l'auteur de la citation ?") || "";
                    var citation = prompt("Quelle est la citation ?") || "";
                    if (auteur.length) {
                        startTag = "[quote=" + auteur + "]";
                    } else {
                        startTag = "[quote]";
                    }
                    currentSelection = citation;
                }
                break;
            case 'youtube':
                endTag = "[/youtube]";

                startTag = "[youtube]";
                if (currentSelection) {
                    if (validYoutube(currentSelection)) {
                        var isYouTube = validYoutube(currentSelection);
                    } else {
                        var youtube = prompt("Adresse de la vid\350o YouTube ?", currentSelection);
                        var isYouTube = validYoutube(youtube);
                        if (!isYouTube) {
                            return insertTag(startTag, endTag, tagType);
                        }
                    }
                    currentSelection = isYouTube;
                } else {
                    var youtube = prompt("Adresse de la vid\350o YouTube ?");
                    var isYouTube = validYoutube(youtube);
                    if (!isYouTube) {
                        return insertTag(startTag, endTag, tagType);
                    }
                    currentSelection = isYouTube;
                }
                break;
        }
    }
    if (window.ActiveXObject) {
        textRange.text = startTag + currentSelection + endTag;
        textRange.moveStart('character', -endTag.length-currentSelection.length);
        textRange.moveEnd('character', -endTag.length);
        textRange.select();
    } else { // Ce n'est pas IE
        field.value = startSelection + startTag + currentSelection + endTag + endSelection;
        field.focus();
        field.setSelectionRange(startSelection.length + startTag.length, startSelection.length + startTag.length + currentSelection.length);
    }
    field.scrollTop = scroll;
}

function xCodeInitScroll() {
    var positionElementInPage = jQuery('.xCodeMenu').offset().top;
    console.log(positionElementInPage);

    jQuery(window).scroll(
        function() {
            if (jQuery(window).scrollTop() > jQuery('.xCode').offset().top) {
                // fixed
                jQuery('.xCodeMenu').addClass("floatable");
            } else {
                // relative
                jQuery('.xCodeMenu').removeClass("floatable");
            }
        }
    );
    jQuery('.xCodeScroll').on('click', function(event){
        event.preventDefault();
        //jQuery(window).scroll(positionElementInPage);
        jQuery('html, body').animate({
            scrollTop: jQuery('.xCode').offset().top//(jQuery(".xCodeMenu").offset().top - 100)
        }, 200);
    });
}

jQuery(document).ready(function() {
    jQuery(document).on('click', '.xCodeBtnLink', function(event){
        event.preventDefault();
        jQuery.alertModal(jQuery('#xCodeLink').html(), {
            title: "Lien",
            onConfirm: function(modal) {
                var sel = selection();
                console.log(sel.length);
                if (sel.length > 0) {
                    insertTag("[url=" + modal.find('form input').attr('value') + "]", "[/url]");
                } else {
                    insertTag("[url]" + modal.find('form input').attr('value') + "[/url]","");
                }
                modal.modal('hide');
            }
        });
    });
    jQuery(document).on('click', '.xCodeBtnImage', function(event){
        event.preventDefault();
        jQuery.alertModal(jQuery('#xCodeLink').html(), {
            title: "Image",
            onConfirm: function(modal) {
                insertTag("[img]" + modal.find('form input').attr('value') + "[/img]]");
                modal.modal('hide');
            }
        });
    });
    jQuery(document).on('click', '.xCodePreview .close', function(){
        event.preventDefault();
        jQuery('.xCodePreview').slideUp();
    });
    jQuery(document).on('click', '.xCodeBtnPreview', function(event){
        event.preventDefault();
        jQuery('.xCodeLoad').fadeIn(100);

        jQuery('.xCodePreview').slideUp(200, function(){
            jQuery.ajax({
                type: "POST",
                url: "/rpc-bbcode",
                data: "bbcode=" + jQuery('.xCode textarea').val(),
                success: function(data, textStatus, jqXHR) {
                    jQuery('.xCodeLoad').fadeOut(200, function(){
                        if (data.length) {
                            jQuery('.xCodePreview').html('<div><button type="button" class="close">&times;</button></div>' + data).slideDown();
                        }
                    });
                },
                error: function(xhr) {
                    alert ("Oopsie: " + xhr.statusText);
                }
            });
        });

    });
    jQuery(document).on('click', '[data-tag]', function(event){
        event.preventDefault();
        var tag = jQuery(this).attr('data-tag');

        if (typeof(jQuery(this).attr('data-value')) == 'undefined') {
            insertTag("[" + tag + "]", "[/" + tag + "]");
        } else {
            var value = jQuery(this).attr('data-value');
            insertTag("[" + tag + "=" + value + "]", "[/" + tag + "]");
        }
    });
    jQuery(document).on('click', '[data-smiley]', function(event){
        event.preventDefault();
        var tag = jQuery(this).attr('data-smiley');
        insertTag(" " + tag + " ", "");
    });


    jQuery(document).on('click', '.xCodeBtnUploadImage', function(event){
        jQuery.alertModal('<iframe src="http://www.hostingpics.net/iframe_mini.php?color=000000&amp;src=default&amp;bgcolor=FFFFFF" scrolling="auto" frameborder="0" width="400" height="130" hspace="0">Votre navigateur n\'est pas compatible avec les iframes !!!</iframe>', {title: "H&eacute;berger une image", confirm: "Fermer", confirmClass: "btn-u btn-u-red"});
    });
    jQuery(document).on('click', '.xCodeBtnUp', function(event){
        event.preventDefault();
        var height = jQuery('.xCodeContent').height();
        var newHeight = height+100;
        jQuery('.xCodeContent').animate({height: newHeight}, 200);
    });
    jQuery(document).on('click', '.xCodeBtnDown', function(event){
        event.preventDefault();
        var height = jQuery('.xCodeContent').height();
        if (height > 200) {
            var newHeight = height-100;
            jQuery('.xCodeContent').animate({height: newHeight}, 200);
        }
    });
});