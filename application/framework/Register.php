<?php
/**
 * post 9420 
 *
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/
if (!defined('__APP_PATH'))
{
	echo '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You don\'t have permission to access this file on this server.</p></body></html>'; die;
}

$slug = 'A-Za-z0-9_\-';
// !important
Router::connect('sitemap.xml', 'sitemap/xml');
Router::connect('robots.txt', 'sitemap/robots');
Router::connect('rpc-:cmd', 'rpc/cmd:([0-9a-z]+)');

/***************************************
*   Support BETA
***************************************/
Router::connect('support/:id/:passcode/:status', 'support/id:([0-9]+)/passcode:(['.$slug.']+)/status:(pending|finish|spam)');
Router::connect('support/:id/:passcode', 'support/id:([0-9]+)/passcode:(['.$slug.']+)');
Router::connect('support/manager/:state', 'support/manager/state:(pending|finish|spam+)');

/***************************************
*   Panel Admin
***************************************/
Router::connect('panelcontrol/error/delete-:id', 'panelcontrol/error/delete/id:([0-9a-z]+)');
Router::connect('panelcontrol/error/:fn', 'panelcontrol/error/fn:(delete)');
Router::connect('panelcontrol/log/:fn', 'panelcontrol/log/fn:(delete)');
Router::connect('alert/delele', 'error/delete');

/***************************************
*	Plugins
***************************************/
Router::connect('plugin/:slug-:stat', 'plugin/slug:([' . $slug . ']+)/stat:([0-1])');
Router::connect('plugin/:slug-setting', 'plugin/manager/slug:([' . $slug . ']+)');
Router::connect('plugin/:slug-setting-:menu', 'plugin/manager/slug:([' . $slug . ']+)/menu:(' . $slug . ']+)');

/***************************************
*	Error
***************************************/
Router::connect('alert', 'error');
Router::connect('alert/del-:id', 'error/delete/id:([0-9a-z]+)');
Router::connect('alert/delele', 'error/delete');

/***************************************
*	Auth
***************************************/
Router::connect('auth/recovery/slug:([' . $slug . ']+)/uid:([0-9]+)');