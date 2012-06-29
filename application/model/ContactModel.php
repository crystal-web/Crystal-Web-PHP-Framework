<?php
/**
* @title Contact
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description Mise en DB des formulaires de contact et validation du form
*/

Class ContactModel extends Model {

	var $validate = array(
		'firstname' => array(
			'rule' => 'notEmpty',
			'message' => 'Vous devez préciser votre prénom'
		),
		'lastname' => array(
			'rule' => 'notEmpty',
			'message' => 'Vous devez préciser votre nom'
		),
		'message' => array(
			'rule' => 'notEmpty',
			'message' => 'Vous devez préciser un message'
		),
		'mail' => array(
			'rule' => 'isMail',
			'message' => 'Vous devez préciser votre e-mail'
		),
		'motif' => array(
			'rule' => 'notEmpty',
			'message' => 'Vous devez préciser un motif'
		),
	);

	public function install()
	{
	$this->query("CREATE TABLE IF NOT EXISTS `".__SQL."_Contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(256) NOT NULL,
  `lastname` varchar(256) NOT NULL,
  `motif` varchar(256) NOT NULL,
  `mail` varchar(256) NOT NULL,
  `message` text NOT NULL,
  `ip` varchar(256) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
	
	}
}

?>