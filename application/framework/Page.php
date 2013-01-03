<?php
/*##################################################
 *                                 Page.php
 *                            -------------------
 *   begin                : 2012-03-08
 *   copyright            : (C) 2012 DevPHP
 *   email                : developpeur@crystal-web.org
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/
class Page extends Config{

private $siteTitle = NULL;
private $siteSlogan = NULL;
private $pageTitle = NULL;
private $menu = array();
private $head;
private $breadcrumb;
private $body;
private $layout  = 'default';

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
			self::$_instance = new Page();  
		}
		return self::$_instance;
	}
	
	public static function setInstance($instance) {
		self::$_instance = $instance;
	}
	
	public function Page()
	{
		$config = $this->getConfig();
		$this->setLayout($config->layout);
		$this->setSiteTitle($config->siteName);
		$this->setSiteSlogan($config->siteSlogan);
	}
	
	/**
	* Definis le layout du site
	*
	* @param $layout
	*/
	public function setLayout($layout)
	{
		if (file_exists(__APP_PATH.DS.'layout'.DS.$layout.'.phtml'))
		{
			$this->layout = $layout;
			return true;
		}
		return false;
	}
	
	
	/**
	 * 
	 * Retourne le layout du site
	 */
	public function getLayout()
	{
		return $this->layout;
	}
	
	
	/**
	* Definis le titre du site
	*
	* @param $title|le titre
	*/
	public function setSiteTitle($title)
	{
		$this->siteTitle = $title;
		return $this;
	}
	
	
	/**
	* Renvois le titre du site
	*
	* @return string $title|le titre
	*/
	public function getSiteTitle()
	{
		return $this->siteTitle;
	}
	
	
	/**
	* Definis le titre de la page
	*
	* @param $title|le titre
	*/
	public function setPageTitle($title)
	{
		$this->pageTitle = $title;
		return $this;
	}
	
	
	/**
	* Renvois le titre du site
	*
	* @return string $title|le titre
	*/
	public function getPageTitle()
	{
		return $this->pageTitle;
	}
	
	/**
	* Definis le slogan du site
	*
	* @param string $slogan
	*/
	public function setSiteSlogan($slogan)
	{
		$this->siteSlogan = $slogan;
	}
	
	
	/**
	* Renvois le slogan du site
	*
	* @return string $slogan
	*/
	public function getSiteSlogan()
	{
		return $this->siteSlogan;
	}
	
	/**
	* 
	* @param string $title|Titre du menu
	* @param string $name|Intitul� du lien
	* @param string $url|url du lien
	*/
	public function setMenu($title, $name, $url)
	{
		$this->menu[$title][] = array($url, $name);
		return $this;
	}
	
	
	/**
	* Renvois le tableau du menu
	*
	* @return array
	*/
	public function getMenu()
	{
		return $this->menu;
	}
	
	/**
	*	HEADER
	**/
	
	public function getHeader()
	{
		return $this->head;
	}
	
	public function setHeader($source)
	{
		$this->head.= nl2null($source);
		return $this;
	}
	
	public function setHeaderCss($url)
	{
		$this->head.= '<link rel="stylesheet" href="'.$url.'">' . PHP_EOL;
		return $this;
	}
		
	public function setHeaderJs($url)
	{
		$this->head.= '<script type="text/javascript" src="'.$url.'"></script>' . PHP_EOL;
		return $this;
	}
	
	public function setBreadcrumb($url, $name)
	{
		$this->breadcrumb[$url] = $name;
		return $this;
	}
	
	public function getBreadcrumb()
	{
		return $this->breadcrumb;
	}
	
	public function body($body)
	{
		$this->body = $body;
		return $this;
	}
	
	public function getBody()
	{
		return $this->body;
	}	
	
}
?>