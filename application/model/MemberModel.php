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
CREATE TABLE IF NOT EXISTS `".__SQL."_Acl` (
  `id` int(11) NOT NULL auto_increment,
  `identifiant` int(11) NOT NULL,
  `controller` varchar(255) NOT NULL,
  `params` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `".__SQL."_Member` (
  `idmember` int(11) NOT NULL AUTO_INCREMENT,
  `loginmember` varchar(50) NOT NULL,
  `password` varchar(256) NOT NULL,
  `mailmember` varchar(256) NOT NULL,
  `validemember` enum('on','off') NOT NULL DEFAULT 'off',
  `levelmember` int(1) NOT NULL DEFAULT '1',
  `groupmember` text NOT NULL,
  `firstactivitymember` int(11) NOT NULL,
  `lastactivitymember` int(11) NOT NULL,
  `hash_validation` varchar(255) NOT NULL,
  `ip` varchar(256) NOT NULL,
  PRIMARY KEY (`idmember`),
  UNIQUE KEY `loginmember` (`loginmember`,`mailmember`),
  UNIQUE KEY `loginmember_2` (`loginmember`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `".__SQL."_MemberGroup` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE  `".__SQL."_MemberInfo` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`website` VARCHAR( 256 ) NOT NULL ,
`location` VARCHAR( 256 ) NOT NULL ,
`job` VARCHAR( 256 ) NOT NULL ,
`leisure` VARCHAR( 256 ) NOT NULL ,
`sign` TEXT NOT NULL ,
`bio` TEXT NOT NULL ,
`sex` ENUM(  'z',  'x',  'y' ) NOT NULL ,
`birthday` INT( 11 ) NOT NULL ,
`thismember` INT( 11 ) NOT NULL ,
PRIMARY KEY (  `id` )
) ENGINE = MYISAM ;

CREATE TABLE  `".__SQL."_MemberActu` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`auteur` INT( 11 ) NOT NULL ,
`time` INT( 11 ) NOT NULL ,
`actu` VARCHAR( 256 ) NOT NULL ,
PRIMARY KEY (  `id` )
) ENGINE = MYISAM ;

");

	}
	
	public function checkLogin($data)
	{
	$sql = array(
		'conditions' => array(
				'loginmember'	=> $data->loginmember,
				'password'	=> md5($data->passmember),
				'validemember'	=> 'on'
		));
	$user = $this->findFirst($sql);
		if (!empty($user->idmember))
		{
			$user->ip = Securite::ipX();
			$this->save($user);
		}
	return $user;
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
    
    $str = preg_replace('#\&([A-Za-z]{2})(?:lig)\;#', '\1', $string);
    $str = str_replace("'", '', $str);
    $str = str_replace(' ', '', $str);
    $str = preg_replace('#[^A-Za-z0-9_]#', '', $str);
	$str = htmlentities($string, ENT_NOQUOTES, 'utf-8');
    return $str;
	}
	
	public function changePassword($id, $password)
	{
	$data = new stdclass();
	$data->idmember = $id;
	$data->password =  md5($password);
	return $this->save($data);
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
			
			$result = $this->findFirst($req);
			if ($result )
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
		'conditions' => array('loginmember' => $login)
		);
		
	$result = $this->findFirst($req);
	if ($result ) {
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
			if ((int) $respon->idmember === 1)
			{
			$data->groupmember = '*';
			}
		$data->validemember = 'on';
		$data->levelmember = 2;
		$data->hash_validation = '';
		$data->password = preg_replace('#\$devphp#', '', $respon->password);
		$data->lastactivitymember = time();
		return $this->save($data);
		
		}
		return false;
	}
}
?>