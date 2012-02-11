<?php
session_start();
if (preg_match('#MSIE#', $_SERVER["HTTP_USER_AGENT"])){header('Cache-Control: no-cache');}

/*** Doit etre false en production ***/
define ('__DEV_MODE', false);

/*** define the site path  ***/
define ('__SITE_PATH', realpath(dirname(__FILE__)));

/*** define loader type ***/
define ('__LOADER', 'browser');

/*** include the init.php file ***/
include __SITE_PATH . '/includes/init.php';
$temps = getmicrotime(); //temps au debut du chargemennt

/*** Dev mode is enabled ? ***/
$err = (__DEV_MODE===true) ? error_reporting(-1) : error_reporting(0);









	if (__DEV_MODE)
	{
	/*  Problematique
	echo '<div style="margin:7px;padding:5px;"><fieldset style="border:1px solid #0000FF;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee">
	<legend style="color:#0000FF;">&nbsp;&nbsp;ENTETE&nbsp;&nbsp;&nbsp;</legend>';
		if($EnteteHTTP = @get_headers(__CW_PATH))
		{
			foreach($EnteteHTTP as $Valeur) echo $Valeur."<br/>";
		}
	echo '</fieldset>';//*/
	$get = (count($_GET)) ? '<pre>'.print_r($_GET, true).'</pre>' : 'Aucune donn&eacute;e GET';
	$post = (count($_POST)) ? '<pre>'.print_r($_POST, true).'</pre>' : 'Aucune donn&eacute;e POST';
	$session = (count($_SESSION)) ? '<pre>'.print_r($_SESSION, true).'</pre>' : 'Aucune donn&eacute;e SESSION';
	$cookie = (count($_COOKIE)) ? '<pre>'.print_r($_COOKIE, true).'</pre>' : 'Aucune donn&eacute;e SESSION';
	if (count($mvc->template->getVars()))
	{
	$envTmp=array();
		foreach($mvc->template->getVars() AS $envK => $null)
			$envTmp[$envK] = '$'.$envK;
			
	$env = '<pre>'.print_r($envTmp, true).'</pre>';
	}
	else
	{
	$env = 'Aucune donn&eacute;e ENVIRONEMENT';
	}
	
	$poidSysteme = count( (array) $mvc, COUNT_RECURSIVE);
	echo '<fieldset style="border:1px solid #021EB4;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee">
	<legend style="color:#021EB4;">&nbsp;&nbsp;DONNEES ENVIRONEMENT&nbsp;&nbsp;</legend>
	<div style="color:#021EB4;font-weight:normal;padding:4px 0 4px 0">' . $env . '</div></fieldset>

	<fieldset style="border:1px solid #021EB4;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee">
	<legend style="color:#021EB4;">&nbsp;&nbsp;DONNEES GET&nbsp;&nbsp;</legend>
	<div style="color:#021EB4;font-weight:normal;padding:4px 0 4px 0">' . $get . '</div></fieldset>

	<fieldset style="border:1px solid #009900;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee">
	<legend style="color:#009900;">&nbsp;&nbsp;DONNEES POST&nbsp;&nbsp;</legend>
	<div style="color:#009900;font-weight:normal;padding:4px 0 4px 0">' . $post . '</div></fieldset>


	<fieldset style="border:1px solid #CC00B4;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee">
	<legend style="color:#CC00B4;">&nbsp;&nbsp;DONNEES SESSION&nbsp;&nbsp;</legend>
	<div style="color:#CC00B4;font-weight:normal;padding:4px 0 4px 0">' . $session . '</div></fieldset>

	<fieldset style="border:1px solid #A73500;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee">
	<legend style="color:#A73500;">&nbsp;&nbsp;DONNEES COOKIE&nbsp;&nbsp;</legend>
	<div style="color:#A73500;font-weight:normal;padding:4px 0 4px 0">' . $cookie . '</div></fieldset>

	<fieldset style="border:1px solid #0000FF;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee">
	<legend style="color:#0000FF;">&nbsp;&nbsp;BASE DE DONNEES&nbsp;&nbsp;&nbsp;</legend>
	<div style="color:#0000FF;font-weight:normal;padding:4px 0 4px 0">
	table  ' . __SQL .'<br />
	</div></fieldset>

	<fieldset style="border:1px solid #0000FF;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee">
	<legend style="color:#0000FF;">&nbsp;&nbsp;BASE DE DONNEES&nbsp;&nbsp;&nbsp;</legend>
	<div style="color:#0000FF;font-weight:normal;padding:4px 0 4px 0">
	Co&ucirc;t systeme ' . $poidSysteme .'<br />
	</div></fieldset>
	</div>';


	}
?>