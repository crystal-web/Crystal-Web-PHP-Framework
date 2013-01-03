
/***************************************
*	Instance principal de JQuery
***************************************/
jQuery(function($){
	
/***************************************
*	Operator SideBar
***************************************/
var boxSize = 150; // Taile de la marge gauche
$('#opSiderbar').css('marginLeft',-boxSize);
// NE PAS EDITER
var toggled = false; 
$('#opSiderbar a.settingbutton').click(function(){ if(toggled){ $('#opSiderbar').animate({marginLeft:-$('#opSiderbar').width()},500); }else{  $('#opSiderbar').animate({marginLeft:0},500); } toggled = !toggled; return false; });

var toggle = function(direction, display) { return function() {
var self = this; var ul = $("ul", this);

	if( ul.css("display") == 'block')
	{
	self["blockUp"] = true; ul["slideUp"]("slow", function() {
		self["blockUp"] = false;
		});
	}
	else
	{
	self["blockDown"] = true; ul["slideDown"]("slow", function() {
		self["blockDown"] = false;
		});
	}
};
}

  

/*-----------------------------------------------------------------------------------------------*/
/*                                      SIMPLE jQUERY TOOLTIP                                    */
/*                                      VERSION: 1.1                                             */
/*                                      AUTHOR: jon cazier                                       */
/*                                      EMAIL: jon@3nhanced.com                                  */
/*                                      WEBSITE: 3nhanced.com                                    */
/*-----------------------------------------------------------------------------------------------*/
$('.toolTip').hover(
	function() {
		this.tip = this.title;
		
		$(this).append(
			'<div id="toolTipWrapper" class="toolTipBottom" style="max-width: 200px; margin-right: 0px; margin-bottom: 0px;display: block;top:30px;">'
				+'<div id="toolTipArrow" style="margin-left: 14.5px; margin-top: -12px;">'
					+'<div id="toolTipArrow_inner"></div>'
				+'</div>'
				+'<div id="toolTipContent">'
					+this.tip 
				+'</div>'
			+'</div>'
		);
		
		this.title = "";
		this.width = $(this).width();
		//console.log(this.width +' ' + (Math.round(this.width/3)));
		$(this).find('#toolTipWrapper').css({left:this.width-25});
	
		$('#toolTipWrapper').fadeIn(300);
	}, function() {
		$('#toolTipWrapper').fadeOut(100);
		//$(this).children().remove();
		$('#toolTipWrapper').remove();
			this.title = this.tip;
	}
);



});
/***************************************
*	END Instance principal de JQuery
***************************************/


$(document).ready( function () {
	// On cache les sous-menus
	// sauf celui qui porte la classe "open_at_load" :
	$("ul.subMenu:not('.open_at_load')").hide();
	// On selectionne tous les items de liste portant la classe "toggleSubMenu"

	// et on remplace l'element span qu'ils contiennent par un lien :
	$("li.toggleSubMenu span").each( function () {
		// On stocke le contenu du span :
		var TexteSpan = $(this).text();
		$(this).replaceWith('<a href="" title="Afficher le sous-menu">' + TexteSpan + '</a>') ;
	} ) ;

	// On modifie l'evenement "click" sur les liens dans les items de liste
	// qui portent la classe "toggleSubMenu" :
	$("li.toggleSubMenu > a").click( function () {
		// Si le sous-menu etait deja ouvert, on le referme :
		if ($(this).next("ul.subMenu:visible").length != 0) {
			$(this).next("ul.subMenu").slideUp("normal", function () { $(this).parent().removeClass("open") } );
		}
		// Si le sous-menu est cache, on ferme les autres et on l'affiche :
		else {
			$("ul.subMenu").slideUp("normal", function () { $(this).parent().removeClass("open") } );
			$(this).next("ul.subMenu").slideDown("normal", function () { $(this).parent().addClass("open") } );
		}
		// On empÃƒÂªche le navigateur de suivre le lien :
		return false;
	});

	
	
} ) ;