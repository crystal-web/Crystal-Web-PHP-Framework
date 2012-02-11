<?php
class Plugin{

private $oPlugin;
	public function __construct(Cache $cachePlugin)
	{
	$this->oPlugin = $cachePlugin;
	}
	
	public function load()
	{
		if ($this->get()!=false)
		{
			/* Chargement des plugins */
			foreach($this->get() as $plug => $info)
			{
				// Vérifie que le plugin est activer
				if ($info['activer']==true)
				{
					// On vérifie qu'il est compatible avec la version installé
					if (__VER >= $info['compatibility'] && file_exists('plugins/' . $plug . '/' . $info['include']))
					{
						require 'plugins/' . $plug . '/' . $info['include'];
					}
				}
			}
		}
		else
		{
		$this->add('autoconnect',
						1.2,
						'Christophe BUFFET',
						'http://www.crystal-web.org',
						'Gestionnaire de connection par cookie',
						false,
						true,
						true,
						__VER,
						'autoconnect.php',
						true);
		$this->load();
		}
	}
	
	
	private function cleaner($string)
	{
	$string = strtr($string, 
	'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
	'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
	return preg_replace('/([^\.a-z0-9]+)/i', '-', $string);
	}
	
	public function get()
	{
	return $this->oPlugin->getCache();
	}
	
	public function add($name,
						$version,
						$author,
						$website,
						$description,
						$useCache /* bool */,
						$useSgbd /* bool */,
						$useCookie /* bool */,
						$compatibility,
						$include,
						$active = false)
	{
	$arrCache = $this->oPlugin->getCache();
	$arrCache[$this->cleaner($name)] = array(
		'name' => $name,
		'version' => $version,
		'author' => $author,
		'website' => $website,
		'description' => $description,
		'cache' => $useCache,
		'sgbd' => $useSgbd,
		'cookie' => $useCookie,
		'activer' => $active,
		'compatibility' => $compatibility,
		'include' => $include
		);
	$this->oPlugin->setCache($arrCache);
	}
}
