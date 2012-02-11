<?php
/*** Radical ban ip ***/
if (Securite::isLockIp()){header("Location: http://kristof.123.fr/sorry.php");exit();}

/*** Set user level ***/
$_SESSION['user']['power_level'] = (isSet($_SESSION['user']['power_level'])) ? $_SESSION['user']['power_level'] : 1;

/*** Tokken ***/
$_SESSION['user']['tokken'] = (isSet($_SESSION['user']['tokken'])) ? $_SESSION['user']['tokken'] : md5(magicword . (time() * rand()) );
if (isSet($_GET['tokken']))
{
	if($_SESSION['user']['tokken'] != $_GET['tokken'])
	{
	header('HTTP/1.1 403 Forbidden');
	die('403 - Forbidden: Access is denied');
	}
}


/*** Instancie  ***/
$mvc = new mvc;
$mvc->memory_real_usage = (function_exists('memory_get_peak_usage') ? convert(memory_get_peak_usage(TRUE)) : 0 );
$mvc->memory_start = (function_exists('memory_get_usage') ? memory_get_usage() : 0 );

/*** load the router ***/
$mvc->router = new router($mvc);
$mvc->router->setPath(__APP . '/controller');	// set the controller path

/*** load up the template ***/
$mvc->template = new template($mvc);
$mvc->template->setPath(__VIEWS);
$mvc->template->__ismobile = $mvc->router->isMobile;	//!\ A MODIFIER

/*** load html ***/
$mvc->html = new html($mvc);
$mvc->html->setSrcCss(__CDN . '/files/css/common.css');
$mvc->html->setSrcScript(__CDN . '/files/js/common.js');

/*** Recherche de la configuration ***/
$oCwConfig = new Cache(__SQL);
/* UNUSED Del 20 jan 2012
$mvc->config = $oCwConfig->getCache(); //*/


/*** G?n?ration de la page ***/
ob_start();
$mvc->router->loader();
$mvc->contenu = ob_get_clean();

if ( __LOADER == 'ajax'){
die($mvc->contenu);
}
/*** Fin de la g?n?ration, mise en cache **/



/********************************************************************
*				Design Zone											*
*																	*
********************************************************************/


// BEGIN BREADCRUMB
$route = (!isSet($_GET['module'])) ? '' : '?module='.$_GET['module'];
$breadcrumb='<ul class="breadcrumb">';
// Ligne de la racine du module
$breadcrumb.='<li><a href="' . url('index.php') . '" title="Page d\'accueil">Accueil</a> <span class="divider">/</span></li>
<li><a href="'.url('index.php' . $route).'" title="'.$mvc->router->controller_info['title'].'">'.trim($mvc->router->controller_info['title']).'</a> <span class="divider">/</span></li>';
	if (is_array($mvc->router->controller_info['breadcrumb']))
	{
		foreach ($mvc->router->controller_info['breadcrumb'] as $url => $titre )
		{
			$breadcrumb.='<li><a href="'.$url.'" title="'.$titre.'">'.$titre.'</a> <span class="divider">/</span></li>';
		}
	}
$breadcrumb.='<li>'.$mvc->router->controller_info['page_title'].'</li>';
//$tmp.='<li>&nbsp;&gt;&gt;&nbsp;</li><li id="thispage">' . $this->mvc->router->controller_info['page_title'] . '</li>';	
$breadcrumb.='</ul>';
// END BREADCRUMB


$content = $mvc->html->getContent();
// INT?GRATION DU SKINAGE
$skin = array(
	'ALERT_IE' => (is_ie()!=false && is_ie() < 7) ? '<table>
	<tr><td width="75%"><img src="' . __CW_PATH . '/files/images/Alert.gif" valign="middle" /><b>Saviez-vous que votre version d\'Internet Explorer est p&eacute;rim?e?
	Pour obtenir la meilleur exp&eacute;rience de navigation possible sur notre site web, nous vous recommandons de mettre &agrave; jour votre navigateur ou d\'en choisir un autre.
	Une liste des navigateurs les plus populaires se trouve ci-contre.
	Il suffit de cliquer sur l\'ic&ocirc;ne du navigateur correspondant pour se rendre &agrave; sa page de t&eacute;l&eacute;chargement.</b></pre>
	</td><td>
	<a href="http://www.microsoft.com/windows/Internet-explorer/default.aspx" target="_blank"><img src="' . __CW_PATH . '/files/images/browser/browser_ie_mini.gif"  width="50" height="50" title="Internet Explorer "/></a>
	<a href="http://www.mozilla.com/firefox/" target="_blank"><img src="' . __CW_PATH . '/files/images/browser/browser_firefox_mini.gif"  width="50" height="50" title="Firefox"/></a>
	<a href="http://www.apple.com/safari/download/" target="_blank"><img src="' . __CW_PATH . '/files/images/browser/browser_safari_mini.gif"  width="50" height="50" title="Safari"/></a>
	<a href="http://www.opera.com/download/" target="_blank"><img src="' . __CW_PATH . '/files/images/browser/browser_opera_mini.gif"  width="50" height="50" title="Opera"/></a>
	<a href="http://www.google.com/chrome" target="_blank"><img src="' . __CW_PATH . '/files/images/browser/browser_chrome_mini.gif"  width="50" height="50" title="Chrome"/></a>
	</tr>
	</table><br />' : NULL,
	// Class Keyword
	'USER_ID' => (isSet($_SESSION['user']['id'])) ? $_SESSION['user']['id'] : 0,
	'THEME_SERVER_DATE' => dates( time(),'fr_date'),
	'THEME_SERVER_TIME' => date('G:i', time()),
	'PSEUDO' => (isSet($_SESSION['user']['pseudo'])) ? $_SESSION['user']['pseudo'] : '',
	'THEME_CONTENT' => $content['main'].$mvc->contenu,
	'THEME_SITETITLE' => 'IYC.fr',
	'THEME_TITLE' => $mvc->router->controller_info['page_title'],
	'THEME_URI' => __PAGE,
	'THEME_LINKTO' => __CW_PATH,
	'THEME_CDN' => __CDN,
	'THEME_DESCRIPTION' => NULL,
	'THEME_KEYWORD' => NULL,
	'THEME_CATEGORY' => NULL,
	'THEME_LANGUAGE' => NULL,
	'THEME_HEAD' => $mvc->html->getHead(),
	'THEME_FIL' => $breadcrumb,
	//'THEME_ASIDE' => (in_array($mvc->router->controller, $this->admin_spc)) ? menu::build_tree($this->aside_spc) : $aside,
/*	'PLUGINS_CONTENT_HEADER' => $plugins_content_header,
	'PLUGINS_CONTENT_FOOTER' => $plugins_content_footer,*/
	'POWERED_BY' => $mvc->poweredBy
	);




ob_start();
if (preg_match('#Java#', $_SERVER['HTTP_USER_AGENT']) && !isSet($_GET['cw']))
{
require_once './themes/java/wrapper.phtml';
}
elseif(!isSet($_GET['cw']))
{
require_once './themes/boot/wrapper.phtml';
}
elseif(isSet($_GET['cw']))
{
require_once './themes/'.$_GET['cw'].'/wrapper.phtml';
}
echo ob_get_clean();
?>