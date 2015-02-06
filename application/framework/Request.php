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

class Request{
	public $method;             // Method d'acces
	public $url; 				// URL appellé par l'utilisateur
    public $urlElement;         // URL decoupé

	public $page = 1; 			// pour la pagination 
	public $data = false; 		// Données envoyé dans le formulaire $_POST clean
	public $params = array();	// Paramettre fournis par Router
	public $get = false;        // $_GET clean

	/**
	* @var Request
	* @access private
	* @static
	*/
	private static $_instance = null;
	 
	/**
	* Méthode qui crée l'unique instance de la classe
	* si elle n'existe pas encore puis la retourne.
	*
	* @param void
	* @return Request
	*/
	public static function getInstance() {
		if(is_null(self::$_instance)) {
			self::$_instance = new Request();
		}
		return self::$_instance;
	}

	function __construct() {
        $this->url          = isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:'index';
        $this->method       = (isset($_SERVER['REQUEST_METHOD'])) ? $_SERVER['REQUEST_METHOD'] : 'GET';
		$this->urlElement   = explode('/', trim($this->url, '/'));

		if (get_magic_quotes_gpc()) {
			$this->url=htmlentities($this->url, ENT_NOQUOTES, 'utf-8');
		} else {
			$this->url=htmlentities(addslashes($this->url), ENT_NOQUOTES, 'utf-8');
		}

		// Si on a une page dans l'url on la rentre dans $this->page
        // Valeur sécurisé d'entré
		if(isset($_GET['page'])){
			if(is_numeric($_GET['page'])){
				if($_GET['page'] > 0){
					$this->page = round($_GET['page']); 
				}
			}
		}

        // Valeur sécurisé d'entré
		if (!empty($_GET)) {
			$this->get = $this->cleaner($_GET);
		}

        // Valeur sécurisé d'entré
		if(!empty($_POST)) {
			$this->data = $this->cleaner($_POST);
		}
        
	}

	/**
	 * Recupere la clé, en fonction dans l'ordre d'importance
	 * Params, suivi des post et enfin les gets
     *
     * @param string $index
	 * 
	 * @return mixed
	 */
	public function __get($index) {
		if (isSet($this->params[$index])) {
			return $this->params[$index];
		} elseif (isSet($this->data->$index)) {
			return $this->data->$index;
		} elseif (isSet($this->get[$index])) {
			return $this->get[$index];
		}
		return false;
	}

    /**
     * Nettoyage des varibles
     *
     * @param $protect
     * @internal param array $data
     *
     * @return array
     */
	private function cleaner($protect) {
		$data = new stdClass();
		foreach($protect as $k=>$v) {
				
			if (is_array($v)) {
				$tmp = array();
				foreach($v AS $ak => $cv) {
					if (mb_detect_encoding($cv) != 'UTF-8') {
						// Ajout de preg_replace pour supprimer les espace multiple
						$cv = utf8_encode($cv);
					}
					// Supprime les espace multiples
					$cv = preg_replace('/\s\s+/', '', $cv);
					if (get_magic_quotes_gpc()) {
						$tmp[] = htmlentities($cv, ENT_NOQUOTES, 'utf-8');
					} else {
						$tmp[] = htmlentities(addslashes($cv), ENT_NOQUOTES, 'utf-8');
					}
				}
				$data->$k = $tmp;
				unset($tmp);
			} else {
				if (get_magic_quotes_gpc()) {
					$data->$k=htmlentities($v, ENT_NOQUOTES, 'utf-8');
				} else {
					$data->$k=htmlentities(addslashes($v), ENT_NOQUOTES, 'utf-8');
				}
			}
		}
		return $data; 
	}
	
	/**
	 * Retourne le controller appelé
	 * 
	 * @return string
	 */
	public function getController() {
		return (isset($this->controller)) ? $this->controller : 'index';
	}
	
	/**
	 * Retourne l'action appelé
	 * 
	 * @return string
	 */
	public function getAction() {
		return (isset($this->action)) ? $this->action : 'index';
	}
	
	/**
	 * Detection des injections SQL possible
	 * Plus utilisé car, succeptible de supprimer/bloqué des requetes en faux-positif
	 * 
	 * @param array $data
     * @deprecated n'est plus utilisé en raison de faux positif
	 */
	private function isInject($data) {
		while ( list ( $key, $value ) = each ( $data ) ) {
			if (is_array($value)) {
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
			) {
				header('HTTP/1.0 403 Forbidden'); 
				die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You don\'t have permission to access this file on this server.</p></body></html>');
			}
		}
	}
}