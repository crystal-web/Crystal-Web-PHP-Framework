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
/**
 * @revision 2015-01-29
 * @changelog Correction général
 *  Ajout du support pour les plugins .Phar
 */
class Plugin
{

    /**
     * @var array
     * @access private
     */
    private $plugins = array();

    /**
     * @var array
     * @access private
     */
    private $cachedPlugin;

    /**
     * @var Cache
     * @access private
     */
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
    public function __construct() {
        $this->oCache = new Cache('plugin');
        $this->cachedPlugin = $this->oCache->getCache();
        
        if (!$this->cachedPlugin){
            return;
        }
        
        foreach($this->cachedPlugin AS $named=>$v) {
            if ($v['enable'] && !isSet($this->plugins[ $named ])) {
            $name = $named.'Plugin';

                /* Premiere methode, il s'agit d'un plugin non-PHAR */
                if (file_exists(__APP_PATH . DS . 'plugin'  . DS . $named . DS . $name . '.php')) {
                    require_once __APP_PATH . DS . 'plugin'  . DS . $named . DS . $name . '.php';
    
                    Log::setLog('Load ' . $name . ' has ' . $name . '.php', get_class($this));
                    $object = new $name();
                        
                    // Premier triggerEvent, onEnable
                    if (method_exists($object, 'onEnable')) {
                        $object->onEnable(); 
                    }
                    
                    // Enregistrement du plugin
                    $this->plugins[ $named ] = $object;
                } // Deuxieme méthode, il s'agit d'un PHAR
                elseif(file_exists(__APP_PATH . DS . 'plugin'  . DS . $named . '.phar')) {
                    require_once __APP_PATH . DS . 'plugin'  . DS . $named . '.phar';
                    
                    Log::setLog('Load ' . $name . ' has ' . $named . '.phar', get_class($this));
                    $object = new $name();
                        
                    // Premier triggerEvent, onEnable
                    if (method_exists($object, 'onEnable')) {
                        $object->onEnable(); 
                    }
                    
                    // Enregistrement du plugin
                    $this->plugins[ $named ] = $object;
                }
            }
            
            

        }
    }
    
    
    public function getList() {
        return $this->cachedPlugin;
    }
    
    public function setList($list) {
        return $this->oCache->setCache($list);
    }

    /***************************************
    *   Attrappe l'evenement et test si un plugin en a besoin
    ***************************************/
    public function triggerEvents($event, $param = NULL) { 
        foreach ($this->plugins as $name => $object) { 
            if (method_exists($object, $event)) { 
                $this->plugins[$name]->$event($param); 
            } 
        } 
    }
    
    public function execute($pluginName, $event, $param = NULL) {
        if (isset($this->plugins[$pluginName])) {
            if (method_exists($this->plugins[$pluginName], $event)) { 
               return $this->plugins[$pluginName]->$event($param); 
            } 
        }
    }
    
    public function __destruct() {
        $this->triggerEvents('onDisable'); 
    }
} 


abstract class PluginManager{

    /*** Cree un nouveau controleur ***/
    public function __construct()
    {

    }
}