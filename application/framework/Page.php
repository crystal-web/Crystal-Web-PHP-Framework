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

private $pageTitle = NULL;
private $menu = array();
private $head;
private $breadcrumb;
private $body;
private $rss;
private $beforeBody;

// private $overwritelock = false;			// Vérrouillé le template

	/**
	* @var Page
	* @access private
	* @static
	*/
	private static $_instance = null;
	 
	
	/**
	* Méthode qui crée l'unique instance de la classe
	* si elle n'existe pas encore puis la retourne.
	*
	* @param void
	* @return Page
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
	
	/**
	* Definis le titre de la page
	*
	* @param $title|le titre
	*/
	public function setPageTitle($title) {
		$this->pageTitle = $title;
		return $this;
	}
	
	/**
	* Renvois le titre du site
	*
	* @return string $title|le titre
	*/
	public function getPageTitle() {
		return $this->pageTitle;
	}

	/**
	* 
	* @param string $title|Titre du menu
	* @param string $name|Intitul� du lien
	* @param string $url|url du lien
	*/
	public function setMenu($title, $name, $url) {
		$this->menu[$title][] = array($url, $name);
		return $this;
	}
	
	
	/**
	* Renvois le tableau du menu
	*
	* @return array
	*/
	public function getMenu() {
		return $this->menu;
	}
	
	/**
	*	HEADER
	**/
	
	public function getHeader() {
		return $this->head;
	}
	
	public function setHeader($source) {
		$this->head.= nl2null($source);
		return $this;
	}
	
	public function setHeaderCss($url) {
		$this->head.= '<link rel="stylesheet" href="'.$url.'">' . PHP_EOL;
		return $this;
	}
		
	public function setHeaderJs($url) {
		$this->head.= '<script type="text/javascript" src="'.$url.'"></script>' . PHP_EOL;
		return $this;
	}
	
	public function setBreadcrumb($url, $name) {
		$this->breadcrumb[$url] = $name;
		return $this;
	}
	
	public function getBreadcrumb($class = 'pull-left') {

		$html = '<ol class="breadcrumb '.$class.'">';
		$html .= '<li><a href="' . Router::url() . '" title="Page d\'accueil">Accueil</a> </li>';
		if (count($this->breadcrumb)) {
			foreach($this->breadcrumb AS $url => $name) {
				$html .= '<li>' .
                            '<a href="' . Router::url($url) . '" title="' . clean($name, 'str') .'">' . clean($name, 'str') .'</a>' .
                        '</li>';
			}
		}
        if (!is_null($this->getPageTitle())){
            $html .= '<li class="active">'.clean($this->getPageTitle(), 'str').'</li>';
        }

		$html .= '</ol>';
		return $html;
	}
	
	public function body($body) {
		$this->body = $body;
		return $this;
	}
	
	public function getBody() {
		return $this->body;
	}
	
	public function setRss($adresse) {
		$this->rss = $adresse;
	}
	
	public function getRss() {
		if ($this->rss) {
			return '<link rel="alternate" href="' . $this->rss . '" title="'.$this->siteTitle.'" type="application/rss+xml">';
		}
	}
	
	
	public function setBeforeBody($html) {
		$this->beforeBody .= $html;
	}
	
	public function getBeforeBody() {
		return $this->beforeBody;
	}
	
}
?>