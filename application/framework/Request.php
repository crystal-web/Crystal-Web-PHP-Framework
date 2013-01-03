<?php
/*##################################################
 *                                Request.php
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
class Request{
	

	public $url; 				// URL appellé par l'utilisateur
	public $page = 1; 			// pour la pagination 
	public $data = false; 		// Données envoyé dans le formulaire
	public $get = false;

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
			self::$_instance = new Request();  
		}
		return self::$_instance;
	}

	function __construct(){
		$this->url = isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:'index'; 

		// Si on a une page dans l'url on la rentre dans $this->page
		if(isset($_GET['page'])){
			if(is_numeric($_GET['page'])){
				if($_GET['page'] > 0){
					$this->page = round($_GET['page']); 
				}
			}
		}
		
		if (!empty($_GET))
		{
			$this->get = $this->cleaner($_GET);
		}
		
		// Si des données ont été postées ont les entre dans data
		if(!empty($_POST)){
			$this->data = $this->cleaner($_POST);
		}
	}
	
	
	/**
	 * 
	 * Nettoyage des varibles
	 * @param array $data
	 */
	private function cleaner($protect)
	{
		$data = new stdClass();
		foreach($protect as $k=>$v){
			if (is_array($v))
			{
				$tmp = array();
				foreach($v AS $ak => $cv)
				{
		
					if (mb_detect_encoding($cv) != 'UTF-8')
					{
						// Ajout de preg_replace pour supprimer les espace multiple
						$cv = utf8_encode($cv);
					}
					
					$cv = preg_replace('/\s\s+/', '', $cv);
		
					if (get_magic_quotes_gpc()) {
						$tmp[] = htmlentities($cv, ENT_NOQUOTES, 'utf-8');
					}
					else {
						$tmp[] = htmlentities(addslashes($cv), ENT_NOQUOTES, 'utf-8');
					}
		
		
				}
					
				$data->$k = $tmp;
				unset($tmp);
			}
			else
			{
				if (get_magic_quotes_gpc()) {
					$data->$k=htmlentities($v, ENT_NOQUOTES, 'utf-8');
				}
				else {
					$data->$k=htmlentities(addslashes($v), ENT_NOQUOTES, 'utf-8');
				}
			}
		}
		
		return $data; 
	}
	
	
	public function getController()
	{
		return (isset($this->controller)) ? $this->controller : 'index';
	}
	
	public function getAction()
	{
		return (isset($this->action)) ? $this->action : 'index';
	}
	
	/**
	 * 
	 * Detection des injections SQL possible
	 * Plus utilisé car, succeptible de supprimer/bloqué des requetes en faux-positif
	 * @param array $data
	 */
	private function isInject($data)
	{
		while ( list ( $key, $value ) = each ( $data ) ) {
			if (is_array($value))
			{
				return $this->isInject($value);
			}
			if (
			stristr ( $value, 'ALTER ' ) || 
			stristr ( $value, 'CREATE ' ) || 
			stristr ( $value, 'DELETE FROM' ) || 
			stristr ( $value, 'DROP ' ) || 
			stristr ( $value, 'FROM ' ) || 
			stristr ( $value, 'SELECT ' ) || 
			stristr ( $value, 'SET ' ) || 
			//stristr ( $value, 'script' ) || 
			stristr ( $value, 'SHUTDOWN ' ) || 
			stristr ( $value, 'UPDATE ' ) || 
			stristr ( $value, 'WHERE ' ) || 
			//stristr ( $value, '<>' ) || 
			//stristr ( $value, '=' ) || 
			//stristr ( $value, '/**/' ) ||
			
			// cas particulier parmis tant d'autre... 
			stristr ( $value, '@version ' )
			)
			{
				header('HTTP/1.0 403 Forbidden'); 
				die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You don\'t have permission to access this file on this server.</p></body></html>');
			}
		}
	}

}
