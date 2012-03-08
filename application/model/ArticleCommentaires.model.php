<?php
Class ArticleCommentaires extends Model{

	var $validate = array(
 
		'mail' => array(
			'rule' => 'isMail',
			'message' => 'Vous devez préciser une adresse e-mail valide'
		),
		'content' => array(
			'rule' => 'noEmpty',
			'message' => 'Votre commentaire n\'a aucun contenu'
		),
		'pseudo' => array(
			'rule' => 'noEmpty',
			'message' => 'Veuillez pr&eacute;ciser votre pseudo ou pr&eacute;nom'
		),
	);
	
	
	public function install()
	{
	
	$this->query("
CREATE TABLE IF NOT EXISTS `".__SQL."_Article` (
  `id` int(11) NOT NULL auto_increment,
  `id_auteur` int(11) NOT NULL,
  `categorieid` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `date` int(11) NOT NULL,
  `hit` bigint(20) NOT NULL default '0',
  `online` enum('n','y') NOT NULL default 'n',
  `type` varchar(55) NOT NULL default 'article',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `".__SQL."_ArticleCat` (
  `idcategorie` int(11) NOT NULL auto_increment,
  `categorie` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY  (`idcategorie`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `".__SQL."_ArticleCommentaires` (
  `id` int(11) NOT NULL auto_increment,
  `pseudo` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `Cdate` int(11) NOT NULL,
  `id_article` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `mail` varchar(255) default NULL,
  `website` varchar(255) default NULL,
  `valide` enum('n','y','s') NOT NULL default 'n' COMMENT 'n = non valide, y = valide, s = spam',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
	");
	}
	
	/**
	* Compte les commentaires
	*
	* @param id | identifiant de la article
	* @param valide | etat du commentaire n = non valide, y = valide, s = spam
	*/
	public function comCount($id, $valide='y')
	{
	return $this->findCount(array('valide' => $valide, 'id_article' => $id));
	}
	
	/**
	* Recherche et retourne les commentaires
	*
	* @param id | identifiant de la article
	* @param valide | etat du commentaire n = non valide, y = valide, s = spam
	* @param start | extrai a partier de x default=0
	* @param stop | extrait x premier default=10
	* @param order | ASC ou DESC default DESC
	*/
	public function getCom($id, $valide = 'y', $start = 0, $stop = 10, $ordre='DESC')
	{
	$sql = array (
		'fields' => 'id, pseudo, content, ip, Cdate, mail, website',
		'conditions' => array('valide' => $valide, 'id_article' => $id),
		'order' => 'id '.$ordre,
		'limit' => $start.', '.$stop
		);
	return $this->find($sql);	
	}
	
	public function getAll($valide='y')
	{
	$find = array(
		'conditions' => array('valide' => $valide),
		);
	
	return $this->find($find);
	}
	
	public function changeStatut($id, $valide)
	{
	$req = new stdClass();
	$req->id = $id;
	$req->valide = $valide;
	$this->save($req);
	}
	
	
	public function add($data)
	{
	$req = new stdClass();
	$req->pseudo = $data->pseudo;
	$req->content = $data->content;
	$req->id_article = $data->id_article;
	$req->ip = Securite::ipX();
	$req->mail = $data->mail;
	$req->website = $data->website;
	$req->valide = 'n';
	$req->Cdate = time();
	
	return $this->save($req);
	
	}
}