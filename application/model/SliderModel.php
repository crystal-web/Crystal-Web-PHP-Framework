<?php
class SliderModel extends Model{
	var $validate = array(
		'title' => array(
			'rule' => 'notEmpty',
			'message' => 'Vous devez préciser un titre',
		),
		'description' => array(
			'rule' => 'notEmpty',
			'message' => 'Vous devez préciser une description',
		),	
		'link' => array(
			'rule' => 'notEmpty',
			'message' => 'Vous devez préciser un lien valide',
			'callback' => 'isURL'
		),
		'image' => array(
			'rule' => '(?:jpe?g|png|gif)$',
			'message' => 'Vous devez préciser une image valide',
		)	
		);

public function install()
{
$this->query("
CREATE TABLE IF NOT EXISTS `".__SQL."_Slider` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `active` enum('y','n') NOT NULL default 'y',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
");

}
}
?>