<?php
/**
* @title Connection
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description 
*/

Class AclModel extends Model {

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
");
}


public function test()
{/*
	$this->table = 'git__AclInheritance';
	
	$prepare = array(
			'join' => array(	
				'git__AclPermission' => 'git__AclPermission.name = Acl.parent',
				),
			'conditions' => 'Acl.child = "Moderateur" OR git__AclPermission.name = "Moderateur"',
		);

		
	debug( $this->find($prepare) );
	
	debug($this->sql);
	debug($this->lastError);//*/

	
	
	
}

}


?>