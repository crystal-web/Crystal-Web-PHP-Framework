<?php
Class MediaModel extends Model{
var $validate = array(
		'name' => array(
			'rule' => 'notEmpty',
			'message' => 'Vous devez préciser un titre',
		)
	);

public function install()
{

$this->query("CREATE TABLE IF NOT EXISTS `".__SQL."_Media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `type` varchar(50) NOT NULL,
  `mime` varchar(50) NOT NULL,
  `id_member` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");

}	

public function add($name, $type, $mime, $id_member)
{
//id	name	type	mime
$data = new stdClass();
$data->name			= $name;
$data->type			= $type;
$data->mime			= $mime;
$data->id_member	= $id_member;
return $this->save($data);
}


}
