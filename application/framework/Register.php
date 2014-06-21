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

Router::connect('pager-:page','pager/page:([' . $slug . ']+)');
Router::connect('pager/manager/:page/:action','pager/manager/page:([' . $slug . ']+)/action:([' . $slug . ']+)');
Router::connect('pager/manager/:page','pager/manager/page:([' . $slug . ']+)');

/***************************************
 *	Article
 ***************************************/
Router::connect('article/manager/:action', 'article/manager/action:(make|comment)');
Router::connect('article/manager/:id/:action', 'article/manager/id:([0-9]+)/action:(del|edit|comment|validate|spawn)');
Router::connect('article/:page/:id', 'article/page:(['.$slug.'\.]+)/id:([0-9]+)');


Router::connect('faq', 'faq');
Router::connect('faq/manager', 'faq/manager');
//Router::connect('faq/manager/edit-:edit', 'faq/manager/edit:([0-9]+)');
Router::connect('faq/manager/:action/:id', 'faq/manager/action:(del|edit|make)/id:([0-9]+)');
//Router::connect('faq/manager/del-:del', 'faq/manager/action:del/id:([0-9]+)');

Router::connect('tools/minify/:quoi', 'tools/minify/quoi:(js|css)');

Router::connect('feedback/cat/:cat', 'feedback/cat/cat:([a-z]+)');
Router::connect('feedback/post/:id', 'feedback/post/id:([0-9]+)');

Router::connect('support/:id/:passcode/:status', 'support/id:([0-9]+)/passcode:(['.$slug.']+)/status:(pending|finish|spam)');
Router::connect('support/:id/:passcode', 'support/id:([0-9]+)/passcode:(['.$slug.']+)');
Router::connect('support/manager/:state', 'support/manager/state:(pending|finish|spam+)');


$trackOptions = 'timeline|tasks|team|tickets|ticket-new|ticket-edit';
Router::connect('track/:project/:section/:id', 'track/project:([' . $slug . ']+)/section:('.$trackOptions.')/id:([0-9]+)');
Router::connect('track/:project/:section', 'track/project:([' . $slug . ']+)/section:('.$trackOptions.')');
Router::connect('track/:project', 'track/project:([' . $slug . ']+)');


Router::connect('panelcontrol/error/delete-:id', 'panelcontrol/error/delete/id:([0-9a-z]+)');
Router::connect('panelcontrol/error/:fn', 'panelcontrol/error/fn:(delete)');
Router::connect('panelcontrol/log/:fn', 'panelcontrol/log/fn:(delete)');
Router::connect('alert/delele', 'error/delete');


Router::connect('labs/:player', 'labs/player:(['.$slug.']+)');

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
 *	Forum
 ***************************************/
Router::connect('forum/categorie-:slug-:id', 'forum/categorie/slug:([' . $slug . ']+)/id:([0-9]+)');
Router::connect('forum/sujet-:slug-:id', 'forum/sujet/slug:([' . $slug . ']+)/id:([0-9]+)');
Router::connect('forum/repondre-:slug-:id', 'forum/repondre/slug:([' . $slug . ']+)/id:([0-9]+)');
Router::connect('forum/nouveau-:slug-:id', 'forum/nouveau/slug:([' . $slug . ']+)/id:([0-9]+)');
Router::connect('forum/avatar/:user', 'forum/avatar/user:([' . $slug . ']+)');

Router::connect('forum/action/:action-:id', 'forum/action/action:([' . $slug . ']+)/id:([0-9]+)');
Router::connect('forum/action/:action', 'forum/action/action:([' . $slug . ']+)');
Router::connect('forum/topic-:slug-:id', 'forum/topic/slug:([' . $slug . ']+)/id:([0-9]+)');
Router::connect('forum/topic/:user', 'forum/topic/user:([' . $slug . ']+)');



Router::connect('forum/goodhelp-:id', 'forum/goodhelp/id:([0-9]+)');
Router::connect('forum/edition-:id', 'forum/edit_post/id:([0-9]+)');
Router::connect('forum/delete-:id', 'forum/delete_post/id:([0-9]+)');
Router::connect('forum/rpc-:id-:cmd', 'forum/rpc/cmd:deletepost/id:([0-9]+)');
//UPDATE Router::connect('forum/manager/:cmd-:id', 'forum/manager/cmd:([' . $slug . ']+)/id:([0-9]+)');
Router::connect('forum/manager_repondeur-:id', 'forum/manager_repondeur/id:([0-9]+)');

Router::connect('forum/repair-:phase', 'forum/repair/phase:([' . $slug . ']+)');

Router::connect('forum/manager/:action/:cmd/:id', 'forum/manager/action:([' . $slug . ']+)/cmd:([' . $slug . ']+)/id:([0-9]+)');
Router::connect('forum/manager/:action-:id', 'forum/manager/action:([' . $slug . ']+)/id:([0-9]+)');
Router::connect('forum/manager/:action-:phase', 'forum/manager/action:([' . $slug . ']+)/phase:([' . $slug . ']+)');
Router::connect('forum/manager/:action', 'forum/manager/action:([' . $slug . ']+)');
Router::connect('forum/manager/:action/:type/:id-:direction', 'forum/manager/action:([' . $slug . ']+)/id:([0-9]+)/direction:(u|d)/type:(cat|sujet)');
