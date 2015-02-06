<?php
/**
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
