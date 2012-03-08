<?php
Class Member extends Model{
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
			'message' => "Vous devez préciser votre e-mail"
		)
	);	
	
	public function install()
	{
	$this->query("CREATE TABLE IF NOT EXISTS `".__SQL."_Member` (
  `idmember` int(11) NOT NULL auto_increment,
  `loginmember` varchar(50) NOT NULL,
  `passmember` varchar(256) NOT NULL,
  `mailmember` varchar(256) NOT NULL,
  `validemember` enum('on','off') NOT NULL default 'off',
  `levelmember` int(1) NOT NULL default '1',
  `groupmember` text NOT NULL,
  `firstactivitymember` int(11) NOT NULL,
  `lastactivitymember` int(11) NOT NULL,
  `hash_validation` varchar(255) NOT NULL,
  PRIMARY KEY  (`idmember`),
  UNIQUE KEY `loginmember` (`loginmember`,`mailmember`),
  UNIQUE KEY `loginmember_2` (`loginmember`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `".__SQL."_MemberGroup` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
");

	}
	
	public function checkLogin($data)
	{
	$sql = array('conditions' => array(
				'loginmember' => $data->loginmember,
				'passmember' => $this->genPass($data->loginmember, $data->passmember)
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
	
	public function clean($string)
	{
	$cleaner = strtr($string, 
	'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
	'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
	return preg_replace('/([^\.a-z0-9]+)/i', '-', $cleaner);
	}
	
	public function changePassword($id, $login, $password)
	{
	$data = new stdclass();
	$data->idmember = $id;
	$data->passmember =  $this->genPass($login, $password);
	return $this->save($data);
	}
	
	public function genPass($login, $pass)
	{
	return Securite::Hcrypt(strtolower($login).$pass);	
	}
	
	
	/* Recherche par e-mail	
	***********************/
	public function searchMemberByMail($mail)
	{
	$mail = filter_var($mail, FILTER_VALIDATE_EMAIL);
		if ($mail)
		{
		$req = array(
			'conditions' => array('mailmember' => strtolower($mail))
			);
			
			if ($result = $this->findFirst($req))
			{
				return $result;
			}
		}
	
	return false;
	}	// END searchMemberByMail
	
	
	
	/* Recherche par login
	***********************/
	public function searchMemberByLogin($login)
	{

	$req = array(
		'conditions' => array('loginmember' => strtolower($login))
		);
		
	if ($result = $this->findFirst($req)) {
		return $result;
	}
	
	return false;
	}	// END searchMemberByLogin
	
	
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
		$data->levelmember = 2;
		$data->hash_validation = '';
		$data->lastactivitymember = time();
		return $this->save($data);
		
		}
		return false;
	}
}
?>