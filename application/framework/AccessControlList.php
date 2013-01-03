<?php
/*##################################################
 *                           AccessControlList.php
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
Class AccessControlList { 

	private $fullPower = false;
	private $group;
	private $parent = array();
	private $model;
	private $permissionHistory;
	private $groupList = array();
	
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
			self::$_instance = new AccessControlList();  
		}
		return self::$_instance;
	}
	
	/**
	 * 
	 * Constructeur 
	 */
	public function __construct()
	{
		Log::setLog('Construction...', get_class($this));
		$session = Session::getInstance();
		$this->group = ($session->user('group')) ? $session->user('group') : '*';
		$this->permissionHistory = new stdClass();
	}
	
	/**
	 * Retourne la liste des groupes de l'utilisateur courant
	 * + Ajout d'un re-check si groupList est vide
	 * 		Check avec les droits super user
	 */
	public function getGroupList()
	{
		/**
		 * Impossible de connaitre le group, sans le réactualisé ?
		 */
		if (!count($this->groupList)) {
			$this->isAllowed('*');
			$this->getParent($this->group);
		}
		Log::setLog('get list group of ' . $this->group . ' => ' . implode(',', $this->groupList), get_class($this));
		return $this->groupList;
	}
	
	public function getParent($lastParent)
	{
		// Lie name au parent direct
		$this->parent[] =  "`name` LIKE  '" . $lastParent . "'";
		$prepare = array(
			'conditions' => array('child' => $lastParent)
		);
		$this->model->table = __SQL . "_AclInheritance";
		$resp = $this->model->findFirst($prepare);
		if ($resp)
		{
			Log::setLog($lastParent . ' is child of ' . $resp->parent, get_class($this));
			if ($resp->parent != '*')
			{
				$this->groupList[$resp->parent] = $resp->parent;
			}
			
			if ($lastParent != '*')
			{
				$this->groupList[$lastParent] = $lastParent;
			}
			
			return $this->getParent($resp->parent);
		}

	}
	
	 
	
	public function isAllowed($controller =NULL, $action='%')
	{
		$request = Request::getInstance();

		// Controller 
		$controller = (empty($controller)) ? $request->getController() : $controller;
		
		
		if (strpos($controller, '.'))
		{
			Log::setLog('Params has old', get_class($this));
			$new = explode('.', $controller);
			$controller = (isSet($new[0])) ? $new[0] : null;
			$action = (isSet($new[1])) ? $new[1] : '*';
		}
		
		$searchThisAcl = $controller.'.'.$action;
		Log::setLog('isAllowed ' . $controller . '.' . $action, get_class($this));
		
		if (isSet($this->permissionHistory->$searchThisAcl))
		{
			Log::setLog('Request for ' . $searchThisAcl . ' has already checked return', get_class($this));
			return $this->permissionHistory->$searchThisAcl;
		}
		
		/***************************************
		*	On charge le model
		***************************************/
		$this->model = new AclInheritanceModel();
		
		// Search parent of group 
		if (empty($this->parent))
		{
			Log::setLog('Search parent of ' . $this->group, get_class($this));
			$this->getParent( $this->group );
		}
		else
		{
			Log::setLog('Use parent of ' . $this->group . ' in memory', get_class($this));
		}

		// cont.act or cont.%
		$addJoker = ($action != '*') ? " OR `permission` LIKE '" . $controller.".%' AND ("  . implode(' OR ', $this->parent) . ") ": NULL;
		$search = array(
			'conditions' => "`permission` LIKE  '".$searchThisAcl."' AND ("  . implode(' OR ', $this->parent) . ")
							 OR `permission` LIKE '*' AND ("  . implode(' OR ', $this->parent) . ")" . $addJoker
		);
		
		$this->model->table = __SQL . '_AclPermission';
		$itsOk = $this->model->findFirst($search);
		
		if ($itsOk)
		{
			Log::setLog('Right obtain for '.$this->group.' with inheritance of  ' . $itsOk->name . ' for ' . $itsOk->permission, get_class($this));
			$this->permissionHistory->$searchThisAcl = true;
			return true;
		}
		else
		{
			Log::setLog('Permission deny for ' . $searchThisAcl, get_class($this));
			$this->permissionHistory->$searchThisAcl = false;
			return false;
		}
	}
	
	
	public function isGrant()
	{
		Log::setLog('Check permission for all access', get_class($this));
		return $this->isAllowed('*');
	}	
}