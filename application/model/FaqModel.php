<?php
/**
* @package Faq
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description Frequent Ask Question
*/
Class FaqModel extends Model {

public function install()
{
$this->query("CREATE TABLE IF NOT EXISTS `".__SQL."_Faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(256) NOT NULL,
  `reponse` text NOT NULL,
  `active` enum('on','off') NOT NULL DEFAULT 'on',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
}

}
?>