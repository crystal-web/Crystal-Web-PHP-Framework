<?php
/**
* @title Connection
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description index du site, sorry j'avais pas d'idée
*/

Class indexController extends Controller{


	public function index()
	{
		$this->mvc->Page->setPageTitle('Hello world !!');
		$this->mvc->Template->bonjour = 'Bonjour ami développeur';
		$this->mvc->Template->paragraphe = 'Vous voici enfin sur la page d\'accueil de votre futur site ^^<br>
			Je me suis permis de vous laissez quelques sources comme démonstration. Mais avant de commencer à réaliser votre projet, qui j\'en suis persuadé en vaut la peine (surtout si vous le faite par vous-même).<br>
			Il vous faut, modifier le fichier <strong>/public_html/includes/init.php</strong> qui contient les paramettres de connexion à la base de donnée.<br>
			D\'ici peu, vous aurez une documentation en ligne. J\'y travail afin que cela soit le plus documenté possible et le plus agréable possible à lire (cette page n\'est pas vraiment un bonne exemple xD)
			';
		$this->mvc->Template->show('index/index');

	}

	
	public function log()
	{
	$log = new Cache('log');
	$data = $log->getCache();
	debug($data);
	}


						
}
