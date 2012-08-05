(function($) {
	/***************************************
	*	Force l'ouverture des liens externe dans une nouvelle fenetre
	***************************************/
	$.fn.externalUrl = function (args){
        var defauts=
        {
        	"textExternalLink": "S'ouvre dans un nouvel onglet",
            "classExternalLink": "external",
        };  
        
        //On fusionne nos deux objets ! =D
        var parametres=$.extend(defauts, args); 
		
		// On récupère tous les liens (<a>) du document
		// dans une variable (un array), ici liens.
		var liens = $('a');
		var siteUrl = document.location.hostname;

		// Une boucle qui parcourt le tableau (array) liens du début à la fin.
		for (var i = 0 ; i < liens.length ; ++i)  {
			// On détecte les liens externes et on leur
			// applique au passage la classe "lien_ext".

			var regEx = new RegExp("^http(s)?://");
			var regEx2 = new RegExp("^http(s)?://(www\.)?" + siteUrl);
			if (regEx.test(liens[i].href) && !regEx2.test(liens[i].href))  {

				if (liens[i].text.length > 3)
				{
					liens[i].className = parametres.classExternalLink + ' link';
				}
				else
				{
					liens[i].className = parametres.classExternalLink;
				}

				if (liens[i].title.length == 0) 
				{
					liens[i].title = parametres.textExternalLink;
				}
				liens[i].target = '_blank';
			}
		}
	}
	

	/***************************************
	*	Affiche un thumbsite du site quand la sourie passe
	***************************************/
	$.fn.previewUrl = function (args){
		var defauts=
		{
			"showTitle": true,
			"title": false,
			"xOffset": 10,
			"yOffset": 30
		};

		//On fusionne nos deux objets ! =D
		var parametres=$.extend(defauts, args);
		

		$("a.screenshot.link").hover(function(e){
			
			this.tmpTitle = this.title;
			this.title = "";
			
			if (!parametres.title)
			{
				var previewTitle = (this.tmpTitle != "") ? "<br/>" + this.tmpTitle : "";
			}
			else
			{
				var previewTitle = "<br/>" + parametres.title;
			}
 
			if (parametres.showTitle)
			{
				$("body").append("<p id='screenshot' style=\"position:absolute;border:1px solid #ccc;padding:5px;color:#fff;display:none;background: #333;\">" +
						"<img src='http://open.thumbshots.org/image.pxf?url="+ this.href.replace(new RegExp('http(s)?://'), '') +"' alt='url preview' style=\"width:150px;height:120px;background: #fff url(" + CW_PATH + "/media/image/gif/load.gif) no-repeat center;\">"+ previewTitle +"</p>"); 
			}
			else
			{
				$("body").append("<p id='screenshot' style=\"position:absolute;border:1px solid #ccc;padding:5px;color:#fff;display:none;background: #333;\">" +
						"<img src='http://open.thumbshots.org/image.pxf?url="+ this.href.replace(new RegExp('http(s)?://'), '') +"' alt='url preview' style=\"width:150px;height:120px;background: #fff url(" + CW_PATH + "/media/image/gif/load.gif) no-repeat center;\"></p>"); 
			}

			$("#screenshot")
			.css("top",(e.pageY - parametres.xOffset) + "px")
			.css("left",(e.pageX + parametres.yOffset) + "px")
			.fadeIn("slow");
		},
		function(){
			this.title = this.tmpTitle;
			$("#screenshot").fadeOut("slow").remove();
		}); 

		$("a.screenshot").mousemove(function(e){
			$("#screenshot")
			.css("top",(e.pageY - parametres.xOffset) + "px")
			.css("left",(e.pageX + parametres.yOffset) + "px");
		});
	}	
	
	
	
})(jQuery);


/***************************************
*	Toutes les fonctions JQuery http://static.generation-nt.com/img/ui.totop.png
***************************************/
jQuery(function($){
//external();
/***************************************
*	Scroll Top
***************************************/
//$('#scrollTop').click(function() { $(document).scrollTop(); });
	
	
	$(document).ready(function(){
		$('html').externalUrl({"classExternalLink": "screenshot"});
		$('html').previewUrl({"showTitle": false});
	});




	
	
	
	function imagePreview(){ 
	    /* CONFIG */
	            
	            xOffset = 10;
	            yOffset = 30;
	            
	            // these 2 variable determine popup's distance from the cursor
	            // you might want to adjust to get the right result
	            
	    /* END CONFIG */
	    $("a.preview").hover(function(e){
	            this.t = this.title;
	            this.title = "";        
	            var c = (this.t != "") ? "<br/>" + this.t : "";
	            $("body").append("<p id='preview'><img src='"+ this.href +"' alt='Image preview' />"+ c +"</p>");                                                                
	            $("#preview")
	                    .css("top",(e.pageY - xOffset) + "px")
	                    .css("left",(e.pageX + yOffset) + "px")
	                    .fadeIn("fast");                                                
	},
	    function(){
	            this.title = this.t;    
	            $("#preview").remove();
	}); 
	    $("a.preview").mousemove(function(e){
	            $("#preview")
	                    .css("top",(e.pageY - xOffset) + "px")
	                    .css("left",(e.pageX + yOffset) + "px");
	    });                     
	};








//$("#helper-bar").hide(); 
var pixel = 300; // Pixel a partir duquel on affiche la bar
$(function () {
	$(window).scroll(function () { if ($(this).scrollTop() > pixel) { $('#scrollTop').fadeIn(); } else { $('#scrollTop').fadeOut(); }	});
	$('#scrollTop').click(function()
	{
		$('body,html').animate({scrollTop: $('#navigateur').position().top }, 750);
	});
});


	// Si on clique sur un lien contenant la class .external
	// on ouvre dans une nouvelle fenetre
	$("body").on("click", ".external", function(event){
	event.preventDefault();
	  var elem = $(this);
	  $.get(document.location.hostname + '/click',{id:elem.attr('href')});
	 setTimeout(function() {
		window.open( elem.attr('href') );
		}, 100);
	});
	
	
	// Lorsque l'on click sur une class .collapse
	// On cache ou affiche les parents .collapse_+block ID
	$("body").on("click", ".collapse", function(event){
	event.preventDefault();
		var elem = $(this);
		var coll = $('.collapse_' + elem.attr('id'));
		if (coll.is(':visible'))
		{
		coll.slideUp();
		}
		else
		{
		coll.slideDown();
		}
	});

	
	
	/***************************************
	*	Les evenement du site
	*	Si ils sont loggué dans event
	***************************************/
    function refreshEvent() {
        $.ajax({
            url: CW_PATH + "/event/ajax", 
			ifModified:true,
            success: function(content){
                $('#event').html(content);
				$('#event').animate({scrollTop: $('#event')[0].scrollHeight}, 1500);
            }
 
        });
        setTimeout(refreshEvent, 5000);
    }
	if ($('#event').size()>0)
	{
    refreshEvent();
	}
	
	
	
});










