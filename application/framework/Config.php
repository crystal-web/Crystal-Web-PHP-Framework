<?php 
Class Config 
{
	
	private $cache;
	private $language;
	
	/**
	* @var Singleton
	* @access private
	* @static
	*/
	private static $_instance = null;
 
 
	/**
	* Méthode qui crée l'unique instance de la classe
	* si elle n'existe pas encore puis la retourne.
	*
	* @param void
	* @return Singleton
	*/
	public static function getInstance() {
		if(is_null(self::$_instance)) {
			self::$_instance = new Config();
		}
 
	return self::$_instance;
	}


	public function __construct()
	{
		// Génére un fichier de configuration par defaut
		$config = new stdClass();
		$config->siteName = 'Crystal-Web CMF';
		$config->siteSlogan = 'Et si notre partage faisait l\'&eacute;volution ?';
		// HTTP or HTTPS ??
		$http = (isSet($_SERVER['HTTPS'])) ? 'https' : 'http';
		$config->siteUrl = $http . '://'.$_SERVER['SERVER_NAME'];
		$config->siteTeamName = 'Team Crystal-Web';
		$config->mailSite = 'noreply@'.$_SERVER['SERVER_NAME'];
		$config->mailContact = 'webmaster@'.$_SERVER['SERVER_NAME'];
		$config->layout = 'default';
		try
		{
		// Recherche la configuration, si elle n'existe pas, on prend celle généré
		$oConfig = new Cache ( 'config', $config);
		} catch(Exception $e) {
			die($e->getMessage());
		}		
		$this->cache = $oConfig->getCache();
	}

	public function setConfig($config)
	{
		$oConfig = new Cache ( 'config');
		$oConfig->setCache($config);
		$this->cache = $config;
	}
	
	public function getConfig()
	{
		if (is_null($this->cache))
		{
			$this->__construct();
		}
		return $this->cache;
	}
	
	public function getDefaultLanguage()
	{
		return isSet($this->cache->language) ? $this->cache->language : 'fr';
	}
	
	public function setCurrentLanguage($lng)
	{
		$this->language = $lng;
	}
	
	public function getCurrentLanguage()
	{
		return $this->language;
	}
	
	public function getSiteUrl()
	{
		return $this->siteUrl;
	}
	
	public function getSiteMail()
	{
		return $this->cache->mailSite;
	}
	public function getSiteMailContact()
	{
		return $this->cache->mailContact;
	}
	public function getSiteTeam()
	{
		return $this->cache->siteTeamName;
	}	
	public function __get($index)
	{
		/* Est logique ? */
		$index = clean($index, 'str');
		return isSet($this->cache->$index) ? $this->cache->$index : false;
	}

}
