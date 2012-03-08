<?php
/**
* @title Forum
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description 
*/

Class ForumTopic extends Model {

	var $validate = array(
		'titre' => array(
			'rule' => 'notEmpty',
			'message' => 'Vous devez préciser un titre'
		),
		'message' => array(
			'rule' => 'notEmpty',
			'message' => "Vous devez préciser un message"
		)
	);
	
private $postParPage = 10;

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

public function getListPost($userLevel, $id, $page, $order='ASC', $groupList=false)
{
$req=array(
	'fields' => '*,
		Member.loginmember AS login,
		ForumPost.id AS postId,
		ForumTopic.id AS topicId,
		ForumSujet.id AS  Sid,
		ForumSujet.name AS  Sname,
		ForumSujet.description AS  Sdescription,
		
		ForumTopic.nb_post AS Tnb_post,
		
		ForumCat.id AS  Cid,
		ForumCat.name AS  Cname,
		ForumCat.description AS  Cdescription
		',

	'join'	 => array(
		__SQL .'_ForumPost AS ForumPost'	=> 'ForumPost.topic_id = ForumTopic.id',
		__SQL .'_ForumSujet AS ForumSujet' => 'ForumSujet.id = ForumTopic.sujet_id',
		__SQL .'_ForumCat AS ForumCat'		=> 'ForumCat.id = ForumSujet.cat_id',
		__SQL .'_Member AS Member'			=> 'Member.idmember = ForumPost.auteur'
		),
						
	'conditions'						=> ' ForumTopic.id = '.$id.' AND auth_view <= '.$userLevel,
	'order'								=> 'ForumPost.id '.$order,
	'limit'								=> ($this->postParPage *($page-1)).','.$this->postParPage 
	);




/***************************************
*	Affiche les sujet, y compris, ceux réservé
***************************************/
if ($groupList && $groupList[0] != '*')
{
unset($req['conditions']);
$req['conditions'] = ' ( 
ForumSujet.auth_view <= '.$userLevel.' OR groupid IN ( \''.implode("','", $groupList).'\') ) AND ForumTopic.id = '.$id;
}
/***************************************
*	Affiche tout les sujets
***************************************/
elseif ($groupList && $groupList[0] == '*')
{
unset($req['conditions']);
$req['conditions'] = 'ForumTopic.id = '.$id;
}

	
	
return $this->find($req); 
}


public function updatLastPostId($topicId, $postId)
{
$data = new stdClass();
$data->id = (int) $topicId;
$data->last_post_id = (int) $postId;
$data->nb_post = 'nb_post+1';
return $this->save($data);
}


public function addTopic($data)
{
return $this->save($data);
}
}
?>