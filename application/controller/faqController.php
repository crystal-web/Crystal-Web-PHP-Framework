<?php
/**
* @package Faq
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description Frequent Ask Question
*/

Class faqController extends Controller {

public function index()
{
$this->mvc->Page->setPageTitle('Foire aux questions');

$faq = $this->loadModel('Faq');

$this->mvc->Template->faq = $faq->find(array('conditions' => array('active' => 'on')));
$this->mvc->Template->show('faq/index');
}

public function manager()
{
if ($this->mvc->Acl->isAllowed())
{
$faq = $this->loadModel('Faq');
	if (isSet($this->mvc->Request->data->question) && $this->mvc->Session->token())
	{
	
	$this->mvc->Session->makeToken();
	$faq->save($this->mvc->Request->data);
	Router::redirect('faq');	
	}

	if (isSet($this->mvc->Request->params['id']) && $this->mvc->Session->token())
	{
	$this->mvc->Session->makeToken();
	
	$data = new stdClass();
	$data->id = $this->mvc->Request->params['id'];
	$data->active = 'off';
	$faq->save($data);
	Router::redirect('faq');
	
	}
	else
	{
	return $this->index();
	}
}
else
{
return $this->index();
}
}

}
?>