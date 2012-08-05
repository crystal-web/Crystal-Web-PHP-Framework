<?php

class Template {
	
	/*
 * @the mvc
 * @access private
 */
	private $mvc;
	
	/*
 * @Variables array
 * @access private
 */
	private $vars = array ();
	
	/*
 * @Variables string
 * @access private
 */
	private $path = NULL;
	
	/**
	 *
	 * @constructor
	 *
	 * @access public
	 *
	 * @return void
	 *
	 */
	function __construct($mvc) {
		$this->mvc = $mvc;
	
	}
	
	public function setPath($path /* path to view */)
{
		$this->path = $path;
	}
	
	/**
	 *
	 * @set undefined vars
	 *
	 * @param string $index
	 *
	 * @param mixed $value
	 *
	 * @return void
	 *
	 */
	public function __set($index, $value) {
		$this->vars [$index] = $value;
	}
	
	function show($name) {
		$path = $this->path . '/views' . '/' . $name . '.php';
		
		if (file_exists ( $path ) == false) {
			throw new Exception ( 'Template not found in ' . $path );
			return false;
		}
		
		//debug($this->vars);
		// Load variables
		foreach ( $this->vars as $key => $value ) {
			if (is_array ( $value )) {
				$$key = $value;
			} elseif (is_object ( $value )) {
				$$key = $value;
			} else {
				$$key = stripslashes ( $value );
			}
		}
		
		include ($path);
	}
	
	public function getVars() {
		return $this->vars;
	}

}

?>
