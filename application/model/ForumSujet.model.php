<?php
/**
* @title Forum - Sujet
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description 
*/

Class ForumSujet extends Model{
public $sujetParPage = 10;
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

public function getSujet($userLevel=1, $byId, $groupList, $page=1)
{

$req=array(
	'fields' => '*,
				Member.loginmember AS Alogin,
				MemberLast.loginmember AS Rlogin,
				
				ForumSujet.description AS Sdescription,
				ForumSujet.name AS Sname,
				ForumSujet.id AS Sid,
				
				ForumCat.description AS Cdescription,
				ForumCat.name AS Cname,
				ForumCat.id AS Cid,
				
				ForumTopic.id AS Tid
				',

	'join'			=> array(__SQL .'_ForumCat AS ForumCat' => 'ForumSujet.cat_id = ForumCat.id',
						__SQL .'_ForumTopic AS ForumTopic' => 'ForumTopic.sujet_id = ForumSujet.id',
						__SQL .'_ForumPost AS ForumPost' => 'ForumSujet.last_post_id = ForumPost.id',
						__SQL .'_Member AS MemberLast' => 'MemberLast.idmember = ForumPost.auteur',
						__SQL .'_Member AS Member' => 'Member.idmember = ForumTopic.auteur',
						//__SQL .'_Member AS MemberLast' => 'MemberLast.idmember = ForumPost.auteur',
						),
						
	'conditions' 	=> 'ForumSujet.auth_view <= '.$userLevel.' AND ForumSujet.id = '.$byId,
	'limit'			=> ($this->sujetParPage *($page-1)).','.$this->sujetParPage 
	);

/***************************************
*	Affiche les sujet, y compris, ceux réservé
***************************************/
if ($groupList && $groupList[0] != '*')
{
unset($req['conditions']);
$req['conditions'] = ' ( 
ForumSujet.auth_view <= '.$userLevel.' OR groupid IN ( \''.implode("','", $groupList).'\') ) AND ForumSujet.id = '.$byId;
}
/***************************************
*	Affiche tout les sujets
***************************************/
elseif ($groupList && $groupList[0] == '*')
{
unset($req['conditions']);
	if ($byId)
	{
	$req['conditions'] = 'ForumSujet.id = '.$byId;
	}
}



return $this->find($req); 
} 





public function updatLastPostId($sujetId, $postId)
{
$data = new stdClass();
$data->id = (int) $sujetId;
$data->last_post_id = (int) $postId;
$data->nb_post = 'nb_post+1';
return $this->save($data);
}


public function addTopic($sujetId, $postId)
{
$data = new stdClass();
$data->id = (int) $sujetId;
$data->last_post_id = (int) $postId;
$data->nb_topic = 'nb_topic+1';
return $this->save($data);
}


public function addSujet($data)
{
return $this->save($data);
}


}
?>