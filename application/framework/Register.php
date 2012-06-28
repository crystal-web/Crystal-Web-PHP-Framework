<?php
/*
 * Router::connect('ce-que-je-souhaite', 'ce-que-j-ai-reelement');
 * donne site.tld/ce-que-je-souhaite
 * 
 * Router::connect('alert/del-:id',
 *				'error/delete/id:([0-9a-z]+)');
 * donne alert/del-"id passé par l'expression réguliere"
 * 
 * Note: pensez a l'inversion j'ai id:(PCRE) j'obtiens (PCRE):id mais :id disparait de l'url
 ***/

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
