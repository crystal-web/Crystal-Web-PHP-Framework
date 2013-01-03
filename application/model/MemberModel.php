<?php
Class MemberModel extends Model{
	public $primaryKey = 'idmember';
	var $validate = array(
		'loginmember' => array(
			'rule' => 'notEmpty',
			'message' => 'Vous devez préciser votre login'
		),
		'passmember' => array(
			'rule' => 'notEmpty',
			'message' => "Vous devez préciser votre mot de passe"
		)
	);
	
	var $subscribe = array(
		'loginmember' => array(
			'rule' => 'notEmpty',
			'message' => 'Vous devez préciser votre login'
		),
		'passmember' => array(
			'rule' => 'notEmpty',
			'message' => "Vous devez préciser votre mot de passe"
		),
		'mailmember' => array(
			'rule' => 'isMail',
			'message' => "Vous devez préciser une adresse e-mail valide"
		)
	);	
	
	public function install()
	{
	$this->query("
		CREATE TABLE IF NOT EXISTS `".__SQL."_Member` (
		  `idmember` int(11) NOT NULL AUTO_INCREMENT,
		  `loginmember` varchar(50) NOT NULL,
		  `password` varchar(256) NOT NULL,
		  `mailmember` varchar(256) NOT NULL,
		  `validemember` enum('on','off') NOT NULL DEFAULT 'off',
		 
		  `groupmember` text NOT NULL,
		  `firstactivitymember` int(11) NOT NULL,
		  `lastactivitymember` int(11) NOT NULL,
		  `hash_validation` varchar(255) NOT NULL,
		  `ip` varchar(256) NOT NULL,
		  `warning` tinyint(3) NOT NULL DEFAULT '0',
		  `hasban` enum('n','y') NOT NULL DEFAULT 'n',
		  PRIMARY KEY (`idmember`),
		  UNIQUE KEY `loginmember` (`loginmember`,`mailmember`),
		  UNIQUE KEY `loginmember_2` (`loginmember`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;
		
		
		CREATE TABLE IF NOT EXISTS `".__SQL."_MemberInfo` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `website` varchar(256) NOT NULL,
		  `location` varchar(256) NOT NULL,
		  `job` varchar(256) NOT NULL,
		  `leisure` varchar(256) NOT NULL,
		  `sign` text NOT NULL,
		  `bio` text NOT NULL,
		  `sex` enum('z','x','y') NOT NULL,
		  `birthday` int(11) NOT NULL,
		  `thismember` int(11) NOT NULL,
		  `avatar` varchar(256) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
		
		CREATE TABLE  `".__SQL."_MemberActu` (
		`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
		`auteur` INT( 11 ) NOT NULL ,
		`time` INT( 11 ) NOT NULL ,
		`actu` VARCHAR( 256 ) NOT NULL ,
		PRIMARY KEY (  `id` )
		) ENGINE = MYISAM ;
		");

	}
	
	public function clean($string)
	{
	    $str = preg_replace('#\&([A-Za-z]{2})(?:lig)\;#', '\1', $string);
	    $str = str_replace("'", '', $str);
	    $str = str_replace(' ', '', $str);
	    $str = preg_replace('#[^A-Za-z0-9_]#', '', $str);
		$str = htmlentities($string, ENT_NOQUOTES, 'utf-8');
	    return $str;
	}
	
	
	public function checkLogin($data)
	{					
		// On recherche l'utilisateur dans la base de donnée
		$sql = array(
			'conditions' => array(
					'loginmember'	=> $data->loginmember,
					/*'password'	=> md5(magicword . $data->passmember),
					'validemember'	=> 'on'*/
			));
		return $this->findFirst($sql);
	}
	
	public function lastActivity($id)
	{
		//$req = $pdo->prepare("UPDATE  `" . __SQL . "_member` SET  `lastactivitymember` =  '" . time() . "' WHERE  `" . __SQL . "_member`.`idmember` =:idmember LIMIT 1 ;");
		$data = new stdclass();
		$data->idmember = $id;
		$data->lastactivitymember =  time();
		return $this->save($data);
	}
	

	public function findInListLogin($login, $page, $nbParPage = 30, $onlyValidate = false)
	{
		$page = (int) $page;
		$nbParPage = (int) $nbParPage;
		
		$start = ($page - 1) * $nbParPage;
		$prepare = array(
			'join' => array(__SQL . '_MemberInfo AS Info' => 'Info.thismember = Member.idmember'),
			'limit' => $start . ', ' . $nbParPage,
			'order' => 'idmember ASC',
			'conditions' => array('loginmember' => '%' . $login . '%')
			);
		
		if ($onlyValidate)
		{
			$prepare['conditions'] = array('valiatemember' => $onlyValidate);
		}
		
		return $this->find($prepare);
	}
	
	public function findInListGroup($group, $page, $nbParPage = 30, $onlyValidate = false)
	{
		$page = (int) $page;
		$nbParPage = (int) $nbParPage;
		
		$start = ($page - 1) * $nbParPage;
		$prepare = array(
			'join' => array(__SQL . '_MemberInfo AS Info' => 'Info.thismember = Member.idmember'),
			'limit' => $start . ', ' . $nbParPage,
			'order' => 'idmember ASC',
			'conditions' => array('groupmember' => '%' . $group . '%')
			);
		
		if ($onlyValidate)
		{
			$prepare['conditions'] = array('valiatemember' => $onlyValidate);
		}
		
		return $this->find($prepare);
	}
	
	public function getList($page, $nbParPage = 30, $onlyValidate = false)
	{
		$page = (int) $page;
		$nbParPage = (int) $nbParPage;
		
		$start = ($page - 1) * $nbParPage;
		$prepare = array(
			'join' => array(__SQL . '_MemberInfo AS Info' => 'Info.thismember = Member.idmember'),
			'limit' => $start . ', ' . $nbParPage,
			'order' => 'idmember ASC'
			);
		
		if ($onlyValidate == 'on' OR $onlyValidate == 'off')
		{
			$prepare['conditions'] = array('validemember' => $onlyValidate);
		}
		
		return $this->find($prepare);
	}
	
	public function changePassword($id, $password)
	{
	$data = new stdclass();
	$data->idmember = $id;
	$data->password =  md5(magicword . $password);
	$data->validemember = 'on';
	$data->hash_validation = '';
	
	return $this->save($data);
	}
	
	
	
	private function sendMailValidation($user)
	{
		 
	}
	
	
	/*********************************************/
	/* Verifie le hash membre/client			 */
	/*********************************************/
	public function checkHash($hash)
	{
		if ($respon = $this->findFirst(array('conditions' => array('hash_validation' => $hash))))
		{
		$this->primaryKey = 'idmember';
		$data = new stdClass();
		$data->idmember = $respon->idmember;
			if ((int) $respon->idmember === 1)
			{
			$data->groupmember = '*';
			}
		$data->validemember = 'on';
		$data->hash_validation = '';
		// Toujours d'actu ??
		$data->password = preg_replace('#\$devphp#', '', $respon->password);
		$data->lastactivitymember = time();
		return $this->save($data);
		
		}
		return false;
	}
	
	
	
	
	/**
	 * Recherche
	 */
	
	
	/**
	 * Recherche par e-mail	
	 */
	public function searchMemberByMail($mail)
	{
	$mail = filter_var($mail, FILTER_VALIDATE_EMAIL);
		if ($mail)
		{
		$req = array(
			'conditions' => array('mailmember' => strtolower($mail))
			);
			
			$result = $this->findFirst($req);
			if ($result )
			{
				return $result;
			}
		}
	
	return false;
	}
	
	
	/**
	 * Recherche par login
	 */
	public function searchMemberByLogin($login)
	{

	$req = array(
		'conditions' => array('loginmember' => $login)
		);
		
	$result = $this->findFirst($req);
	if ($result ) {
		return $result;
	}
	
	return false;
	}	// END searchMemberByLogin
	
	/**
	 * END Recherche
	 */
	
	
	/**
	 * Multicompte
	 */
	
	public function countMultiAccount($onlyValidateAccount = false)
	{
		if ($onlyValidateAccount) {
			$query = array(
			    'fields' => 'COUNT( * ) AS db',
			    'group' => 'ip HAVING COUNT( * ) > 1',
			    'conditions' => array('validemember' => 'on')
			    );
		}
		else {
			$query = array(
			    'fields' => 'COUNT( * ) AS db',
			    'group' => 'ip HAVING COUNT( * ) > 1'
			    );	
		}
		
		return $this->findFirst($query);
	}
	
	
	public function getMultiAccount($ip = false)
	{
		if ($ip) {
			$query = array(
			    'fields' => 'idmember, loginmember, mailmember, firstactivitymember, lastactivitymember, ip, alidemember',
			    'conditions' => array('Member.ip' => $ip),
			    );
		}
		else {
			$query = array(
			    'fields' => 'idmember, loginmember, mailmember, firstactivitymember, lastactivitymember, ip, validemember',
			    'group' => 'ip HAVING COUNT( * ) > 1'
			    );			
		}

		return $this->find($query);
	}
	
	/**
	 * END Multicompte
	 */
	 
	 public function addmember($data)
	 {
		//debug($data);
		$member = new stdClass();
		$member->loginmember = $this->clean($data->loginmember);
		$member->password = md5(magicword . $data->passmember2);
		$member->mailmember = $data->mailmember;
		$member->validemember = 'on';
		//$member->groupmember
		$member->firstactivitymember = time();
		$member->lastactivitymember = time();
		return $this->save(	$member );
	 }
	 
	 
	 public function lastMember()
	 {
	 	$prepare = array(
			'order' => 'idmember DESC',
			'limit' => '30'
			);
		return $this->find($prepare);
	 }


	public function purge($month = 1)
	{
		$time = time() - ($month * 2629743);
		$sql = "DELETE FROM `{$this->table}` WHERE `{$this->table}`.`validemember` = 'off' AND `{$this->table}`.`lastactivitymember` < $time";
		$this->pdo->query ( $sql );
	}
}
?>