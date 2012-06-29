<?php
class Plugin
{

/***************************************
*	Liste des plugins en memoire
***************************************/
//private $plugins = array();
private $plugins = array();
protected $mvc;
private $cachedPlugin;
private $oCache;

	/*** Cree un nouveau controleur ***/
	function __construct($mvc)
	{
		$this->mvc = $mvc;
		$this->oCache = new Cache('plugin');
		$this->cachedPlugin = $this->oCache->getCache();
		//debug($this->cachedPlugin);
		foreach($this->cachedPlugin AS $named=>$v)
		{
		
			if ($v['enable'] && !isSet($this->plugins[ $named ]))
			{
			$name = $named.'Plugin';

				if (file_exists(__APP_PATH . DS . 'plugin'  . DS . $named . DS . $name . '.php'))
				{
				require __APP_PATH . DS . 'plugin'  . DS . $named . DS . $name . '.php';

				$object = new $name($this->mvc);
				
					// Premier triggerEvent, onEnable
					if (method_exists($object, 'onEnable')) 
					{
					$object->onEnable(); 
					}
					
				// Enregistrement du plugin
				$this->plugins[ $named ] = $object; 				
				
				}
				
			}
		}

		/*$rt =  array();
		$rt['event'] = array('enable' => true);
		$oCache->setCache($rt);//*/
	}
	
	
	public function getList()
	{
	return $this->cachedPlugin;
	}
	
	public function setList($list)
	{
	return $this->oCache->setCache($list);
	}

	/***************************************
	*	Attrappe l'evenement et test si un plugin en a besoin
	***************************************/
    public function triggerEvents($event) 
    { 
        foreach ($this->plugins as $name => $object) 
        { 
            if (method_exists($object, $event)) 
            { 
                $this->plugins[$name]->$event(); 
            } 
        } 
    }
	
	public function __destruct()
	{
		$this->triggerEvents('onDisable'); 
	}
} 