<?php
Class MemberBruteforceModel extends Model {
	
	private $maximumTryIp = 15; // Nombre de tentativer possible
	private $timeToLockIp = 900; // Temps de blocage de l'ip en secondes
	
	public function install()
	{
		$this->query("
CREATE TABLE  `".__SQL."__MemberBruteforce` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`ip` VARCHAR( 256 ) NOT NULL ,
`try` INT( 2 ) NOT NULL ,
`time` INT( 11 ) NOT NULL ,
PRIMARY KEY (  `id` )
) ENGINE = MYISAM ;
				");
	}
	
	public function onLogin($user)
	{
		$this->cleanDb();
		
		$prepare = array('conditions' => 
					array('ip' => Securite::ipX())
				);
		$resp = $this->find($prepare);
		for ($i=0;$i<count($resp);$i++)
		{
			// time - 15 minutes
			if ($resp[$i]->time > (time() - $this->timeToLockIp))
			{
				// Si il y a trop d'essaye
				if ($resp[$i]->try >= $this->maximumTryIp)
				{
					// Trop d'essaye IP
					return false;
				}
			}
		}
		
		return true;
	}
	
	
	public function onFaild($user)
	{
		$prepare = array(
				'conditions' =>
				'`ip` = "'.Securite::ipX().'"'
				);
		$resp = $this->findFirst($prepare);
		if (!$resp)
		{
			$resp = new stdClass();
			$resp->try = 0;
		}
		$resp->ip = Securite::ipX();
		$resp->try = $resp->try + 1;
		$resp->time = time();
		$this->save($resp);
	}
	
	
	private function cleanDb()
	{
		$thisTime = time() - 7889231; /* 3 month in secondes */
		$this->query("DELETE FROM `{$this->table}` WHERE `{$this->table}`.`time` < $thisTime");
	}
	
	
	private function sendAlert($user)
	{
		// envois d'un mail avec un lien de deblocage, au cas ou il se serai bloqué lui-meme
	}
	
	
}