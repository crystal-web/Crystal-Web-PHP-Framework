<?php
$slug = 'A-Za-z0-9_\-';

/***************************************
*	Error
***************************************/
Router::connect('alert',
				'error');
Router::connect('alert/del-:id',
				'error/delete/id:([0-9a-z]+)');
Router::connect('alert/delele',
				'error/delete');
				
/***************************************
*	Plugins
***************************************/
Router::connect('plugin/:slug-:stat',
				'plugin/slug:([a-z0-9\-]+)/stat:([0-1])');
	
Router::connect('plugin/:slug-setting',
				'plugin/manager/slug:([a-z0-9\-]+)');	
Router::connect('plugin/:slug-setting-:menu',
				'plugin/manager/slug:([a-z0-9\-]+)/menu:([a-z0-9\-]+)');

Router::connect('livre-d-or',
				'goldenbook');
Router::connect('livre-d-or/valider-:id',
				'goldenbook/valider/id:([0-9]+)');
Router::connect('livre-d-or/supprimer-:id',
				'goldenbook/supprimer/id:([0-9]+)');
Router::connect('livre-d-or/bannir-:id',
				'goldenbook/look/id:([0-9]+)');

/***************************************
*	Contact
***************************************/
Router::connect('nous-contacter',
				'contact');
Router::connect('nous-contacter/read',
				'contact/read');
Router::connect('nous-contacter/read-:id',
				'contact/read/id:([0-9]+)');
				
/***************************************
*	FAQ
***************************************/
Router::connect('faq',
				'faq');
Router::connect('faq/manager',
				'faq/manager');
Router::connect('faq/manager-:id',
				'faq/manager/id:([0-9]+)');
				
Router::connect('minecraft-map-viewer',
				'map');			
/***************************************
*	Articles
***************************************/
// Router::connect('article', 'article');
Router::connect('article/:slug-:id',
				'article/post/slug:([' . $slug . ']+)/id:([0-9]+)');
Router::connect('article/categorie/:slug-:id',
				'article/cat/slug:([' . $slug . ']+)/id:([0-9]+)');
Router::connect('article/commentaire/:id',
				'article/commentpost/id:([0-9]+)');
Router::connect('article/post',
				'article/admin_addpost');
Router::connect('article/post/:id',
				'article/admin_addpost/id:([0-9]+)');
Router::connect('article/post/del/:id',
				'article/admin_delpost/id:([0-9]+)');		
Router::connect('article/comment/:slug-:id',
				'article/admin_comment/slug:(y|n|s)/id:([0-9]+)');
Router::connect('article/list',
				'article/getlist');

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
Router::connect('calendar/participe/:id/:s',
				'calendar/participe/id:([0-9]+)/s:(0|1)');

/***************************************
*	Espace Membre
***************************************/

Router::connect('auth/recuperation',
				'auth/forgotpassword');
Router::connect('auth/enregistrement',
				'auth/subscribe');
Router::connect('auth/modification-du-mot-de-passe',
				'member/change_password');
				
Router::connect('auth/deconnection',
				'auth/logout');
Router::connect('auth/validate/:hash',
				'auth/validate/hash:([' . $slug . ']+)');
Router::connect('reglement',
				'auth/cgu');
Router::connect('auth/manager/:by-:order',
				'auth/manager/by:([a-z\-]+)/order:(desc|asc)');


				

/***************************************
*	Gestion membres
***************************************/
Router::connect('membre/edition-de-:id',
				'member/editother/id:([0-9]+)');
Router::connect('membre/mailto-:id',
				'member/mailto/id:([0-9]+)');			
Router::connect('membre/getmulticompte-:id',
				'member/getmulticompte/id:([0-9]+)');						
Router::connect('membre/approb_change_login-:id-:stat',
				'member/approb_change_login/id:([0-9]+)/stat:([0-1])');			
Router::connect('membre/profil-:slug',
				'member/index/slug:(['. $slug . ']+)');
				
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
Router::connect('media/browser/:type',
				'media/browser/type:([' . $slug . ']+)');
Router::connect('media/browser/:type/:sub',
				'media/browser/type:([' . $slug . ']+)/sub:([' . $slug . ']+)');
Router::connect('media/fileinfo/:id',
				'media/fileinfo/id:([0-9]+)');				


/***************************************
*	Forum
***************************************/
Router::connect('forum/categorie-:slug-:id',
				'forum/cat/slug:([' . $slug . ']+)/id:([0-9]+)');
Router::connect('forum/sujet-:slug-:id',
				'forum/sujet/slug:([' . $slug . ']+)/id:([0-9]+)');
Router::connect('forum/topic-:slug-:id',
				'forum/topic/slug:([' . $slug . ']+)/id:([0-9]+)');
Router::connect('forum/repondre-:slug-:id',
				'forum/respon/slug:([' . $slug . ']+)/id:([0-9]+)');
Router::connect('forum/nouveau-:slug-:id',
				'forum/addpost/slug:([' . $slug . ']+)/id:([0-9]+)');
Router::connect('forum/admin_repondeur-:id',
				'forum/admin_repondeur/id:([0-9]+)');

Router::connect('forum/edition-:id',
				'forum/edit_post/id:([0-9]+)');
Router::connect('forum/delete-:id',
				'forum/delete_post/id:([0-9]+)');

 
/***************************************
*	Projet
***************************************/
Router::connect('projet/voir-:id',
				'projet/voir/id:([0-9]+)');
Router::connect('projet/modifier-:id',
				'projet/modifier/id:([0-9]+)');
Router::connect('projet/proposer-:id',
				'projet/proposer/id:([0-9]+)');
Router::connect('projet/supprimer-:id',
				'projet/supprimer/id:([0-9]+)');


Router::connect('projetmanager/voir-:id',
				'projetmanager/voir/id:([0-9]+)');



Router::connect('ticket/voir-:id',
				'ticket/read/id:([0-9]+)');
Router::connect('ticket/fermer-:id',
				'ticket/close/id:([0-9]+)');


Router::connect('ticket/categorie-:id',
				'ticket/bycat/id:([0-9]+)');

Router::connect('messenger/live-:id',
				'messenger/live/id:([' . $slug . ']+)');
Router::connect('messenger/chat-:id',
				'messenger/chat/id:([' . $slug . ']+)');

Router::connect('messenger/find-:search',
				'messenger/find/search:([' . $slug . ']+)');
Router::connect('messenger/delete-:pid',
				'messenger/delete/pid:([0-9]+)');
Router::connect('messenger/read-:id',
				'messenger/read/id:([' . $slug . ']+)');
?>