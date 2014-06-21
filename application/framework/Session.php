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

class Session{
	/**
	* @var Session
	* @access private
	* @static
	*/
	private static $_instance = null;
	
	/**
	 * Méthode qui crée l'unique instance de la classe
	 * si elle n'existe pas encore puis la retourne.
	 *
	 * @param void
	 * @return Session
	 */
	public static function getInstance() {
		if (!isset($_SESSION['cw'])) {
		$analCache = new Cache('analizer');
		$anal = $analCache->getCache(); 
		
		$java = preg_match('#java#Usi', $_SERVER['HTTP_USER_AGENT']);
		$hasJava = ($java) ? 'Oui' : 'Non';
		$anal[] = array(
			'user' => $_SERVER['HTTP_USER_AGENT'], 
			'hasJava' => $hasJava
			);
		$analCache->setCache($anal);
		}
		
		if(is_null(self::$_instance)) {
			self::$_instance = new Session();
		}
		return self::$_instance;
	}
	
	/**
	 * Démarre la session et initialise la clé token en cas de besoin
	 * 
	 * @return void
	 */
	public function __construct() {
		if(!isset($_SESSION)) {
			Log::setLog('Start session', 'Session');
			session_start();
		}
		
		if ( !isSet($_SESSION['token']) ) {
			$this->makeToken();
		}
	}
	
	/**
	 * Ferme la session proprements
	 * 
	 * @return void;
	 */
	public function __destruct() {
		session_write_close ( );
	}
	
	/***************************************
	*	Token
	***************************************/
	
	/**
	 * Régénere la clé token pour la session
	 * @return void
	 */
	public function makeToken() {
		Log::setLog('Token assigned', 'Session');
		return $_SESSION['cw']['token'] = (!isset($_SESSION['cw']['token'])) ? md5(time()*rand()+magicword) : $_SESSION['cw']['token'];
	}
	
	/**
	 * Veirife la clé token de la session 
	 *
	 * @return boolean
	 */
	public function token() {
		
		if (isSet($_GET['token']) && isset($_SESSION['cw']['token'])) {
			Log::setLog('Check token: reçu ' . $_GET['token'], get_class($this));
			Log::setLog('Check token: attendu ' . $_SESSION['cw']['token'], get_class($this));
			$resp = ($_GET['token'] === $_SESSION['cw']['token']) ? true : false;
			Log::setLog('Check token: ' . print_r($resp, true), get_class($this));
			return $resp;
		}
		return false;
	}
	
	/**
	 * Retourne la clé token de la session 
	 *
	 * @return SESSION token
	 */
	public function getToken() {
	//	$this->makeToken();
		return isset($_SESSION['cw']['token']) ? $_SESSION['cw']['token'] : $this->makeToken();
	}
	
	/***************************************
	*	Flash info
	***************************************/
	
	/**
	 * Affiche un message flash sur le site
	 * 
	 * @return void
	 */
	public function setFlash($message,$type = 'success'){
		$_SESSION['cw']['flash'][] = array(
			'message' => $message,
			'type'	=> $type
		);
	}
	
	/**
	 * Récupere les message flash
	 * 
	 * @return html tag
	 */
	public function flash(){
		if(isset($_SESSION['cw']['flash'])){
			$html = new Html();
			foreach($_SESSION['cw']['flash'] AS $k => $v)://&times;
				$html->div(array('class' => 'alert-message '.$v['type'], 'data-alert' => 'alert'))
					->button(array('type' => 'button', 'class' => 'close'), '&times;')->end()
					->p($v['message'])->end()
				->end();
			endforeach;
			$_SESSION['cw']['flash'] = array();
			return $html; 
		}
	}
	
	/***************************************
	*	Membre
	***************************************/

	/**
	 * Savoir si le client est connecté
	 * 
	 * @return boolean
	 */
	public function isLogged() {
		return ($this->user('login')) ? true : false;
	}

	/**
	 * Force la déconnexion du client
	 * 
	 * @return void
	 */
	public function logout() {
		if (isset($_SESSION['cw']['user'])) {
			$this->del('user');
		}
	}
	
	/**
	 * Récupére les info du client si il y en a
	 * 
	 * @return mixte
	 */
	public function user($key) {
		switch($key) {
		case 'id':
			return (isset($_SESSION['cw']['user']->id)) ? $_SESSION['cw']['user']->id : false;
		break;
		case 'login':
			return  (isset($_SESSION['cw']['user']->user)) ? $_SESSION['cw']['user']->user : false;
		break;
		case 'mail':
			return (isset($_SESSION['cw']['user']->mail)) ? $_SESSION['cw']['user']->mail : false;
		break;
		case 'group':
			if (isset($_SESSION['cw']['user']->group)) {
				return $_SESSION['cw']['user']->group;
			} else {
				$acl = AccessControlList::getInstance();
				return $acl->getDefaultGroupGuest();
			}
		break;
		// Action par defaut
		default:
			if($this->read('user')){
				return (isset($this->read('user')->$key)) ? $this->read('user')->$key : false; 
			}
		break;
		}
		return false;
	}
	
	/**
	 * Permet d'ecrire dans la session
	 * 
	 * @return void
	 */
	public function write($key,$value){
		$_SESSION['cw'][$key] = $value;
	}
	
	/**
	 * Permet de supprimer une clé dans la session
	 * 
	 * @return void
	 */
	public function del($key) {
		unset($_SESSION['cw'][$key]);
	}
	
	/**
	 * Action par défaut lorsque Session est appelé comme un stdClass
	 * 
	 * @return mixte
	 */
	public function __set($index, $value) {
		$_SESSION['cw'][$index] = $value;
	}

	/**
	 * Action par défaut lorsque Session est appelé comme un stdClass
	 * 
	 * @return mixte
	 */
	public function __get($index) {
		return isSet($_SESSION['cw'][$index]) ? $_SESSION['cw'][$index] : false;
	}

	/**
	 * Permet de lire une clé de session, ou de récupérer la session
	 * 
	 * @return mixte
	 */
	public function read($key = null){
		if($key){
			if(isset($_SESSION['cw'][$key])){
				return $_SESSION['cw'][$key]; 
			}else{ return false; }
		}else{ return $_SESSION['cw']; }
	}
}