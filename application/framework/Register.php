<?php
/***************************************
*	Plugins
***************************************/
Router::connect('plugin/:slug',
				'plugin/manager/slug:([a-z0-9\-]+)');
				
/***************************************
*	Articles
***************************************/
Router::connect('article/:slug-:id',
				'article/post/slug:([a-z0-9\-]+)/id:([0-9]+)');
Router::connect('article/categorie/:slug-:id',
				'article/cat/slug:([a-z0-9\-]+)/id:([0-9]+)');
Router::connect('article/commentaire/:id',	'article/commentpost/id:([0-9]+)');

Router::connect('article/post',	'article/admin_addpost');
Router::connect('article/post/:id',	'article/admin_addpost/id:([0-9]+)');
Router::connect('article/post/del/:id',	'article/admin_delpost/id:([0-9]+)');		
Router::connect('article/comment/:slug-:id','article/admin_comment/slug:(y|n|s)/id:([0-9]+)');
Router::connect('article/list', 'article/getlist');
Router::connect('article/conf', 'article/admin_config');


/***************************************
*	Calendrier
***************************************/
Router::connect('calendrier',
				'calendar');
Router::connect('calendrier/:year',
				'calendar/year:([0-9]+)');
Router::connect('calendrier/:year-:month',
				'calendar/year:([0-9]+)/month:([0-9]+)');
// Event
Router::connect('calendrier/event',
				'calendar/event');
Router::connect('calendrier/:year-:month-:day',
				'calendar/event/year:([0-9]+)/month:([0-9]+)/day:([0-9]+)');


/***************************************
*	Espace Membre
***************************************/
Router::connect('espace-membre',
				'auth');
Router::connect('espace-membre/recuperation',
				'auth/forgotpassword');
Router::connect('espace-membre/enregistrement',
				'auth/subscribe');
Router::connect('espace-membre/deconnection',
				'auth/logout');
Router::connect('espace-membre/validate/:hash',
				'auth/validate/hash:([a-z0-9\-]+)');

				
/***************************************
*	Slider
***************************************/
Router::connect('sliderpop',
				'sliderpop');
Router::connect('slidepop/:id-:stat',
				'sliderpop/id:([0-9]+)/stat:([0-1])');
Router::connect('sliderpop/efface-:id',
				'sliderpop/del/id:([0-9]+)');		
Router::connect('sliderpop/editer-:id',
				'sliderpop/edit/id:([0-9]+)');	
				
/***************************************
*	Media
***************************************/
Router::connect('media/del/:id',
				'media/delete/id:([0-9]+)');
?>