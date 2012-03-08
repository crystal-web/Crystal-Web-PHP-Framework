<?php
/**
* @title Connection
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description 
*/

Class Acl extends Model {

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


}
?>