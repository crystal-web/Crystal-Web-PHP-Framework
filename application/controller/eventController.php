<?php
/**
* @title Connection
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description 
*/
Class eventController extends Controller {

public function index()
{


}

public function ajax()
{
$event = $this->loadModel('Event');
$req = array(
	'order' => 'Event.time  DESC',
	'limit'	=> '0, 10',
	);

$this->mvc->Template->event = $event->find($req);
$this->mvc->Template->show('event/ajax');
}

}
?>