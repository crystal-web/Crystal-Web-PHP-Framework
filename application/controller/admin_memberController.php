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


Class admin_memberController Extends baseController {

private $module=array(
	/* BOOL */
	'sitemap' => false,
	'title' => 'Administration', // Titre du module
	'page_title' => NULL, // Titre page courante
	'breadcrumb' => false, // breadcrumb hierarchy $url => $title
	'isAdmin' => true,
	'aside' => array (
		'Configuration' => array(
			'index.php?module=admin_config' => 'Config g&eacute;n&eacute;ral',
			),
		'Contact' => array(
			'index.php?module=admin_contact' => 'Config Contact',
			),
		'Membre' => array(
		'index.php?module=admin_member' => 'Gestion Membre',
			),
		'Plugin' => array(
			'index.php?module=admin_plugin' => 'Gestion des plugins',
			),
		'Backup' => array(
			'index.php?module=admin_backup' => 'Backup manager',
			),
		'Contrôle' => array(	
		'index.php?module=admin_md5' => 'Contrôle MD5',
			),
		'Info serveur' => array(	
		'index.php?module=admin_linfo' => 'Serveur Healt',	
			),
		),
	);

/*** Methode ***/

	public function getInfo(){
	return $this->module;
	}

	public function setInfo($name, $is){
	$this->module[$name]=$is;
	return $this->module;
	}

	public function index(){
		
		$thismember = NULL;
		if (isSet($_POST['l_member']))
		{
		$thismember = Login::searchMemberByLogin(preg_replace('#\*#', '%', $_POST['l_member']));
		}
		
		$error_add=array();
		if (isSet($_POST['add_member']))
		{
		$_POST['loginM'] = strtolower($_POST['loginM']);
		$_POST['mailM'] = strtolower($_POST['mailM']);
		$_POST['passM'] = strtolower($_POST['passM']);
		$_POST['passM2'] = strtolower($_POST['passM2']);
		
			if (Login::searchMemberByLogin($_POST['loginM'])!= false)
			{
			$error_add[]='Ce nom d\'utilisateur est déjà utilisé';
			}
			if (Login::searchMemberByMail($_POST['mailM'])!= false)
			{
			$error_add[]='Cette adresse e-mail est déjà utilisée.';
			}
			if (!filter_var($_POST['mailM'], FILTER_VALIDATE_EMAIL))
			{
			$error_add[] = 'Adresse e-mail est incorrect!';
			}	
			if ($_POST['passM'] != $_POST['passM2'])
			{
			$error_add[]='Les mot de passe sont différents';
			}
			if (strlen($_POST['passM']) < 6)
			{
			$error_add[] = 'Mot de passe est trop court (min 6 caract&egrave;res) !';
			}
			
			// Fin verification
		
			if (count($error_add) < 1)
			{
			
				if (Login::addMember($_POST['loginM'], $_POST['passM'], $_POST['mailM']) != false)
				{
				$this->mvc->html->setCodeScript('alert("Membre ajouté");');
				
				}
			
			}
		
		}
	
		
		$nbtotlalmember=Login::count();
		$membresParPage=30;
		
		//Nous allons maintenant compter le nombre de pages.
		$nombreDePages=ceil($nbtotlalmember/$membresParPage);
		

			if(isset($_GET['page'])) // Si la variable $_GET['page'] existe...
			{
			$pageActuelle=intval($_GET['page']);

			// Si la valeur de $pageActuelle (le numéro de la page) est plus grande que $nombreDePages...
				if($pageActuelle>$nombreDePages)
				{
				$pageActuelle=$nombreDePages;
				}
				
			}
			else // Sinon
			{
			// La page actuelle est la n°1 
			$pageActuelle=1;   
			}

		$start=($pageActuelle-1)*$membresParPage; // On calcul la première entrée à lire
	
	/* Liste ordonner */
	$order_by = (isSet($_GET['o'])) ? $_GET['o'] : NULL;

	switch ($order_by) {
	case 'member':
	$ordre = "loginmember";
	break;
	case 'mail':
	$ordre = "mailmember";
	break;	
	case 'firstactivity':
	$ordre = "firstactivitymember";
	break;		
	case 'lastactivity':
	$ordre = "lastactivitymember";
	break;
	default:
	$ordre = "idmember";
	break;
	}
	

	
	
	
	
	
	$sens = (isSet($_GET['i'])) ? 'DESC' : 'ASC';
	
	$this->mvc->template->i = ($sens == 'ASC') ? '&i' : '';
	$this->mvc->template->addMemberError = $error_add;
	$this->mvc->template->thismember = $thismember;
	$this->mvc->template->listmember = Login::listMember($start, $membresParPage, $ordre, $sens);
	$this->mvc->template->nombreDePages=$nombreDePages;
	$this->mvc->template->nbtotlalmember=$nbtotlalmember;
	
	$this->mvc->template->show('admin/member');
	}
}

?>
