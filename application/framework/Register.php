<?php
/*##################################################
 *                               Register.php
 *                            -------------------
 *   begin                : 2012-03-08
 *   copyright            : (C) 2012 DevPHP
 *   email                : developpeur@crystal-web.org
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/


$slug = 'A-Za-z0-9_\-';

Router::connect('rpc-:cmd', 'rpc/cmd:([0-9a-z]+)');

Router::connect('database/structure/:table', 'database/structure/table:([' . $slug . ']+)');
Router::connect('database/dump/:table', 'database/dump/table:([' . $slug . ']+)');
Router::connect('database/optimize/:table', 'database/optimize/table:([' . $slug . ']+)');

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
*	Viki
***************************************/

Router::connect('viki/:slug/edition',
				'viki/edit/slug:([' . $slug . ']+)');			
Router::connect('viki/:slug',
				'viki/slug:([' . $slug . ']+)');
Router::connect('viki/:slug/restore',
				'viki/restore/slug:([' . $slug . ']+)');
				
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
Router::connect('auth/recovery/:slug',
		'auth/recovery/slug:([' . $slug . ']+)');
Router::connect('auth/validate/:slug',
		'auth/validate/slug:([' . $slug . ']+)');
Router::connect('auth/enregistrement',
				'auth/subscribe');
Router::connect('auth/deconnection',
				'auth/logout');
Router::connect('reglement',
				'auth/cgu');


/***************************************
*	Gestion membres
***************************************/	
Router::connect('member/profil-:slug',
				'member/index/slug:(['. $slug . ']+)');

Router::connect('membermanager/edituser-:id', 'membermanager/edituser/id:([0-9]+)');
Router::connect('membermanager/rpc-:cmd', 'membermanager/rpc/cmd:([0-9a-z]+)');

Router::connect('membermanager/mail-:id', 'membermanager/mail/id:([0-9]+)');

Router::connect('membermember/multiaccounts-:id', 'membermanager/multiaccounts/id:([0-9]+)');

Router::connect('membermanager/valide-:has',
				'membermanager/valide/has:(on|off)');	
Router::connect('membermanager/approb_change_login-:id-:stat',
				'membermanager/approb_change_login/id:([0-9]+)/stat:([0-1])');		

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
Router::connect('mediamanager/del/:id',
				'mediamanager/delete/id:([0-9]+)');				
Router::connect('mediamanager/browser/:type',
				'mediamanager/browser/type:([' . $slug . ']+)');
Router::connect('mediamanager/browser/:type/:sub',
				'mediamanager/browser/type:([' . $slug . ']+)/sub:([' . $slug . ']+)');
Router::connect('mediamanager/fileinfo/:id',
				'mediamanager/fileinfo/id:([0-9]+)');				
Router::connect('mediamanager/edit/:id',
		'mediamanager/edit/id:([0-9]+)');

/***************************************
*	Forum
***************************************/
Router::connect('forum/categorie-:slug-:id',
				'forum/categorie/slug:([' . $slug . ']+)/id:([0-9]+)');
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
Router::connect('forum/goodhelp-:id',
                'forum/goodhelp/id:([0-9]+)');
Router::connect('forum/edition-:id',
				'forum/edit_post/id:([0-9]+)');
Router::connect('forum/delete-:id',
				'forum/delete_post/id:([0-9]+)');
Router::connect('forum/rpc/:cmd-:id',
                'forum/rpc/cmd:([' . $slug . ']+)/id:([0-9]+)');
Router::connect('forum/manager/:cmd-:id',
				'forum/manager/cmd:([' . $slug . ']+)/id:([0-9]+)');
Router::connect('forum/cat_order/:type/:id-:direction',
				'forum/cat_order/id:([0-9]+)/direction:(u|d)/type:(cat|sujet)');
Router::connect('forum/repair-:phase',
				'forum/repair/phase:([' . $slug . ']+)');


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


Router::connect('button-:siteslug',
				'button/siteslug:([' . $slug . ']+)');
Router::connect('button/out/:siteslug',
				'button/out/siteslug:([' . $slug . ']+)');

Router::connect('stat-:siteslug',
				'stat/siteslug:([' . $slug . ']+)');
Router::connect('vote-:siteslug',
				'vote/siteslug:([' . $slug . ']+)');
				
Router::connect('topsite/info-:siteslug', 
				'topsite/info/siteslug:([' . $slug . ']+)');
Router::connect('topsite/stat-:siteslug', 
				'topsite/stat/siteslug:([' . $slug . ']+)');
Router::connect('topsite/categorie-:catslug',
				'topsite/category/catslug:([' . $slug . ']+)');
				

Router::connect('topsite/ajouter/categorie-:catslug',
				'topsite/addin/catslug:([' . $slug . ']+)');
Router::connect('topsite/ajouter',
				'topsite/add');
Router::connect('topsite/vote-:siteslug',
				'topsite/vote/siteslug:([' . $slug . ']+)');
Router::connect('topsite/editer-:siteslug',
				'topsite/edit/siteslug:([' . $slug . ']+)');
Router::connect('topsite/effacer-:siteslug',
				'topsite/delete/siteslug:([' . $slug . ']+)');
Router::connect('topsite/mes-sites',
				'topsite/mesite');
Router::connect('topsite/approuver-:siteslug',
				'topsite/approved/siteslug:([' . $slug . ']+)');
Router::connect('topsite/desapprouver-:siteslug',
				'topsite/disapproved/siteslug:([' . $slug . ']+)');
Router::connect('topsite/pending/:stat/:siteslug',
				'topsite/pending/stat:(disapproved|approved|blacklist)/siteslug:([' . $slug . ']+)');

				
Router::connect('topsite/button-:siteslug',
				'topsite/button/siteslug:([' . $slug . ']+)');
Router::connect('topsite/button-:siteslug/:size',
				'topsite/button/siteslug:([' . $slug . ']+)/size:(90|468)');
Router::connect('topsite/out/:siteslug',
				'topsite/out/siteslug:([' . $slug . ']+)');
				
				

?>
