<?php
/*##################################################
 *                               Template.php
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

class Template {
 
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
   public static function getInstance($mvc='ToDel') {
 
	if(is_null(self::$_instance)) {
		self::$_instance = new Template($mvc);  
	}
 
	return self::$_instance;
   }
	
	public function __construct($mvc="ToDel")
	{
		$this->mvc = $mvc;
	}

	
	/*
	* @Variables array
	* @access private
	*/
	private $vars = array ();
	
	/*
	* @Variables string
	* @access private
	*/
	private $path = NULL;
	
	
	public function setPath($path /* path to view */)
	{
		$this->path = $path;
	}
	
	/**
	 *
	 * @set undefined vars
	 * @param string $index
	 * @param mixed $value
	 * @return void
	 */
	public function __set($index, $value) {
		$this->vars [$index] = $value;
	}
	
	public function __get($index)
	{
		/* Est logique ? */
		$index = clean($index, 'str');
		return isSet($this->vars[$index]) ? $this->vars[$index] : false;
	}
	
	function show($name) {
		
		$path = $this->path . DS . $name . '.php';
		
		if (file_exists ( $path ) == false) {
			throw new Exception ( 'Template not found in ' . $path ); 
			return false;
		}
		 
		// Load variables
		foreach ( $this->vars as $key => $value ) {
			if (is_array ( $value )) {
				$$key = $value;
			} elseif (is_object ( $value )) {
				$$key = $value;
			} else {
				$$key = stripslashes ( $value );
			}
		}
		
		include ($path);
	}
	
	public function getVars() {
		return $this->vars;
	}

}