<?php
class Page {

private $siteTitle=NULL;
private $pageTitle=NULL;
private $menu=array();
private $head;
private $breadcrumb;
private $body;
public $layout;

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
	* 
	* @param string $title|Titre du menu
	* @param string $name|Intitulé du lien
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
	
	public function setHeaderCss($url)
	{
	$this->head.= '<link rel="stylesheet" href="'.$url.'">';
	return $this;
	}
	
	
	public function setHeaderJs($url)
	{
	$this->head.= '<script type="text/javascript" src="'.$url.'"></script>';
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