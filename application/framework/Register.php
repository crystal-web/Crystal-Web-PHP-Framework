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

/***************************************
*	Plugins
***************************************/
Router::connect('plugin/:slug-:stat', 'plugin/slug:([a-z0-9\-]+)/stat:([0-1])');
Router::connect('plugin/:slug-setting', 'plugin/manager/slug:([a-z0-9\-]+)');
Router::connect('plugin/:slug-setting-:menu', 'plugin/manager/slug:([a-z0-9\-]+)/menu:([a-z0-9\-]+)');

/***************************************
*	Error
***************************************/
Router::connect('alert', 'error');
Router::connect('alert/del-:id', 'error/delete/id:([0-9a-z]+)');
Router::connect('alert/delele', 'error/delete');

/***************************************
*	Contact
***************************************/
Router::connect('nous-contacter', 'contact');
Router::connect('nous-contacter/read', 'contact/read');
Router::connect('nous-contacter/read-:id', 'contact/read/id:([0-9]+)');

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
?>
