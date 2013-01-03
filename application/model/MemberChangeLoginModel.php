<?php
/**
* @title Connection
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description 
*/
Class MemberChangeLoginModel extends Model{
	public function install()
	{
	$this->query("CREATE TABLE IF NOT EXISTS `".__SQL."_Member` (
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

CREATE TABLE `".__SQL."_MemberChangeLogin` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`idmember` INT( 11 ) NOT NULL ,
`newlogin` VARCHAR( 256 ) NOT NULL ,
`raison` VARCHAR( 256 ) NOT NULL ,
`time` INT( 11 ) NOT NULL ,
PRIMARY KEY (  `id` )
) ENGINE = MYISAM ;
");
	}

	var $validate = array(
		'pseudo' => array(
			'rule' => 'notEmpty',
			'message' => 'Vous devez préciser votre nouveau pseudo'
		),
		'raison' => array(
			'rule' => 'notEmpty',
			'message' => "Vous devez préciser une raison valable"
		)
	);
	
}

?>