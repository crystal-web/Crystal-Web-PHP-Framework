<?php 
Class AclInheritanceModel extends Model {
	
	public function install()
	{
		$this->query(" 
			CREATE TABLE IF NOT EXISTS `" . __SQL . "_AclInheritance` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `child` varchar(256) NOT NULL,
			  `parent` varchar(256) NOT NULL,
			  `default` enum('n','y') NOT NULL DEFAULT 'n',
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

			INSERT INTO `".__SQL."_AclInheritance` (`id`, `child`, `parent`, `default`) VALUES
			(NULL, 'Membre', '*', 'y'),
			(NULL, 'Moderateur', 'Membre', 'n'),
			(NULL, 'Administrateur', 'Moderateur', 'n');
			
			CREATE TABLE IF NOT EXISTS `".__SQL."_AclPermission` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(256) NOT NULL,
			  `permission` varchar(256) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
			
			
			INSERT INTO `".__SQL."_AclPermission` (`id`, `name`, `permission`) VALUES (NULL, 'Administrateur', '*');
		");
	}
	
	
	public function findDefault()
	{
		$resp = $this->findFirst(array(
			'conditions' => array(
				'default' => 'y'
				)
			)
			);
		return ($resp) ? $resp->child : false;
	}
	
	public function getGroupList()
	{
		return $this->find(array('fields' => 'child'));
	}
}
