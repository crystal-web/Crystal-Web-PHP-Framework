<?php
class Request{
	

	public $url; 				// URL appellé par l'utilisateur
	public $page = 1; 			// pour la pagination 
	public $prefix = false; 	// Prefixage des urls /prefix/url
	public $data = false; 		// Données envoyé dans le formulaire
	public $get = false;
	
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
			$this->isInject($_GET);
			$this->get = $this->cleaner($_GET);
		}
		
		// Si des données ont été postées ont les entre dans data
		if(!empty($_POST)){
			$this->isInject($_POST);
			$this->data = $this->cleaner($_POST);
		}
	}
	
	/*
	public function __get($index)
	{
		return (isSet($this->data->$index)) ? $this->data->$index : false;
	}//*/
	
	
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
						$cv = utf8_encode($cv);
					}
		
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
	
	/**
	 * 
	 * Detection des injections SQL possible
	 * @param array $data
	 */
	private function isInject($data)
	{
		while ( list ( $key, $value ) = each ( $data ) ) {
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
			stristr ( $value, '<>' ) || 
			stristr ( $value, '=' ) || 
			stristr ( $value, '/**/' ) ||
			
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