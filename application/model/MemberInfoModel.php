<?php
/**
* @title Connection
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description 
*/
Class MemberInfoModel extends Model{
	
	public function install()
	{
		$this->query("CREATE TABLE IF NOT EXISTS `".__SQL."_MemberInfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `website` varchar(256) NOT NULL,
  `location` varchar(256) NOT NULL,
  `job` varchar(256) NOT NULL,
  `leisure` varchar(256) NOT NULL,
  `sign` text NOT NULL,
  `bio` text NOT NULL,
  `sex` enum('z','x','y') NOT NULL,
  `birthday` int(11) NOT NULL,
  `thismember` int(11) NOT NULL,
  `avatar` varchar(256) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
	
	}
	
	
	public function changeInfo($data)
	{
//		var_dump($data);
		
		$findThisMEmber =  array(
			'conditions' => array('thismember' => (int) $data->thismember),
			'limit' => 1
			);
		
		$id = $this->findFirst($findThisMEmber);
//		var_dump($this->sql);
		if ($id)
		{
	//		var_dump($id);	echo  'exist  ';
			$data->id = $id->id;
		}

		unset($data->day);
		unset($data->month);
		unset($data->year);
		return $this->save($data);
		
	}

}