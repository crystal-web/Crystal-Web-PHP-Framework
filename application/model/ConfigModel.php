<?php
Class ConfigModel extends Model {

	/**
	 * 
	 * Installation automatique
	 */
	public function install()
	{
		$this->query("CREATE TABLE ".__SQL."_Config (
			`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
			`controller` VARCHAR( 256 ) NOT NULL ,
			`action` VARCHAR( 256 ) NOT NULL ,
			`params` TEXT NOT NULL ,
			PRIMARY KEY (  `id` )
			) ENGINE = MYISAM ;");
	}
	

	/**
	 * 
	 * Recherche dans la base de donnée
	 * la configuration du controller et de son action
	 * @param string $controller
	 * @param string $action
	 * @return object stdClass
	 */
	public function getConfig($controller, $action)
	{
		$f = array(
			'fields' => 'params, id',
			'conditions' => array(
				'controller' => $controller,
				'action' =>  $action
				)
			);
		$conf = $this->findFirst($f);
		if ($conf)
		{
			$conf->params = unserialize($conf->params);
			return $conf;
		} else {
			return false;
		}
	}
	
	public function setConfig($controller, $action, $config)
	{
		$hasConfig = $this->getConfig($controller, $action);
		
		$data = new stdClass();
		$data->params = serialize($config);
		$data->controller = $controller;
		$data->action = $action;
		
		if ($hasConfig)
		{
			$data->id = $hasConfig->id;
		}

		return $this->save($data);
	}
}