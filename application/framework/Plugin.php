<?php
/*##################################################
 *                                 Plugin.php
 *                            -------------------
 *   begin                : 2012-03-08
 *   copyright            : (C) 2012 DevPHP
 *   email                : developpeur@crystal-web.org
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/
class Plugin
{

/***************************************
*	Liste des plugins en memoire
***************************************/
//private $plugins = array();
private $plugins = array();
private $cachedPlugin;
private $oCache;

	/**
	* @var Singleton
	* @access private
	* @static
	*/
	private static $_instance = null;
	 
	
	/**
	* Méthode qui crée l'unique instance de la classe
	* si elle n'existe pas encore puis la retourne.
	*
	* @param void
	* @return Singleton
	*/
	public static function getInstance() {
		if(is_null(self::$_instance)) {
			self::$_instance = new Plugin();  
		}
		return self::$_instance;
	}
	
	/*** Cree un nouveau controleur ***/
	public function __construct()
	{
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

				Log::setLog('Load ' . $name, get_class($this));
				$object = new $name();
				
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
    public function triggerEvents($event, $param = NULL) 
    { 
        foreach ($this->plugins as $name => $object) 
        { 
            if (method_exists($object, $event)) 
            { 
                $this->plugins[$name]->$event($param); 
            } 
        } 
    }
	
	public function __destruct()
	{
		$this->triggerEvents('onDisable'); 
	}
} 


abstract class PluginManager{

	/*** Cree un nouveau controleur ***/
	public function __construct()
	{

	}
}