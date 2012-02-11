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

class admin_pluginController Extends baseController {

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

	public function index()
	{
	
	$this->setInfo('sitemap', false);
	$this->setInfo('page_title', 'Gestion des plugins');
	
	$plugin = new Cache(__SQL);
	$cache = $plugin->getCache();
	
	/* Activer ou désactiver */
	if (isset($_POST['ref'])){
	// Récupere le cache

	// Recherche la reference a changer et ça valeur
	$switch_to=($_POST[$_POST['ref']]==0) ? false : true;
	// Change la valeur
	$cache['plugin'][$_POST['ref']]['activer']=$switch_to;
	// Enregistre
	$plugin->setCache($cache);
	}
	
	if (isset($_POST['refd']))
	{
	unset($cache['plugin'][$_POST['refd']]);
	
	$plugin->setCache($cache);
	}
	
	$this->mvc->template->plugin = $cache['plugin'];
	$this->mvc->template->show('admin/plugin');
	
	}

	
	/*
	*	Ajout d'un plugin
	*/
	public function add(){
	$this->setInfo('sitemap', false);
	$this->setInfo('page_title', 'Ajout d\'un plugin');
	$erreur = array();
	if (isSet($_FILES['upload_plugin']['name']))
	{
	$SafeFile = $_FILES['upload_plugin']['name'];
	$extension=strrchr($SafeFile,'.');
	$extension=strtolower(substr($extension,1));
		if($extension == 'zip')
		{

			// Rappel Caractere de Regex doivent être précedé par \ : # ! ^ $ ( ) [ ] { } ? + * . \ | 
			// Clé a remplacer
			$patterns = array('/\#/',
				'/\$/',
				'/%/',
				'/\^/',
				'/&/',
				'/\*/',
				'/\?/'
				);
			$SafeFile = preg_replace($patterns, '', $SafeFile);
			$SafeFile = urlencode($SafeFile);
			$uploaddir = "./files/tmp/";
			$path = $uploaddir.$SafeFile;
			

			if(copy($_FILES['upload_plugin']['tmp_name'], $path))
			{

			$theFileName = $_FILES['upload_plugin']['name']; 

			$theFileSize = $_FILES['upload_plugin']['size']; 

				if ($theFileSize>999999)
				{
					$theDiv = $theFileSize / 1000000; 
					$theFileSize = round($theDiv, 1)." MB"; 
				}
				else
				{
					$theDiv = $theFileSize / 1000; 
					$theFileSize = round($theDiv, 1)." KB"; 
				}
			
			$zip = new Zip;
			$zip->unzip($path,'./plugins/') or die($zip->error());

			$SafeFile=strtolower(preg_replace('/.zip/', '', $SafeFile));

				if (file_exists('./plugins/'.$SafeFile.'/install.ini'))
				{
				$ini_array = parse_ini_file('./plugins/'.$SafeFile.'/install.ini');
					$cache_plugin = new Cache(__SQL);
					$cacheConf = $cache_plugin->getCache();
					$plugin_arr = $cacheConf['plugin'];
					
					if (!isSet($ini_array['name']))
					{
						$erreur[] = 'Le plugin n\'a pas de nom';
					} else { $plugin_arr[$SafeFile]['name'] = htmlentities($ini_array['name']); }
					
					if (!isSet($ini_array['version']))
					{
						$erreur[] = 'Le plugin n\'a pas de version';
					} else { $plugin_arr[$SafeFile]['version'] = htmlentities($ini_array['version']); }
					
					if (!isSet($ini_array['author']))
					{
						$erreur[] = 'Le plugin n\'a pas d\'auteur';
					} else { $plugin_arr[$SafeFile]['author'] = htmlentities($ini_array['author']); }
					
					if (!isSet($ini_array['website']))
					{
						$erreur[] = 'Le plugin n\'a pas site web';
					}  else { $plugin_arr[$SafeFile]['website'] = htmlentities($ini_array['website']); }		
					
					if (!isSet($ini_array['description']))
					{
						$erreur[] = 'Le plugin n\'a pas description';
					}  else { $plugin_arr[$SafeFile]['description'] = htmlentities($ini_array['description']); }			
					
					if (!isSet($ini_array['cache']))
					{
						$erreur[] = 'Le plugin n\'a pas indiqu&eacute; l\'utilisation du cache';
					} else { $plugin_arr[$SafeFile]['cache'] = htmlentities($ini_array['cache']); }
					
					if (!isSet($ini_array['sgbd']))
					{
						$erreur[] = 'Le plugin n\'a pas indiqu&eacute; l\'utilisation de la base de donn&eacute;e';
					} else { $plugin_arr[$SafeFile]['sgbd'] = htmlentities($ini_array['sgbd']); }
					
					if (!isSet($ini_array['cookie']))
					{
						$erreur[] = 'Le plugin n\'a pas indiqu&eacute; l\'utilisation des cookie';
					} else { $plugin_arr[$SafeFile]['cookie'] = htmlentities($ini_array['cookie']); }
					
					if (!isSet($ini_array['include']))
					{
						$erreur[] = 'Le plugin n\'a pas indiqu&eacute; le fichier a charger';
					} else { $plugin_arr[$SafeFile]['include'] = htmlentities($ini_array['include']); }
					
					if (!isSet($ini_array['compatibility']))
					{
						$erreur[] = 'Le plugin n\'a pas indiqu&eacute; la compatibilit&eacute;';
					} else { $plugin_arr[$SafeFile]['compatibility'] = htmlentities($ini_array['compatibility']); }
					
					if (!isSet($ini_array['admin']))
					{
						$erreur[] = 'Le plugin n\'a pas indiqu&eacute; de fichier d\'administration';
					} else { $plugin_arr[$SafeFile]['admin'] = htmlentities($ini_array['admin']); }
					
				if (!count($erreur))
				{
					$plugin_arr[$SafeFile]['activer'] = false;
					$cacheConf['plugin'] = $plugin_arr;
					$cache_plugin->setCache($cacheConf);
					//@unlink('./plugins/'.$SafeFile.'/install.ini');
					
					$this->mvc->template->msg = 'Installation termin&eacute;<br />'.$zip->file;
					$this->mvc->template->show('admin/addPlugin');
				}
				else
				{
					if (is_dir('./plugins/'.$SafeFile.'/')) @rmdir_recursive('./plugins/'.$SafeFile.'/');
					// Pas de fichier ini
					$this->mvc->template->msg = 'Erreur avec l\'installeur.'.$SafeFile;
					$this->mvc->template->erreur=$erreur;
					$this->mvc->template->show('admin/addPlugin');
				}
				
				
				}
				else
				{
				if (is_dir('./plugins/'.$SafeFile.'/')) @rmdir_recursive('./plugins/'.$SafeFile.'/');
				// Pas de fichier ini
				$this->mvc->template->msg = 'Erreur Il n\'y a pas d\'installeur.';
				$this->mvc->template->show('admin/addPlugin');
				}
			}
			unlink($uploaddir.$SafeFile.".zip");
		}
		else
		{
		$this->mvc->template->msg = 'Erreur extention incorrect (doit être ZIP)';
		$this->mvc->template->show('admin/addPlugin');
		// Erreur extention incorrect
		}

	}
	else
	{
	$this->mvc->template->show('admin/addPlugin');
	}

	}
	
	
	/*
	*	Adminstration d'un plugin
	*/
	public function admin() 
	{
		if ( isSet($_GET['p']) && !empty($_GET['p']) )
		{
		$plugin = $this->mvc->element->getPlugin();
		//debug($plugin);		
			if (isSet( $plugin[ $_GET['p'] ] ) )
			{
			
				$extension=strrchr($plugin[ $_GET['p'] ]['admin'],'.');
				$extension=strtolower(substr($extension,1));

				if ($extension = 'php')
				{
				$this->setInfo('page_title', 'Gestion du plugin ' . $plugin[ $_GET['p'] ]['name']);
				

				$activer = ($plugin[ $_GET['p'] ]['activer']) ? 'enabled' : 'disabled';
				$this->mvc->html->setContent(info('<p>
					'.$plugin[ $_GET['p'] ]['description'].'<br />
				<b>Statut du plugin:</b> '.$activer.'
				</p>'));				
				
				
				require_once './plugins/'.$_GET['p'].'/'.$plugin[ $_GET['p'] ]['admin'];
				}
				else
				{
				alerte('Le plugin ne dispose pas d\'une administration', true);
				}
			}
			else
			{
			alerte('Plugin introuvable', true);
			}
		}
		else
		{
		return $this->index();
		}
	
	}
}


?>