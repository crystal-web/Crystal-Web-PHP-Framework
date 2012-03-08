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

				
/***************************************
*	Slider
***************************************/
Router::connect('slidepop',
				'sliderpop/pop');

				
/***************************************
*	Media
***************************************/
Router::connect('media/del/:id',
				'media/delete/id:([0-9]+)');				

				
/***************************************
*	Forum
***************************************/
Router::connect('forum/categorie-:slug-:id',
				'forum/cat/slug:([a-z0-9\-]+)/id:([0-9]+)');
Router::connect('forum/sujet-:slug-:id',
				'forum/sujet/slug:([a-z0-9\-]+)/id:([0-9]+)');
Router::connect('forum/topic-:slug-:id',
				'forum/topic/slug:([a-z0-9\-]+)/id:([0-9]+)');
Router::connect('forum/repondre-:slug-:id',
				'forum/respon/slug:([a-z0-9\-]+)/id:([0-9]+)');
Router::connect('forum/nouveau-:slug-:id',
				'forum/addpost/slug:([a-z0-9\-]+)/id:([0-9]+)');

				
/***************************************
*	Token
***************************************/
Router::connect('etok',
				'token');
Router::connect('etok/ajax/:id',
				'token/ajax/id:([0-9]+)');			
Router::connect('etok/historique',
				'token/history');
				
/***************************************
*	Shop
***************************************/
Router::connect('shop/:slug-:id',
				'shop/slug:([a-z0-9\-]+)/id:([0-9]+)');	
?>