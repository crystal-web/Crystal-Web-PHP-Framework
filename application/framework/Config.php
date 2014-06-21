<?php
/**
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/
if (!defined('__APP_PATH'))
{
	echo '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You don\'t have permission to access this file on this server.</p></body></html>'; die;
}

Class Config {
	private $cache;
	private $language;
	
	
private $siteTitle = NULL;
private $siteSlogan = NULL;

	
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

	public function __construct() {
		// Génére un fichier de configuration par defaut
		$config = new stdClass();
		// HTTP or HTTPS ??
		$http = (isSet($_SERVER['HTTPS'])) ? 'https' : 'http';
		
		$config->siteTitle = 'Crystal-Web CMF';
		$config->siteSlogan = 'Et si notre partage faisait l\'&eacute;volution ?';
		$config->siteUrl = $http . '://'.$_SERVER['SERVER_NAME'];
		$config->siteTeamName = 'Team Crystal-Web';

		$config->mailSite = 'noreply@'.$_SERVER['SERVER_NAME'];
		$config->mailContact = 'webmaster@'.$_SERVER['SERVER_NAME'];
		$config->layout = 'default';
		
		try {
		// Recherche la configuration, si elle n'existe pas, on prend celle généré
		$oConfig = new Cache ( 'config', $config);
		} catch(Exception $e) {
			die($e->getMessage());
		}		
		$this->cache = $oConfig->getCache();
	}
	
	public function saveChange() {
		try {
		// Recherche la configuration, si elle n'existe pas, on prend celle généré
		$oConfig = new Cache ( 'config');
		$oConfig->setCache($this->cache);
		} catch(Exception $e) {
			die($e->getMessage());
		}
	}

	/**
	 * @deprecated Use saveChange
	 */
	public function setConfig($config) {
		$oConfig = new Cache ( 'config');
		$oConfig->setCache($config);
		$this->cache = $config;
	}
	
	public function getConfig() {
		if (is_null($this->cache)) {
			$this->__construct();
		}
		return $this->cache;
	}
	
/***********************************************************************************************************/
	public function getDefaultLanguage() {
		return 'fr';
	}
	
	public function setCurrentLanguage($lng) {
		$this->language = $lng;
		return $this;
	}
	
	public function getCurrentLanguage() {
		return isset($this->language) ? $this->language : $this->getDefaultLanguage();
	}
/***********************************************************************************************************/
	
	 
	/**
	 * 
	 * Retourne le layout du site
	 */
	public function getLayout() {
		return isset($this->cache->layout) ? $this->cache->layout : 'default';
	}
	
	/**
	* Definis le layout du site
	*
	* @param $layout
	*/
	public function setLayout($layout) {
		if (file_exists(__APP_PATH.DS.'layout'.DS.$layout.'.phtml')) {
			$this->cache->layout = $layout;
            Log::setLog('Layout changez pour ' . $this->cache->layout, get_class($this));
			return $this;
		}
		throw new Exception("Layout not found", 1);
	}
	
	
	/**
	* Definis le titre du site
	*
	* @param $title|le titre
	*/
	public function setSiteTitle($title) {
		$this->cache->siteTitle = $title;
		return $this;
	}
	
	/**
	* Renvois le titre du site
	*
	* @return string $title|le titre
	*/
	public function getSiteTitle() {
		return isset($this->cache->siteTitle) ? $this->cache->siteTitle : 'Crystal-Web System';
	}

	/**
	* Definis le slogan du site
	*
	* @param string $slogan
	*/
	public function setSiteSlogan($slogan) {
		$this->cache->siteSlogan = $slogan;
		return $this;
	}
	
	/**
	* Renvois le slogan du site
	*
	* @return string $slogan
	*/
	public function getSiteSlogan() {
		return isset($this->cache->siteSlogan) ? $this->cache->siteSlogan : 'Et si notre partage faisait l\'&eacute;volution ?';
	}
	

	public function getSiteUrl() {
		return isset($this->cache->siteUrl) ? $this->cache->siteUrl : __CW_PATH;
	}
	
	public function setSiteUrl($url) {
		$this->cache->siteUrl = $url;
		return $this;
	}
	
	public function getSiteMail() {
		return isset($this->cache->mailSite) ? $this->cache->mailSite : 'no@mail.com';
	}
	
	public function setSiteMail($mail) {
		$this->cache->mailSite = $mail;
		return $this;
	}
	
	public function getSiteMailContact() {
		return isset($this->cache->mailContact) ? $this->cache->mailContact : 'webmaster@'.$_SERVER['SERVER_NAME'];
	}
	
	public function setSiteMailContact($mail) {
		$this->cache->mailContact = $mail;
		return $this;
	}
	
	public function getSiteTeam() {
		return isset($this->cache->siteTeamName) ? $this->cache->siteTeamName : 'Team Crystal-Web';
	}
	
	public function setSiteTeam($teamname) {
		$this->cache->siteTeamName = $teamname;
		return $this;
	}
	
	public function __get($index) {
		/* Est logique ? */
		$index = clean($index, 'str');
		return isSet($this->cache->$index) ? $this->cache->$index : false;
	}
}