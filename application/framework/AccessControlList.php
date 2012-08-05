<?php
/**
* @title Connection
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description 
*/

Class AccessControlList {
	protected $mvc;
	private $fullPower = false;
	private $group;
	private $parent = array();
	private $model;
	private $permissionHistory;
	
	/**
	 * 
	 * Constructeur 
	 * @param unknown_type $mvc
	 */
	public function __construct($mvc)
	{
		Log::setLog('Construction...', get_class($this));
		$this->mvc = $mvc;
	//	$this->group = ($this->mvc->Session->user('group')) ? 'Moderateur' : '*';
		$this->group = ($this->mvc->Session->user('group')) ? $this->mvc->Session->user('group') : '*';
		$this->permissionHistory = new stdClass();
	}
	
	
	
	private function getParent($lastParent)
	{
		$this->parent[] =  "`name` LIKE  '" . $lastParent . "'";
		
		$this->model->table = __SQL . '_AclInheritance';
		
		$prepare = array(
			'conditions' => array('child ' => $lastParent)
		);
		
		$resp = $this->model->findFirst($prepare);
		
		if ($resp)
		{
			Log::setLog($lastParent . ' is child of ' . $resp->parent, get_class($this));
			return $this->getParent($resp->parent);
		}

	}
	
	
	
	public function isAllowed($controller =NULL, $action='*')
	{
		
		if (strpos($controller, '.'))
		{
			Log::setLog('Params has old', get_class($this));
			$new = explode('.', $controller);
			$controller = (isSet($new[0])) ? $new[0] : null;
			$action = (isSet($new[1])) ? $new[1] : '*';
		}
		
		
		if ($action == '*') { $action ='%';}
		
		if(!empty($controller))
		{	
			$searchThisAcl = $controller.'.'.$action;
		}
		else
		{
			$searchThisAcl = $this->mvc->getController().'.'.$action;
		}
		
		Log::setLog('Change ACL test to ' . $searchThisAcl, get_class($this));
		
		if (isSet($this->permissionHistory->$searchThisAcl))
		{
			Log::setLog('Request for ' . $searchThisAcl . ' has already checked return', get_class($this));
			return $this->permissionHistory->$searchThisAcl;
		}
		
		/***************************************
		*	On charge le model
		***************************************/
		$this->model = loadModel('Acl');
		$this->model->table = __SQL . '_AclInheritance';
		
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

		
		$search = array(
			'conditions' => "`permission` LIKE  '".$searchThisAcl."'
											AND ("  . implode(' OR ', $this->parent) . ")
										OR `permission` LIKE '*'
											AND ("  . implode(' OR ', $this->parent) . ")"
		);
		
		$this->model->table = __SQL . '_AclPermission';
		
		$itsOk = $this->model->findFirst($search);
		
		//$this->model->debug();
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