<?php
/**
* @title Event
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description Action recent, enregistrement, poste, discution
* @package Event
*/
Class EventModel extends Model{

public function install()
{
	$this->query("
	CREATE TABLE IF NOT EXISTS `" . __SQL . "_Event` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `descri` varchar(256) NOT NULL,
	  `time` int(11) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
	");
}




public function push($desc)
{
	$data			= new stdClass();
	$data->descri	= $desc;
	$data->time		= time();
	return $this->save($data);
}

}
?>