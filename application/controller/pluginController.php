<?php
/**
* @title Simple MVC systeme 
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nc-nd/3.0/
*/
class pluginController Extends Controller {

public function index()
{
if (!$this->mvc->Acl->isAllowed()) {Router::redirect();}

$this->mvc->Page->setPageTitle('Plugin manager')
				->setBreadcrumb('plugin','Plugin');
				
$pluginList = $this->mvc->Plugin->getList();
$pluginListNew = array();
$scanner = opendir(__APP_PATH . DS . 'plugin');
$slug = (isSet($this->mvc->Request->params['slug'])) ? $this->mvc->Request->params['slug'] : '';
$stat = (isSet($this->mvc->Request->params['stat'])) ? $this->mvc->Request->params['stat'] : false;

    while (($dir = readdir($scanner)) !== false)
	{
		if ($dir != '.' && $dir != '..')
		{
		$iniFile = __APP_PATH . DS . 'plugin'  . DS . $dir . DS . 'plugin.ini';

			if (!isSet($pluginList[$dir]))
			{
			$pluginListNew[$dir] = array(
				'enable' => false,
				'info' => parse_ini_file($iniFile)
				);
			}
			else
			{
			$pluginListNew[$dir] = array(
				'enable' => $pluginList[$dir]['enable'],
				'info' => parse_ini_file($iniFile)
				);
			}
			
			foreach($pluginListNew[$dir]['info'] as $k=>$v){
			
				if (get_magic_quotes_gpc()) {
				$pluginListNew[$dir]['info'][$k] = htmlentities($v, ENT_NOQUOTES, 'utf-8');
				}
				else {
				$pluginListNew[$dir]['info'][$k] = htmlentities(addslashes($v), ENT_NOQUOTES, 'utf-8');
				}			
				
			}
		}
    }
    closedir($scanner);
	$this->mvc->Plugin->setList($pluginListNew);
	
	
	if (!empty($slug) && isSet($pluginList[$slug]))
	{
	$pluginListNew[$slug]['enable'] = ($stat == '1') ? true : false;
	$this->mvc->Plugin->setList($pluginListNew);
	Router::redirect('plugin');
	//debug($pluginList);
	}
	//debug($pluginList);
	
	$this->mvc->Template->pluginList = $pluginListNew;
	$this->mvc->Template->show('plugin/index');
}



public function indexUp()
{

if ($this->mvc->Acl->isAllowed())
{
$this->mvc->Page->setPageTitle('Plugin manager')
				->setBreadcrumb('plugin','Plugin');

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
			$erreur = array();
				if (file_exists('./plugins/'.$SafeFile.'/install.ini'))
				{
				$ini_array = parse_ini_file('./plugins/'.$SafeFile.'/install.ini');
					$cache_plugin = new Cache(__SQL);
					$cacheConf = $cache_plugin->getCache();
					$plugin_arr = isset($cacheConf['plugin']) ? $cacheConf['plugin'] : array();
					
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
					
					$this->mvc->Session->setFlash('Installation termin&eacute;<br />'.$zip->file);
				}
				else
				{
					if (is_dir('./plugins/'.$SafeFile.'/')) @rmdir_recursive('./plugins/'.$SafeFile.'/');
					// Pas de fichier ini
					$this->mvc->Session->setFlash('Erreur avec l\'installeur.'.$SafeFile, 'error');
					$this->mvc->Template->erreur=$erreur;
				}
				
				
				}
				else
				{
				if (is_dir('./plugins/'.$SafeFile.'/')) @rmdir_recursive('./plugins/'.$SafeFile.'/');
				// Pas de fichier ini
				$this->mvc->Session->setFlash('Erreur Il n\'y a pas d\'installeur.', 'error');
				}
			}
			unlink($uploaddir.$SafeFile.".zip");
		}
	}


	$plugin = new Cache(__SQL);
	$cache = $plugin->getCache();
	
	/* Activer ou desactiver */
	if (isset($_POST['ref']))
	{
	// Recherche la reference a changer et ca valeur
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
	
	$this->mvc->Template->plugin = isset($cache['plugin']) ? $cache['plugin'] : array();
	$this->mvc->Template->show('plugin/plugin');
}
else
{
$this->mvc->Session->setFlash('Vous n\'avez pas le droit d\'accès à cette page', 'error');
}

}

	
	
	
/*
*	Adminstration d'un plugin
*/
public function manager() 
{

if ($this->mvc->Acl->isAllowed())
{
		
	if ( isSet($this->mvc->Request->params['slug']) && !empty($this->mvc->Request->params['slug']) )
	{
	$mePlugin = $this->mvc->Request->params['slug'];
	$plugin = $this->mvc->Plugin->getList();


//		debug($plugin);	
		if (isSet( $plugin[ $mePlugin ] ) )
		{
			if (!$plugin[ $mePlugin ]['enable'])
			{
			$this->mvc->Session->setFlash('Le plugin n\'est pas activé','error');
			Router::redirect('plugin');
			}
			
		$pluginNamed = (isSet($plugin[ $mePlugin ]['info']['name'])) ? clean($plugin[ $mePlugin ]['info']['name'], 'str') : $mePlugin;
		$plugin[ $mePlugin ]['info']['description'] = (isSet($plugin[ $mePlugin ]['info']['description'])) ? clean($plugin[ $mePlugin ]['info']['description'], 'str') : '';
		$this->mvc->Page->setPageTitle($pluginNamed . ' manager')
				->setBreadcrumb('plugin','Plugin');


			if (isSet($plugin[ $mePlugin ]['info']['setting']))
			{
			
			ob_start();
					$this->mvc->Plugin->triggerEvents(clean($plugin[ $mePlugin ]['info']['setting'], 'str'));
				// Enregistre le contenu du tampon de sortie
				$buffer = ob_get_contents();

			// Efface le contenu du tampon de sortie
			ob_clean();
			

				if (!empty($buffer))
				{
				$this->mvc->Template->plugin = $plugin[ $mePlugin ];
				$this->mvc->Template->html = $buffer;
				$this->mvc->Template->show('plugin/manager');
				} else {$this->mvc->Session->setFlash('Method '. clean($plugin[ $mePlugin ]['info']['setting'], 'str') . ' not exist');}
				
			}
			else
			{
			$this->mvc->Session->setFlash('Le plugin ne dispose pas d\'une administration', 'error');
			Router::redirect('plugin');
			}
		}
		else
		{
		$this->mvc->Session->setFlash('Plugin introuvable', 'error');
		Router::redirect('plugin');
		}
	}
	else
	{
	$this->mvc->Session->setFlash('Plugin introuvable', 'error');
	Router::redirect('plugin');
	}
}
else
{
$this->mvc->Session->setFlash('Vous n\'avez pas le droit d\'accès à cette page', 'error');
}

}
}


?>