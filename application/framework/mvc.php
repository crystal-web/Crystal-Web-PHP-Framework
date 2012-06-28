<?php
/**
* @title Simple MVC systeme - Registre
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/

final Class mvc{

/*** Variables ***/
public $vars = array();
/*** Methodes ***/

	public function __construct()
	{
	header("X-Powered-By: Crystal-Web.org");
	}

	
	public function __set($index, $value)
	{
	$this->vars[$index] = $value;
	}

	public function __get($index)
	{
	return $this->vars[$index];
	}
	
	
	
	
/***************************************REQUEST********************************************/
	
	/**
	 * 
	 * Retourne l'url apres l'adresse du site actuelle
	 */
	public function getUrl()
	{
		if (isSet($this->vars['Request']->url))
		{
			return $this->vars['Request']->url;
		}
	}
	
	
	/**
	 * 
	 * Retourne le controller courant
	 * @return string
	 */
	public function getController()
	{
		if (isSet($this->vars['Request']->controller))
		{
			return $this->vars['Request']->controller;
		}
	}
	
	
	/**
	 * 
	 * Retourne l'action courante
	 * @return string
	 */
	public function getAction()
	{
		if (isSet($this->vars['Request']->action))
		{
			return $this->vars['Request']->action;
		}
	}
}


?>
