<?php
class ArticleCatModel extends Model{
public $primaryKey = 'idcategorie';
public $type;

	var $validate = array(
		'categorie' => array(
			'rule' => 'notEmpty',
			'message' => 'Vous devez préciser le nom de la cat&eacute;gorie'
		),
		'description' => array(
			'rule' => 'notEmpty',
			'message' => 'Vous devez préciser une description'
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

public function getCategorie($online='y')
{

$categorieReq = array(
		'fields'		=> 'ArticleCat.categorie,  ArticleCat.idcategorie, COUNT( Article.id ) AS nb',
		'conditions'	=> array('Article.online' => $online, 'Article.type' => $this->type),
		//'limit'		=> ($perPage*($this->request->page-1)).','.$perPage,
		'join'			=> array(__SQL . '_Article as Article' => 'ArticleCat.idcategorie = Article.categorieid AND Article.online = \'y\''),
		'group'			=> 'idcategorie',
		'order'			=> 'idcategorie DESC'
	);
	
	if ($online == '*' or $online == '?')
	{
	unset($categorieReq['conditions']);
	}
return $this->find($categorieReq);
}

public function add($data)
{
	return $this->save($data);
}




}