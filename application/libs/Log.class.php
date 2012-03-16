<?php
/**
* @title Connection
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description 
*/
Class Log {


private $oCache;
private $arrCache = array();
private $logDate;
	
	public function __Construct()
	{
	$tmp = new Cache('logdate');
	$this->logDate = $tmp->getCache();

	if (isSet($this->logDate['log-'.date('y-m-d')]))
	{
	$this->logDate['log-'.date('y-m-d')]++;
	}
	else
	{
	$this->logDate['log-'.date('y-m-d')] = 1;
	}
	 
	$tmp->setCache($this->logDate);
	
	$this->oCache = new Cache('log-'.date('y-m-d'));
	$this->arrCache = $this->oCache->getCache();
	return $this;
	}


	public function error($msg, $module, $action, $level='notice')
	{
	$this->arrCache[] = array(
			'msg'		=> $msg,				// Information 
			'module'	=> $module,				// Module / Controller
			'action'	=> $action,				// Action / methode
			'ip'		=> Securite::ipX(),		// IP client
			'level' 	=> $level				// niveau d'erreur
			);
	}
	
	public function getLog()
	{
	$tmp=array();

		foreach($this->logDate AS $cacheFile=>$k)
		{
			$c = new Cache($cacheFile);
			$data = $c->getCache();	
			$tmp[$cacheFile] = $data;
		}
	return $tmp;
	}
	
	public function __destruct()
	{
	$this->oCache->setCache($this->arrCache);
	}

}
?>