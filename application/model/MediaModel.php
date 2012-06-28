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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
");
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

public function getId($id)
{
	$id = (int) $id;
	
	$p = array(
		'fields' => '*, CONCAT( SUBSTRING_INDEX( mime,  \''.$type.'/\', -1 ) ) AS subType',
		'conditions' => array('id' => $id),
		'join' => array(__SQL . '_Member AS Member' => 'Member.idmember = Media.id_member'),
		);
	
	
	return $this->findFirst($p);
}

public function getList()
{
$p = array(
	'fields' => '*, CONCAT( SUBSTRING_INDEX( mime,  \''.$type.'/\', -1 ) ) AS subType',
	'order' => 'name ASC'
	);
	
return $this->find($p);
}

public function getListByType($type)
{
$p = array(
	'fields' => '*, CONCAT( SUBSTRING_INDEX( mime,  \''.$type.'/\', -1 ) ) AS subType',
	'conditions' => array('type' => $type),
	'order' => 'name ASC'
	);
	
return $this->find($p);
}

public function getListBySubType($mime)
{
$p = array(
	'fields' => '*, CONCAT( SUBSTRING_INDEX( mime,  \''.$type.'/\', -1 ) ) AS subType',
	'conditions' => array('mime' => $mime),
	'order' => 'name ASC'
	);
	
return $this->find($p);
}

}
