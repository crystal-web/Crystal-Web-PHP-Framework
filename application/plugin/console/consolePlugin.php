<?php

Class consolePlugin{

	public function onEnabled()
	{
		PhpConsole::start(true, true, dirname(__FILE__));
	}
	
	public function afterContent()
	{
		echo '<div class="widget">' . 
			'<div class="widget-header">' .
				'<h3>Console Log</h3>' .
			'</div>'.
				'<div class="widget-content">' . 
					Log::console() . 
				'</div>' . 
			'</div>';
	}
}//*/