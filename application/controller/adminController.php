<?php
/**
* @title Simple MVC systeme 
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nc-nd/3.0/
*/
if ($_SESSION['user']['power_level'] != 5)
{
header('Location: '.url('index.php?module=login&action=index'));
exit();
}

Class adminController Extends baseController {


private $module=array(
	/* BOOL */
	'sitemap' => false,
	'title' => 'Administration', // Titre du module
	'page_title' => 'Dashboard', // Titre page courante
	'breadcrumb' => false, // breadcrumb hierarchy $url => $title
	'isAdmin' => true,
	);
	
/*** Methode ***/

	public function getInfo(){
	
	return $this->module;
	}

	public function setInfo($name, $is){
	$this->module[$name]=$is;
	return $this->module;
	}
	


	// Listage de posts
	public function index() 
	{

	$this->setInfo('sitemap', false);
	$this->setInfo('page_title', 'Tableau de bord');
	
	$errors=NULL;
	
	
	/* Notepad - Bloc-Notes */
	if (isSet($_POST['notepad']) && $_SESSION['user']['power_level'] == 5)
	{
	$notepad = stripcslashes($_POST['notepad']);

	$cache_notepad = new Cache('notepad', $notepad);	
	if ($cache_notepad->setCache()==false){$errors[] = '<b>Bloc-Notes :</b> &Eacute;criture impossible';}

	}
	

	/* Flux RSS */
   if($chaine = @implode("",@file('http://feeds.feedburner.com/Crystal-webNews'))) {
      // on explode sur <item>
      $tmp = preg_split("/<\/?"."item".">/",$chaine);
	  $tmp3=NULL;
      // pour chaque <item>
      for($i=1;$i<sizeof($tmp)-1;$i+=2)
         // on lit les champs demand? <champ>
         foreach(array("title","link","description","pubDate",) as $champ) {
            $tmp2 = preg_split("/<\/?".$champ.">/",$tmp[$i]);
            // on ajoute au tableau
            $tmp3[$i-1][] = truncate(strip_tags(htmlspecialchars_decode(utf8_decode($tmp2[1]))), 200);
         }
		 	
      // et on retourne le tableau
      $rss = $tmp3;
   }

   
	/* Alert Erreur */
	$cache_alerte = new Cache('erreur_alerte');
	if (count($cache_alerte->getCache()) == 0){
	$erreur_alerte['string'] = "Aucune alerte";
	$erreur_alerte['bool'] = false;
	}
	else
	{
	$erreur_alerte['string'] = "Alerte : une ou plusieurs erreurs détectées";
	$erreur_alerte['bool'] = true;
	}

	
	
	/* Online, qui est la ? */
	$online = new Cache('online');
	$this->mvc->template->online = $online->getCache();
	
	
	
	$this->mvc->template->errors = $errors;
	$this->mvc->template->erreur_alerte = $erreur_alerte;
	$this->mvc->template->rss = $rss;
	
	$cache_notepad = new Cache('notepad');
	$this->mvc->template->notepad = $cache_notepad->getCache();
	$this->mvc->template->show('admin/dashboard');

	
	} // END index

	
	
	
	public function alerte(){
	$this->setInfo('sitemap', false);
	$this->setInfo('page_title', 'Alerte système');
	
	if (isSet($_POST['poke']))
	{
		if ($_POST['poke'] == $_SESSION['poke'])
		{
		$cache_alerte = new Cache('erreur_alerte');
		$cache_alerte->delCache();
		unset($cache_alerte);
		}
	}
	else
	{
	$_SESSION['poke']=randCar(50);
	}
	

	
	// Chargement des erreurs 
	$cache_alerte = new Cache('erreur_alerte');

	// Pas d'alerte
	if (count($cache_alerte->getCache()) == 0){
	$this->mvc->template->show('admin/alerte_notexist');	
	}
	// Erreur a lister 
	else
	{
	
		if (isSet($_POST['bugtracker']))
		{
		
		
ob_start();
echo '<table width="100%"><tr><th colspan="2">Erreurs archiv&eacute;es</th></tr><tr><td>Description</td><td>Date</td></tr>';	
foreach ($cache_alerte->getCache() AS $date => $data)
{
echo '<tr><td><b>Type : </b>' . $data['type'] . ' ' . $data['msg'].'<br /><b>Ligne : </b>' . $data['errline'] . ' ' . $data['errfile'].'<br />
<div style="width: 600px;height: 200px;border: 1px solid #CCC;background: #F2F2F2;padding: 6px;overflow: auto;">
<table width="100%"><tr><td><textarea rows="30" cols="60">' . $data['more'] . '
</textarea></td></tr></table></div></td><td><pre style="border: 1px solid #000; height: 9em; overflow: auto; margin: 0.5em;">' . date("d/m/Y H:i:s",$date) . '</pre></td></tr>';
}
echo '</table>';	
$repport=ob_get_contents();
ob_end_clean();

		
		$mail_send = new Mail('Report Bug form <'.$_SERVER['SERVER_NAME'].'>',$repport,'developpeur@crystal-web.org', ADMIN_MAIL);
		$mail_send->sendMailHtml();
		$this->mvc->html->setCodeScript("alert('Mail send succes');");
		}
	
	$this->mvc->template->alerte = $cache_alerte->getCache();
	$this->mvc->template->show('admin/alerte_exist');	
	

	}

}	
}

?>
