<?php
Class ConfigModel extends Model {

	/**
	 * 
	 * Installation automatique
	 */
	public function install() {
		$this->query("CREATE TABLE ".__SQL."_Config (
			`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
			`controller` VARCHAR( 256 ) NOT NULL ,
			`name` VARCHAR( 256 ) NOT NULL ,
			`config` TEXT NOT NULL ,
			PRIMARY KEY (  `id` )
			) ENGINE = MYISAM ;");
	}

	/**
	 * 
	 * Recherche dans la base de donnée
	 * la configuration du controller
	 * @param string $controller
	 * @param string $name
	 * @return object stdClass
	 */
	public function getConfig($controller, $name) {
		$f = array(
			'conditions' => array(
				'controller' => $controller,
				'name' =>  $name),
			'fields' => 'id, config',
			'limit' => 1
			);
		$resu = $this->findFirst($f);
		if ($resu) {
			$resu->config = unserialize($resu->config);
		}
		return $resu;
	}

	/**
	 * 
	 * Enregistre dans la base de donnée
	 * la configuration du controller
	 * @param string $controller
	 * @param string $name
	 * @return object stdClass
	 */
	public function setConfig($controller, $name, $config) {
		$dataToSave = new stdClass();
		$resu = $this->getConfig($controller, $name);
		if ($resu) {
			$dataToSave->id = $resu->id;
		}
		
		$dataToSave->config = serialize($config);
		$dataToSave->controller = $controller;
		$dataToSave->name = $name;
		return $this->save($dataToSave);
	}
}