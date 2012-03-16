<?php
Class ManuelModel extends Model{

	var $validate = array(
		'titre' => array(
			'rule' => 'notEmpty',
			'message' => 'Vous devez préciser un titre'
		),
		'content' => array(
			'rule' => 'notEmpty',
			'message' => 'Vous devez préciser un contenu'
		),		
	);
	
	//categorieid	titre	content	date	hit	online	type
	public $config;
	public $type;
	public $page=1;
	public $id_auteur;
	
	
	public function install()
	{
	
	$this->query("
CREATE TABLE IF NOT EXISTS `".__SQL."_Manuel` (
  `id` int(11) NOT NULL auto_increment,
  `id_auteur` int(11) NOT NULL,
  `categorieid` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `date` int(11) NOT NULL,
  `hit` bigint(20) NOT NULL default '0',
  `online` enum('n','y') NOT NULL default 'n',
  `type` varchar(55) NOT NULL default 'manuel',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `".__SQL."_ManuelCat` (
  `idcategorie` int(11) NOT NULL auto_increment,
  `categorie` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY  (`idcategorie`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `".__SQL."_ManuelCommentaires` (
  `id` int(11) NOT NULL auto_increment,
  `pseudo` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `Cdate` int(11) NOT NULL,
  `id_manuel` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `mail` varchar(255) default NULL,
  `website` varchar(255) default NULL,
  `valide` enum('n','y','s') NOT NULL default 'n' COMMENT 'n = non valide, y = valide, s = spam',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
	");
	}
	
	function getManuelList($categorieId = NULL)
	{
	$meManuelSql = array(
	'fields'
		=> "Manuel.id, Manuel.titre, Manuel.content, Manuel.date, COUNT(Commentaire.id) AS count, Cdate AS lastcomm, Member.loginmember AS auteur, Categorie.categorie, Categorie.description, Manuel.hit", 
	'conditions'
		=> array('online' => 'y', 'type' => $this->type),
	'limit'	
		=> ($this->config['postParPage']*($this->page-1)).','.$this->config['postParPage'],
	'join'
		=>	array(__SQL . '_Member AS Member' => 'Manuel.id_auteur = Member.idmember',
							__SQL . '_ManuelCat AS Categorie' => "Manuel.categorieid = Categorie.idcategorie",
							__SQL . "_ManuelCommentaires AS Commentaire" => "Manuel.id = Commentaire.id_Manuel AND Commentaire.valide = 'y'"),
	'group'			=> 'Manuel.id',
	'order'			=> 'Manuel.id DESC'
	);
	
		if (!is_null($categorieId))
		{
		$meManuelSql['conditions'] = array('online' => 'y', 'type' => $this->type, 'categorieid' => $categorieId);
		}
		
	return  $this->find($meManuelSql);
	}
	
	
	
	public function getManuel($id, $online='y')
	{
	$find = array(
		'conditions' => array('id' => $id, 'online' => $online, 'type' => $this->type),
		'fields'     => 'id, id_auteur, titre, content, date, categorieid, categorie, COUNT(Category.idcategorie) AS count',
		'join'       => array(__SQL . '_ManuelCat as Category' => 'Category.idcategorie = categorieid')
	);
	
	if ($online == '*' OR $online == '?')
	{
		$find['conditions'] = array('id' => $id);
	}
	// = ($online == '*' OR $online == '?') ? array('id' => $id, 'type' => $this->type) : array('id' => $id, 'online' => $online, 'type' => $this->type);
	return $this->findFirst($find);
	}

	
	
	public function add($data, $id=NULL)
	{
		if (!is_null($id))
		{
		$data->date = time();
		$data->id_auteur = $this->id_auteur;
		$data->id = $id;
		}
	return $this->save($data);
	}
	
	
	public function isAuthor($id, $idAuthor){
	$find = array(
		'conditions' => array('id' => $id,'id_auteur' => $idAuthor)
		);
	return (bool) $this->findFirst($find);
	}
}
?>