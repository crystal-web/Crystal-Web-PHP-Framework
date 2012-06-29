<?php

/***************************************
*	Plugins
***************************************
Router::connect('plugin/:slug-:stat',
				'plugin/slug:([a-z0-9\-]+)/stat:([0-1])');
	
Router::connect('plugin/:slug-setting',
				'plugin/manager/slug:([a-z0-9\-]+)');	
Router::connect('plugin/:slug-setting-:menu',
				'plugin/manager/slug:([a-z0-9\-]+)/menu:([a-z0-9\-]+)');	
				
Router::connect('vote-:id',
				'vote/out/id:([0-9]+)');		


Router::connect('faq/delete-:id',
				'faq/manager/id:([0-9]+)');		

Router::connect('alert',
				'error');
Router::connect('error/delete-:id',
				'error/delete/id:([0-9]+)');				
/***************************************
*	Articles
***************************************
Router::connect('article/:slug-:id',
				'article/post/slug:([a-z0-9_\-]+)/id:([0-9]+)');
Router::connect('article/categorie/:slug-:id',
				'article/cat/slug:([a-z0-9_\-]+)/id:([0-9]+)');
Router::connect('article/commentaire/:id',	'article/commentpost/id:([0-9]+)');

Router::connect('article/post',	'article/admin_addpost');
Router::connect('article/post/:id',	'article/admin_addpost/id:([0-9]+)');
Router::connect('article/post/del/:id',	'article/admin_delpost/id:([0-9]+)');		
Router::connect('article/comment/:slug-:id','article/admin_comment/slug:(y|n|s)/id:([0-9]+)');
Router::connect('article/list', 'article/getlist');
Router::connect('article/conf', 'article/admin_config');


/***************************************
*	Calendrier
***************************************
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
***************************************
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
*	Gestion membres
***************************************
Router::connect('member/edition-de-:id',
				'member/editother/id:([0-9]+)');
Router::connect('member/mailto-:id',
				'member/mailto/id:([0-9]+)');			
Router::connect('member/getmulticompte-:id',
				'member/getmulticompte/id:([0-9]+)');						
Router::connect('member/approb_change_login-:id-:stat',
				'member/approb_change_login/id:([0-9]+)/stat:([0-1])');			

Router::connect('member/profil-:slug',
				'member/index/slug:([A-Za-z0-9\-_]+)');
				
/***************************************
*	Slider
***************************************
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
***************************************
Router::connect('media/del/:id',
				'media/delete/id:([0-9]+)');
				
/***************************************
*	Forum
***************************************
Router::connect('forum/categorie-:slug-:id',
				'forum/cat/slug:([a-z0-9_\-]+)/id:([0-9]+)');
Router::connect('forum/sujet-:slug-:id',
				'forum/sujet/slug:([a-z0-9_\-]+)/id:([0-9]+)');
Router::connect('forum/topic-:slug-:id',
				'forum/topic/slug:([a-z0-9_\-]+)/id:([0-9]+)');
Router::connect('forum/repondre-:slug-:id',
				'forum/respon/slug:([a-z0-9_\-]+)/id:([0-9]+)');
Router::connect('forum/nouveau-:slug-:id',
				'forum/addpost/slug:([a-z0-9_\-]+)/id:([0-9]+)');
Router::connect('forum/admin_repondeur-:id',
				'forum/admin_repondeur/id:([0-9]+)');

Router::connect('forum/edition-:id',
				'forum/edit_post/id:([0-9]+)');
Router::connect('forum/delete-:id',
				'forum/delete_post/id:([0-9]+)');		


Router::connect('dedicace/manager-:s-:id',
				'dedicace/manager/s:([a-z0-9_]+)/id:([0-9]+)');			


/***************************************
*	Viki
***************************************
Router::connect('viki/:slug/edition',
				'viki/edit/slug:([a-zA-Z0-9-_]+)');			
Router::connect('viki/:slug',
				'viki/slug:([a-zA-Z0-9-_]+)');
Router::connect('viki/:slug/restore',
				'viki/restore/slug:([a-zA-Z0-9-_]+)');				

Login : Administrateur
Password : 2cKspQnd

L'adresse IP de votre serveur est : 91.236.239.27
Les serveurs DNS par défaut sont configurés sur Google : 8.8.8.8
*/
?>

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

/***************************************
*	Viki
***************************************/
Router::connect('viki/:slug/edition',
				'viki/edit/slug:([' . $slug . ']+)');			
Router::connect('viki/:slug',
				'viki/slug:([' . $slug . ']+)');
Router::connect('viki/:slug/restore',
				'viki/restore/slug:([' . $slug . ']+)');

Router::connect('boutique/buy-:id',
				'boutique/buy/id:([0-9]+)');
Router::connect('boutique/:slug-:id',
				'boutique/slug:([' . $slug . ']+)/id:([0-9]+)');

Router::connect('boutique/:slug',
				'boutique/slug:([' . $slug . ']+)');		




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
Router::connect('news',
				'article');
Router::connect('news/:slug-:id',
				'article/post/slug:([' . $slug . ']+)/id:([0-9]+)');
Router::connect('news/categorie/:slug-:id',
				'article/cat/slug:([' . $slug . ']+)/id:([0-9]+)');
Router::connect('news/commentaire/:id',
				'article/commentpost/id:([0-9]+)');
Router::connect('news/post',
				'article/admin_addpost');
Router::connect('news/post/:id',
				'article/admin_addpost/id:([0-9]+)');
Router::connect('news/post/del/:id',
				'article/admin_delpost/id:([0-9]+)');		
Router::connect('news/comment/:slug-:id',
				'article/admin_comment/slug:(y|n|s)/id:([0-9]+)');
Router::connect('news/list',
				'article/getlist');
Router::connect('news/conf',
				'article/admin_config');


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
Router::connect('espace-membre/modification-du-mot-de-passe',
				'member/change_password');
/*Router::connect('espace-membre/argh',
				'member/change_login)');//*/
				
Router::connect('espace-membre/deconnection',
				'auth/logout');
Router::connect('espace-membre/validate/:hash',
				'auth/validate/hash:([' . $slug . ']+)');
Router::connect('reglement',
				'auth/cgu');
Router::connect('reglement-dota',
				'auth/dota');
Router::connect('auth/manager/:by-:order',
				'auth/manager/by:([a-z\-]+)/order:(desc|asc)');

Router::connect('espace-membre/profil-:slug',
				'member/index/slug:(['. $slug . ']+)');
				
				
/***************************************
*	Gestion membres
***************************************/
Router::connect('member/edition-de-:id',
				'member/editother/id:([0-9]+)');
Router::connect('member/mailto-:id',
				'member/mailto/id:([0-9]+)');			
Router::connect('member/getmulticompte-:id',
				'member/getmulticompte/id:([0-9]+)');						
Router::connect('member/approb_change_login-:id-:stat',
				'member/approb_change_login/id:([0-9]+)/stat:([0-1])');			
				










				
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
*	Minecraft manager
***************************************/				
Router::connect('minecraft/:cmd',
				'minecraft/cmd:([' . $slug . ']+)');
				
/***************************************
*	Vote
***************************************/
Router::connect('vote-:id',
				'vote/out/id:([a-z0-9__\-]+)');
?>