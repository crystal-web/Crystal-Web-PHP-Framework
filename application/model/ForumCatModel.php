<?php
/**
* @title Forum - Categorie
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description 
*/

Class ForumCatModel extends Model{

	var $validate = array(
		'name' => array(
			'rule' => 'notEmpty',
			'message' => 'Vous devez préciser un titre'
		),
		'description' => array(
			'rule' => 'notEmpty',
			'message' => 'Vous devez préciser une description'
		),		
	);
	
public function install()
{
$this->query("
CREATE TABLE IF NOT EXISTS `".__SQL."_ForumCat` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `".__SQL."_ForumPost` (
  `id` int(11) NOT NULL auto_increment,
  `topic_id` int(11) NOT NULL,
  `auteur` int(11) NOT NULL,
  `created_time` int(11) NOT NULL,
  `edited_time` int(11) NOT NULL default '0',
  `message` text NOT NULL,
  `ip` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `".__SQL."_ForumSujet` (
  `id` int(11) NOT NULL auto_increment,
  `cat_id` int(11) NOT NULL,
  `icone` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `last_post_id` int(11) NOT NULL default '0',
  `nb_topic` int(11) NOT NULL default '0',
  `nb_post` int(11) NOT NULL default '0',
  `auth_view` int(11) NOT NULL default '1',
  `auth_post` int(11) NOT NULL default '2',
  `auth_topic` int(11) NOT NULL default '2',
  `groupid` int(3) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `".__SQL."_ForumTopic` (
  `id` int(11) NOT NULL auto_increment,
  `sujet_id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `sous_titre` varchar(255) NOT NULL,
  `auteur` int(11) NOT NULL,
  `hits` int(11) NOT NULL default '0',
  `created_time` varchar(255) NOT NULL,
  `is_annonce` enum('n','y') NOT NULL default 'n',
  `first_post_id` int(11) NOT NULL,
  `last_post_id` int(11) NOT NULL,
  `locked` enum('n','y') NOT NULL default 'n',
  `icone` varchar(255) NOT NULL,
  `nb_post` int(11) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `".__SQL."_ForumView` (
  `sujet_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
");
}


public function getCategorie($userLevel=1, $byId=FALSE, $groupList=FALSE)
{
$req=array(
	'fields' => '*,
		ForumSujet.name AS Sname,
		ForumSujet.id AS Sid,
		ForumSujet.nb_post AS Snb_post,
		ForumCat.name AS Cname,
		ForumCat.id AS Cid,
		ForumTopic.id AS Tid,
		ForumSujet.description AS Sdescription,
		ForumCat.description AS Cdescription,
		ForumTopic.nb_post AS Pnb_post,
		Member.loginmember AS Alogin
		',
		
	'conditions' => 'ForumSujet.auth_view <= '.$userLevel.'',
	'join'		=> array(
				__SQL .'_ForumSujet AS ForumSujet'	=> 'ForumCat.id = ForumSujet.cat_id',
				__SQL .'_ForumPost AS ForumPost'		=> 'ForumSujet.last_post_id = ForumPost.id',
				__SQL .'_ForumTopic AS ForumTopic'		=> 'ForumPost.topic_id = ForumTopic.id',
				__SQL .'_Member AS Member'				=> 'ForumPost.auteur = Member.idmember'),
	'order'		=> 'cat_id ASC',
	);

/***************************************
*	Affiche les sujet, y compris, ceux réservé
***************************************/
if ($groupList && $groupList[0] != '*')
{

$in = ' OR groupid IN ( \''.implode("','", $groupList).'\')';
$req['conditions'] = 'ForumSujet.auth_view <= '.$userLevel.''.$in;
	if ($byId)
	{
	$req['conditions'] = 'ForumSujet.auth_view <= '.$userLevel .' '.$in.' AND ForumCat.id = '.$byId;
	}
}
/***************************************
*	Affiche tout les sujets
***************************************/
elseif ($groupList && $groupList[0] == '*')
{
unset($req['conditions']);
	if ($byId)
	{
	$req['conditions'] = 'ForumCat.id = '.$byId;
	}
}



return $this->find($req); 
}

public function getCategory()
{
$req = array(
	'fields' => 'id, name, description',
	);
return $this->find($req);
}

}
?>