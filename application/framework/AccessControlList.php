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

Class AccessControlList extends PermissionsModel{ 

	private $fullPower = false;
	private $group;
	private $parent = array();
	private $model;
	private $permissionHistory;
	public $groupList = array();
	
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
    * @changeLog
    *  * changement de retour de self::$_instance a new static();
	* @param void
	* @return Singleton
	*/
	public static function getInstance() {
        static $instance = null;
        if($instance === null){
            $instance = new static();
        }
        return $instance;
	}
	
	public function remove_expery_date() {
		$this->query("DELETE FROM `" . __SQL . "_Permissions` WHERE `expiry_date` < " . time() . " AND `expiry_date` != 0 ");
	}
	
	public function isGrant() {
		return $this->isAllowed('*');
	}
	

}