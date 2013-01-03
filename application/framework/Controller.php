<?php
/*##################################################
 *                              Controller.php
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
abstract class Controller{
	/*** Cree un nouveau controleur ***/
	function __construct()
	{
		$request = Request::getInstance();
		$plugin = Plugin::getInstance();
		$plugin->triggerEvents('load' . $request->getController());
	}

	
	/*** tous les controleurs doivent contenir une methode index ***/
	public function index()
	{
		$page = Page::getInstance();
		$session = Session::getInstance();
		$page->setPageTitle('Method index notfound');
		$session->setFlash('Chaque controller doit avoir une m&eacute;thode index', 'error');
	}
	
	/**
	*	
	*/
	public function loadModel($name)
	{		
	$name = $name.'Model';
	// L'endroit ou le model est charg�
	$file = __APP_PATH . DS . 'model' . DS . $name . '.php';
		if (file_exists($file))
		{
		require_once $file;
			if (!isSet($this->$name))
			{
				return new $name();
			}
		}
		else
		{
			throw new Exception ('File model not found '.$file);
		}
	}
	
	
	/**
	 *	Chargement d'un controller
	 *	Try -> Catch
	 */
	public function loadController($controller)
	{
		$name = $controller.'Controller'; 
		$file = __APP_PATH . DS . 'controller' . DS . $name . '.php';
		if(!file_exists($file)){
			throw new Exception ('Le controller '.$controller.' n\'existe pas dans '.$file); 
		}
		require $file; 
		return new $name($this->mvc); 
	}
	
	
	public function attach(Controller $controller)
	{
	
	}
	
	
}
?>