function external()
{
// On récupère tous les liens (<a>) du document
// dans une variable (un array), ici liens.
var liens = document.getElementsByTagName('a');
// Une boucle qui parcourt le tableau (array) liens du début à la fin.
for (var i = 0 ; i < liens.length ; ++i)  {
	// On détecte les liens externes et on leur
	// applique au passage la classe "lien_ext".
	var siteUrl = document.location.hostname;
	var regEx = new RegExp("^http(s)?://");
	var regEx2 = new RegExp("^http(s)?://(www\.)?" + siteUrl);
	if (regEx.test(liens[i].href) && !regEx2.test(liens[i].href))  {
		if (liens[i].text.length > 3)
		{
		liens[i].className = 'external link';
		}
		else
		{
		liens[i].className = 'external';
		}
		if (liens[i].title.length == 0) 
		{
		liens[i].title = 'S\'ouvre dans un nouvel onglet';
		}
		liens[i].target = '_blank';
	}
}

}


jQuery(function($){
	external();
	$("body").on("click", ".external", function(event){
	event.preventDefault();
	  var elem = $(this);
	  $.get(document.location.hostname + '/click',{id:elem.attr('href')});
	 setTimeout(function() {
		window.open( elem.attr('href') );
		}, 100);
	});
});


function twitterCallback2(twitters)
{
var statusHTML = [];
	for (var i=0; i<twitters.length; i++)
	{
	var username = twitters[i].user.screen_name;
	var statusT = twitters[i].text.replace(/((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;'">\:\s\<\>\)\]\!])/g, function(url) {
	return '<a href="'+url+'">'+url+'</a>';	
	}).replace(/\B@([_a-z0-9]+)/ig, function(reply) {
	return  reply.charAt(0)+'<a href="http://twitter.com/'+reply.substring(1)+'">'+reply.substring(1)+'</a>';
	});
	statusHTML.push('<div style="margin-bottom:5px;">'+statusT+'</div>');
	}
document.getElementById('twitterStatus').innerHTML = statusHTML.join('');
}