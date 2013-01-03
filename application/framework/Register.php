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
*	Contact
***************************************/
Router::connect('nous-contacter',
				'contact');
Router::connect('nous-contacter/read',
				'contact/read');
Router::connect('nous-contacter/read-:id',
				'contact/read/id:([0-9]+)');

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
?>
