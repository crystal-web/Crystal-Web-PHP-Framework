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
	// Creation de la table
	$this->query("CREATE TABLE IF NOT EXISTS `".__SQL."_Media` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `name` varchar(250) NOT NULL,
	  `type` varchar(50) NOT NULL,
	  `mime` varchar(50) NOT NULL,
	  `id_member` int(11) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
	");

	// Injection du langage
	$this->loadLanguage();
}

public function loadLanguage()
{
	$langModel = $this->loadModel('Language');
	$fr = array();
	$fr['Drop your files here'] = 'D&eacute;posez vos fichiers ici';
	$fr['Browse'] = 'Parcourir';
	$fr['Browser'] = 'Navigateur';
	$fr['or'] = 'ou';
	$fr['Upload'] = 'T&eacute;l&eacute;chargez';
	$fr['Media library'] = 'M&eacute;diath&egrave;que';
	$fr['Library manager'] = 'M&eacute;diath&egrave;que manager';
	$fr['Missing parameter'] = 'Param&egrave;tre manquant';
	$fr['Do you really want to delete this file?'] = 'Voulez-vous vraiment supprimer ce fichier ?';
	$fr['Delete'] = 'Supprimer';
	$fr['Save'] = 'Sauvegarder';
	$fr['Saved file'] = 'Fichier sauvegard&eacute;';
	$langModel->addLanguage('fr', 'mediamanager', $fr);
	
	$en = array();
	$en['Drop your files here'] = 'Drop your files here';
	$en['Browse'] = 'Browse';
	$en['Browser'] = 'Browser';
	$en['or'] = 'or';
	$en['Upload'] = 'Upload';
	$en['Media library'] = 'Media library';
	$en['Library manager'] = 'Library manager';
	$en['Missing parameter'] = 'Missing parameter';
	$en['Do you really want to delete this file?'] = 'Do you really want to delete this file?';
	$en['Delete'] = 'Delete';
	$en['Save'] = 'Save';
	$en['Saved file'] = 'Saved file';
	$langModel->addLanguage('en', 'mediamanager', $en);
}
public function add($name, $mime, $id_member, $folder)
{
	list($type, $soustype) = explode('/', $mime);
	$type = clean($type, 'slug');
	$soustype = clean($soustype, 'slug');
	//id	name	type	mime
	$data = new stdClass();
	$data->folder		= $folder;
	$data->name			= $name;
	$data->type			= $type;
	
	$data->mime			= $type.'/'.$soustype;
	$data->id_member	= $id_member;
	$this->save($data);
	return $this->id;
}

public function getId($id)
{
	$id = (int) $id;
	
	$p = array(
		'fields' => '*, CONCAT( SUBSTRING_INDEX( mime,  type, -1 ) ) AS subType',
		'conditions' => array('id' => $id),
		'join' => array(__SQL . '_Member AS Member' => 'Member.idmember = Media.id_member'),
		);
	
	
	return $this->findFirst($p);
}

public function getList()
{
	$this->loadLanguage();
$p = array(
	'fields' => '*, CONCAT( SUBSTRING_INDEX( mime,  type, -1 ) ) AS subType',
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
	'fields' => '*, CONCAT( SUBSTRING_INDEX( mime,  type, -1 ) ) AS subType',
	'conditions' => array('mime' => $mime),
	'order' => 'name ASC'
	);
	
return $this->find($p);
}

}
