<?php

Class eventPlugin extends PluginManager{

public function onRender()
{

	if ($this->mvc->Request->controller == 'index' or $this->mvc->Request->controller == 'forum' )
	{
		
		
		$mEvent = loadModel('Event');
		$searchEvent = array('limit' => '0, 5', 'order' => '`id` DESC');
		$respon = $mEvent->find($searchEvent);
		
			if ($respon)
			{
				$this->mvc->Template->setPath(__APP_PATH . DS . 'plugin' . DS . 'event');
				$this->mvc->Template->title = 'Derniers posts du forum';
				$this->mvc->Template->event = $respon;
				$this->mvc->Template->show('default');
			}
	}
	
}



/**
 * 
 * Administration du plugin
 */
public function eventSetting()
{
	echo 'Nofing';
}

}


