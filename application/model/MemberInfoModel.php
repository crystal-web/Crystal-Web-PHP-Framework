<?php
/**
* @title Connection
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description 
*/
Class MemberInfoModel extends Model{
	

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