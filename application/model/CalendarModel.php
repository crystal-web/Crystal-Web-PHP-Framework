<?php
Class CalendarModel extends Model{

	var $validate = array(
		'heure' => array(
			'rule' => '([0-9]{1,2}:(00|30))',
			'message' => "L'heure n'est pas valide"
		),
		'resume' => array(
			'rule' => 'notEmpty',
			'message' => "Indiquez l'&eacute;venement"
		),
		'date' => array(
			'rule' => '([0-9]{1,4})-([0-9]{1,2})-([0-9]{1,2})',
			'message' => "La date n'est pas valide format yyyy-mm-dd"
		)
	);
	
	public function install()
	{
	$this->query("CREATE TABLE IF NOT EXISTS `".__SQL."_Calendar` (
  `id` int(11) NOT NULL auto_increment,
  `year` int(4) NOT NULL,
  `month` int(2) NOT NULL,
  `day` int(2) NOT NULL,
  `note` text NOT NULL,
  `heure` int(2) NOT NULL,
  `minute` int(2) NOT NULL,
  `label` enum('default','success','warning','important','notice') NOT NULL default 'default',
  `labelword` varchar(11) NOT NULL,
  `id_member` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
");
	
	}
	
	public function addEvent($data)
	{
	// Decoupage
	$date = array_map('intval', explode('-', $data->date) );
	$heure = array_map('intval', explode(':', $data->heure));
	
		$req = new stdClass();
		$req->year = $date[0];
		$req->month = $date[1];
		$req->day = $date[2];
		$req->resume = $data->resume;
		$req->heure = $heure[0];
		$req->minute = $heure[1];
		return $this->save($req);
	}

}






