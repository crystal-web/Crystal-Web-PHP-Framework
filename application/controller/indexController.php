<?php
/**
* @title Connection
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description index du site, sorry j'avais pas d'idée
*/

Class indexController extends Controller{


	public function index()
	{
	/*$ssh = new Ssh2();
	$ssh->setServer('88.191.101.35');
	$ssh->setLogin('dyraz','toudou');
	if ($ssh->startIt())
	{
	echo 'Enjoy';	
	}
	else
	{
	echo 'ok, no more';
	}//*/
	
	$slider = $this->loadModel('Slider');
	$this->mvc->Page->setPageTitle('Bienvenue ');
	$this->mvc->Template->sliderList = $slider->find(array('conditions' => array('active'=>'y')));

	$this->mvc->Template->show('slider');
	}

}