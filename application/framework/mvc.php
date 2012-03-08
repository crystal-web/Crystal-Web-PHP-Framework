<?php
/**
* @title Simple MVC systeme - Registre
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/

Class mvc{

/*** Variables ***/
public $vars = array();
/*** Methodes ***/

	public function __construct()
	{
	header("X-Powered-By: Crystal-Web.org");
	$this->poweredBy = '<div id="shadowing"></div>
<div id="box">
	<div id="boxheader">
	Crystal-web.org : Conception et r&eacute;alisation de site web
	<span id="boxclose" onclick="document.getElementById(\'box\').style.display=\'none\';
	document.getElementById(\'shadowing\').style.display=\'none\'"> </span>
	</div>

<div id="boxcontent">
	<img src="http://dyraz.com/~dyraz/dyraz_new_design/images/logo-crystal.gif"  style="float: left; width: 171px; height: 81px;top:10px; " />
	
	<p id="devCW">
	<b>Auteurs :</b> <a href="http://www.crystal-web.org" title="Crystal-Web Team">Crystal-Web Team</a><br />
	<b>Site web :</b> <a href="http://www.crystal-web.org" title="Crystal-Web Solution">www.crystal-web.org</a><br />
	<b>Version :</b> ' . __VER . '<br />
	
	</p><h1>A propos de...</h1>

	
	
	<p class="retCW">
	Crystal-Web CMS &agrave; &eacute;t&eacute; cod&eacute; pour faciliter la vie des utilisateurs et des d&eacute;veloppeurs. Il est l&eacute;g&eacute;, rapide, s&ucirc;re et surtout efficace. Pas besoin de t&eacute;l&eacute;charger quoi que se soit, Crystal-Web.org se charge de tout, il s\'<acronym title="Dans le cadre d\'une installation depuis nos serveurs et non d\'un t&eacute;l&eacute;chargement">installe, &eacute;volue et se met &agrave; jour automatiquement.</acronym></p>

	<p class="retCW">Non seulement Crystal-web CMS utilise une composante surpuissante, mais en plus il est d&eacute;velopp&eacute; pour &ecirc;tre facilement adaptable &agrave; n\'importe quel utilisation.</p>
	<p class="retCW">Le projet est mature... Toutefois il reste encore quelques bugs et des fonctionnalit&eacute;s manquantes. Si vous avez des questions ou des conseils en rapport avec ce projet, ou bien vous voulez faire des demandes d\'ajout de fonctionnalit&eacute;s, vous pouvez y participer dans les forums.</p>

	<p class="retCW">Assez parl&eacute;, je vous laisse juge de Crystal-Web CMS.</p>
	
	


<p style="font-weight:bold;text-align:center;">Copyright &copy; 2008 - ' . date('Y') . ' Crystal-Web.org. Tous droits r&eacute;s&eacute;rv&eacute;s</p>
</div>

</div>

<a data-placement="above" data-original-title="A propos de Crystal-web.org" data-content="Conception et r&eacute;alisation de site web" rel="popover" href="#" onclick="document.getElementById(\'shadowing\').style.display=\'block\';document.getElementById(\'box\').style.display=\'block\';">Powered by Crystal-Web Solution</a>';
	}

	
	public function __set($index, $value)
	{
	$this->vars[$index] = $value;
	}

	public function __get($index)
	{
	return $this->vars[$index];
	}

}


?>
