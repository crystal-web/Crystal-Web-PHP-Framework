<?php
Class PermissionsModel extends Model {

	public $lastPerm = NULL;
	private static $inheritanceGroup = array();
	private static $usergroup = NULL;
	private $permissions = array();
	
	private $permissionHistory = array();
	
	
	public function install() {
		$this->query("CREATE TABLE IF NOT EXISTS `".__SQL."_Permissions` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(50) NOT NULL,
			  `type` enum('0','1') NOT NULL DEFAULT '0',
			  `permission` varchar(200) NOT NULL,
			  `default` enum('n','y') NOT NULL DEFAULT 'n',
			  `has_guest` enum('n','y') NOT NULL DEFAULT 'n',
			  `expiry_date` int(11) NOT NULL DEFAULT '0',
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `unique` (`name`,`permission`,`type`),
			  KEY `user` (`name`,`type`),
			  KEY `world` (`name`,`type`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

		$this->query("CREATE TABLE IF NOT EXISTS `".__SQL."_Permissions_inheritance` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `child` varchar(50) NOT NULL,
			  `parent` varchar(50) NOT NULL,
			  `type` tinyint(1) NOT NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `child` (`child`,`parent`,`type`),
			  KEY `child_2` (`child`,`type`),
			  KEY `parent` (`parent`,`type`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
		$this->query("INSERT INTO `".__SQL."_Permissions` (`id`, `name`, `type`, `permission`, `default`, `has_guest`, `expiry_date`) VALUES
			(NULL, 'SuperUser', '0', 'group', 'n', 'n', 0),
			(NULL, 'SuperUser', '0', '*', 'n', 'n', 0),
			(NULL, 'Moderateur', '0', 'group', 'n', 'n', 0),
			(NULL, 'Membre', '0', 'group', 'y', 'n', 0),
			(NULL, 'Visiteur', '0', 'group', 'n', 'y', 0);
		INSERT INTO `".__SQL."_Permissions_inheritance` (`id`, `child`, `parent`, `type`) VALUES
			(NULL, 'Visiteur', 'Membre', 0),
			(NULL, 'Membre', 'Moderateur', 0),
			(NULL, 'Moderateur', 'SuperUser', 0);");
	}

	public function getGroupList($group = false) {
		$session = Session::getInstance();
		
		$group = (!$group) ? $session->user('group') : $group;
		$this->genInheritance($group);
		if (count(self::$inheritanceGroup)) {
			return self::$inheritanceGroup;
		} else {
			$default = $this->getDefaultGroupGuest();
			return array($default => $default);
		}
	}

	public function getDefaultGroup() {
		$this->setTable('Permissions');
		$prepare = array('conditions' => array( 'default' => 'y'), 'limit' => '1');
		$resp = $this->findFirst($prepare);
		if ($resp) {
			return $resp->name;
		} 
		
		$data = new stdClass();
		$data->name = 'Membre';
		$data->default = 'y';
		$data->has_guest = 'n';
		$data->type = '0';
		$data->expiry_date = 0;
		// Indique le type, surtout pour ne pas avoir un champ vide
		$data->permission = 'group';
		
		if (!$this->save($data)) {
			$this->logger->setLog(get_class($this), "[Erreur][Permissions][".__LINE__."] Impossible d'ajouter le groupe 'Membre' " . $this->lastError);
		}
		
		return 'Membre';
	}
	
	public function getDefaultGroupGuest() {
		$this->setTable('Permissions');
		$prepare = array(
			'fields' => 'name',
			'conditions' => array( 'has_guest' => 'y'),
			'limit' => '1');
		$resp = $this->findFirst($prepare);
		if ($resp) {
			return $resp->name;
		} 
		
		$data = new stdClass();
		$data->name = 'Visiteur';
		$data->default = 'n';
		$data->has_guest = 'y';
		$data->type = '0';
		$data->expiry_date = 0;
		// Indique le type, surtout pour ne pas avoir un champ vide
		$data->permission = 'group';
		
		if (!$this->save($data)) {
			$this->logger->setLog(get_class($this), "[Erreur][Permissions][".__LINE__."] Impossible d'ajouter le groupe 'Visiteur' " . $this->lastError);
		}
		
		return 'Visiteur';
	}
	
	/**
	 * Recherche de l'héritage d'un groupe, recurcivement  
	 */
	private function genInheritance($groupName) {
		$this->setTable('Permissions_inheritance');
		$prepare = array(
			'fields' => 'child',
			'conditions' => array(
				'parent' => $groupName,
				'type' => '0' 
				)
			);
		$resp = $this->find($prepare);

		for($i=0;$i<count($resp);$i++) {
			self::$inheritanceGroup[$resp[$i]->child] = $resp[$i]->child;
			$this->getInheritance($resp[$i]->child);
		}
	}
	
	public function getInheritance($groupName) {
		$this->genInheritance($groupName);
		return self::$inheritanceGroup;
	}
	
	
	public function isAllowed($controller=NULL, $action=NULL) {
		$session = Session::getInstance();
		$request = Request::getInstance();
		$controller = (is_null($controller)) ? $request->getController() : $controller;

		if (strpos($controller, '.')) {
			$new = explode('.', $controller);
			$controller = (isSet($new[0])) ? $new[0] : null;
			unset($new[0]);
			$action = (isSet($new[1])) ? implode('.', $new) : '*';
		} else {
			$action = (is_null($action)) ? $request->getAction() : $action;
		}
		
		// Si une clé existe dans l'historique, on renvois ça réponse
		if (isset($this->permissionHistory[trim($controller.'.'.$action, '.')])) {
			Log::setLog('[Info][Permissions]['.__LINE__.'] Permission in history', get_class($this));
			return $this->permissionHistory[trim($controller.'.'.$action, '.')];
		}
		
		// Si on a pas de inheritanceGroup, ou pas de groupe
		// On recherche les info
		if (!count(self::$inheritanceGroup) OR is_null(self::$usergroup)) {
			self::$usergroup = ($session->user('group')) ? $session->user('group') : $this->getDefaultGroupGuest();
			$this->genInheritance(self::$usergroup);
			$parent = (count(self::$inheritanceGroup)) ? 'parent of "' . implode('", "', self::$inheritanceGroup).'"' : NULL;
			Log::setLog('[Info][Permissions]['.__LINE__.'] User group "'.self::$usergroup.'" ' . $parent, get_class($this));
		}
		
		$permissionsToTest = $this->make_permission(trim($controller.'.'.$action, '.'));
        $authorized = false;
        /**
         * On cherche les correspondances
         * Si group_permissions renvois true, c'est que la permission existe
         * et que celle-ci n'est pas un refus
         *
        if ($this->group_permissions($permissionsToTest)) {
            $this->permissionHistory[trim($controller.'.'.$action, '.')] = true;
            $authorized = true;
        }*/
        $user = ($session->isLogged()) ? $session->user('login') : false;
        if ($this->testPermissions($permissionsToTest, $user)) {
            $this->permissionHistory[trim($controller.'.'.$action, '.')] = true;
            $authorized = true;
        }

        /**
         * On cherche les correspondances pour l'utilisateur, si il est connecter
         * Si user_permissions renvois true, c'est que la permission existe
         * et que celle-ci n'est pas un refus
         *
         *
        if ($session->isLogged() && $this->user_permissions($session->user('login'), $permissionsToTest)) {
            $this->permissionHistory[trim($controller.'.'.$action, '.')] = true;
            $authorized = true;
        }//*//* elseif($session->isLogged() && $authorized) {
            $authorized = false;
        }//*/
		$this->permissionHistory[trim($controller.'.'.$action, '.')] = false;
		return $authorized;
	}

	/**
	 * Créer la hierachie, pour la permission demandé
	 */
	private function make_permission($perm) {
		$this->lastPerm = $perm;
		// Initialise
		$permissions = array();
		

		// Si il y a plus d'un ".", on recherche un droit suppérieur
		// dans la hierachie, pour se faire on explose la chaine au "."
		// et on demande un test avec le joker
		// sysinfo.rpc.byterate
		// sysinfo.rpc.*
		// sysinfo.*
		
		if ($perm == '*.index') {
			return $permissions;
		}
		// On explode la permissions, pour avoir tout les paramettres possible
		$new = explode('.', $perm);
		$controller = $new[0];
		unset($new[0]);
		
		$last = $controller;
		// Initialisation, avec la SuperPErm sur le controller
		$permissions[] = $last.'.*';
		$permissions[] = '-' . $last.'.*';
		
		// Boucle tant que la taile de new (les paramettres) n'est pas atteinte
		// Sans le 0, qui etait le controller 
		for($i=1;$i<count($new);$i++) {
			$last = $last.'.'.$new[$i];
			$permissions[] = $last . '.*';
			$permissions[] = '-' . $last.'.*';
		}
		
		if ($new[count($new)] !='*') {
			$permissions[] = $perm;
			$permissions[] = '-' . $perm;
		}
		
		Log::setLog('[Info][Permissions]['.__LINE__.'] Permission rules "' . implode('", "', $permissions) . '"', get_class($this));
		return $permissions;
	}

    private function testPermissions($permissions, $username = false) {
        $this->setTable('Permissions');
        // Crée la liste des groupes enfant du groupe courant

        $searchItPerm = NULL;
        for($i=0;$i<count($permissions);$i++) {
            $searchItPerm.="OR `permission` = '" . $permissions[$i]."' ";
        }

        if (!is_null($searchItPerm)) {
            $searchItPerm = 'OR '.trim($searchItPerm, 'OR').' ';
        }

        $searchItGroup = NULL;
        $orderSql = NULL;
        // Crée la liste des groupes enfant du groupe courant
        $i=1;
        foreach (self::$inheritanceGroup as $key => $value) {
            $searchItGroup.="OR `name` = '" . $value . "' ";
            $orderSql.=" when '" . $value . "' then " . ($i+1);
            $i++;
        }

        if (!is_null($searchItGroup)) {
            $searchItGroup = ' OR '.trim($searchItGroup, 'OR').' ';
        }

        $findByUser = ($username) ? '`name` LIKE \''.$username.'\' OR ' : '';
        $search = array(
            'fields' => 'permission',
            'conditions' =>
                //-- Groupe et enfants
                "( " . $findByUser . " `name` = '" . self::$usergroup . "' ".$searchItGroup." )" .
                //-- Permissions et hierachie
                " AND (  `permission` LIKE '*'" . $searchItPerm . " ) AND `permission` NOT LIKE  'group'",
            //-- Ordonner par le groupe principale, vers les enfants
            'order' => " ( case name  when '" . self::$usergroup . "' then 0 " . $orderSql . " end ), `permission` DESC");

        $itsOk = $this->find($search);

        if (!count($itsOk)) {
            Log::setLog('[Info][Permissions]['.__LINE__.'] Permission deny for ' . $this->lastPerm . ' not found', get_class($this));
            return false;
        }

        for($i=0;$i<count($itsOk);$i++) {
            Log::setLog('[Info][Permissions]['.__LINE__.'] Check '.$itsOk[$i]->permission, get_class($this));
            if ($itsOk[$i]->permission[0] == '-') {
                Log::setLog('[Info][Permissions]['.__LINE__.'] Permission deny for ' . $this->lastPerm . ' by "'.$itsOk[$i]->permission[0].'"', get_class($this));
                return false;
            }
        }

        return true;
    }

	/**
	 * Permission dans le groupe et les enfants
	 */
	private function group_permissions($permissions) {
		$this->setTable('Permissions');
		// Crée la liste des groupes enfant du groupe courant

		$searchItPerm = NULL;
		for($i=0;$i<count($permissions);$i++) {
			$searchItPerm.="OR `permission` = '" . $permissions[$i]."' ";
		}

		if (!is_null($searchItPerm)) {
			 $searchItPerm = 'OR '.trim($searchItPerm, 'OR').' ';
		}

		$searchItGroup = NULL;
		$orderSql = NULL;
		// Crée la liste des groupes enfant du groupe courant
		$i=1;
		foreach (self::$inheritanceGroup as $key => $value) {
			$searchItGroup.="OR `name` = '" . $value . "' ";
			$orderSql.=" when '" . $value . "' then " . ($i+1);
		$i++;
		}

		if (!is_null($searchItGroup)) {
			$searchItGroup = ' OR '.trim($searchItGroup, 'OR').' ';
		}

		$search = array(
					'fields' => 'permission',
					'conditions' =>
					//-- type 0 = groupe
						"`type` = '0'
						AND
						( " .
							//-- Groupe et enfants
							"( `name` = '" . self::$usergroup . "' ".$searchItGroup." )
							AND
							" .
							//-- Permissions et hierachie
							"(  `permission` LIKE '*'" . $searchItPerm . " )
						)
						",
					//-- Ordonner par le groupe principale, vers les enfants
					'order' => " ( case name  when '" . self::$usergroup . "' then 0 " . $orderSql . " end ), `permission` DESC");

		$itsOk = $this->find($search);

		if (!count($itsOk)) {
			Log::setLog('[Info][Permissions]['.__LINE__.'] Permission deny for ' . $this->lastPerm . ' not found', get_class($this));
			return false;
		}

		for($i=0;$i<count($itsOk);$i++) {
			Log::setLog('[Info][Permissions]['.__LINE__.'] Check '.$itsOk[$i]->permission, get_class($this));
			if ($itsOk[$i]->permission[0] == '-') {
				Log::setLog('[Info][Permissions]['.__LINE__.'] Permission deny for ' . $this->lastPerm . ' by "'.$itsOk[$i]->permission[0].'"', get_class($this));
				return false;
			}
		}

		return true;
	}

	private function user_permissions($user, $permissions) {
		$searchItPerm = NULL;
		for($i=0;$i<count($permissions);$i++){
			$searchItPerm.="OR `permission` = '" . $permissions[$i]."' ";
		}
		
		if (!is_null($searchItPerm)) {
			 $searchItPerm = 'OR '.trim($searchItPerm, 'OR').' ';
		}
		$this->setTable("Permissions");
		$search = array(
				'fields' => 'permission',
				'conditions' =>
						"`type` = '1'
						AND (`name` = '".$user."'". PHP_EOL ."
						AND ( `permission` LIKE '*' " . $searchItPerm . " )
						)
						",
				'order' => "`permission` DESC");

		$itsOk = $this->find($search);
		Log::setLog($this->getLastRequest());
		if (!count($itsOk)) {
			Log::setLog('[Info][Permissions]['.__LINE__.'] ' . $this->getLastRequest() . ' Permission deny for ' . $this->lastPerm . ' not found', get_class($this));
			return false;
		}
		
		for($i=0;$i<count($itsOk);$i++) {
			if ($itsOk[$i]->permission[0] == '-') {
				Log::setLog('[Info][Permissions]['.__LINE__.'] Permission deny for ' . $this->lastPerm . ' by "'.$itsOk[$i]->permission[0].'"', get_class($this));
				return false;
			}
		}
		return true;
	}

	public function addUserPermission($uname, $permission, $expireTime = 0) {
		if (empty($uname)) {
			return false;
		}
		
		$prepare = array(
			'conditions' => array(
				'name' => $uname,
				'permission' => $permission
				),
			'limit' => '1'
			);
		$permExist = $this->findFirst($prepare);
		if ($permExist) {
			if ($permExist->expiry_date > 0 && $permExist->expiry_date < $expireTime) {
				$permExist->expiry_date = $expireTime;
				return $this->save($permExist);
			}
			return false;
		}
		
		$data = new stdClass();
		$data->name = $uname;
		$data->type = 1;
		$data->permission = $permission;
		$data->expiry_date = $expireTime;
		return $this->save($data);
	}
}