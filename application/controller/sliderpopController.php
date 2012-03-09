<?php
/**
* @title SliderPop
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description Permet la gestion du slider
*/

class SliderpopController extends Controller{ 

public function index()
{

if ($this->mvc->Acl->isAllowed())
{
$this->mvc->Page->setPageTitle('Slider liste');
$slider = $this->loadModel('Slider');

	if (isSet($this->mvc->Request->params['id']) && isSet($this->mvc->Request->params['stat'])) 
	{
		$stat = ($this->mvc->Request->params['stat'] == '1') ? 'y': 'n';
		$data = new stdClass();
		$data->id = $this->mvc->Request->params['id'];
		$data->active = $stat;
		$slider->save($data);
	}
	elseif (isSet($this->mvc->Request->params['id']) && !isSet($this->mvc->Request->params['stat'])) 
	{
		if ($this->mvc->Session->token())
		{
		$slider->delete($this->mvc->Request->params['id']);
		}
	
	}

$this->mvc->Template->slider = $slider->find();
$this->mvc->Template->show('slider/index');
}
else
{
$this->mvc->Session->setFlash('Vous n\'avez pas le droit d\'accès à cette page', 'error');
}

}


public function pop()
{

if ($this->mvc->Acl->isAllowed())
{
$this->mvc->Page->setPageTitle('Slider pop')->setBreadcrumb('sliderpop', 'Slider');

$slider = $this->loadModel('Slider');

	if (isSet($this->mvc->Request->data->title) && $this->mvc->Session->token())
	{
		if ($slider->validates($this->mvc->Request->data))
		{
			if($return = $slider->save($this->mvc->Request->data)>0)
			{
			$this->mvc->Session->setFlash('Image ajouté au Slider avec succès');
			}
			else
			{
			$this->mvc->Session->setFlash('Il y a une erreur système c\'est produite', 'error');
			}
		}
		else
		{
		$this->mvc->Session->setFlash('Il y a une ou plusieurs erreurs se sont produites', 'error');
		}
	
	}

$this->mvc->Form->setErrors($slider->errors);
$form = $this->mvc->Form->input('title', 'Titre:');
$form .= $this->mvc->Form->input('description', 'Texte d\'accroche:', array('type' => 'textarea'));
$form .= $this->mvc->Form->input('link', 'Lien');
$form .= $this->mvc->Form->input('image', 'Url de l\'image');
$form .= $this->mvc->Form->input('send', 'Ajouter', array('type' => 'submit', 'class' => 'btn primary'));
$this->mvc->Template->form = $form;
$this->mvc->Template->show('slider/pop');
}
else
{
$this->mvc->Session->setFlash('Vous n\'avez pas le droit d\'accès à cette page', 'error');
}

}


public function edit()
{

if ($this->mvc->Acl->isAllowed())
{
$this->mvc->Page->setPageTitle('Slider edition')->setBreadcrumb('sliderpop', 'Slider');
$slider = $this->loadModel('Slider');

	if (isSet($this->mvc->Request->params['id'])) 
	{
		if (isSet($this->mvc->Request->data->id) && $this->mvc->Session->token())
		{
		$slider->save($this->mvc->Request->data);
		$this->mvc->Session->setFlash('Slider modifier avec succès<br><a href="'.Router::url('sliderpop').'" class="btn primary">Retourner à la liste des slide ?</a>');
		}
	
	
	
	$this->mvc->Request->data = $slider->findFirst(array('conditions' => array('id' => $this->mvc->Request->params['id'])));
	$form = $this->mvc->Form->input('id', 'hidden');
	$form .= $this->mvc->Form->input('title', 'Titre:');
	$form .= $this->mvc->Form->input('description', 'Texte d\'accroche:', array('type' => 'textarea'));
	$form .= $this->mvc->Form->input('link', 'Lien');
	$form .= $this->mvc->Form->input('image', 'Url de l\'image');
	$form .= $this->mvc->Form->input('send', 'Ajouter', array('type' => 'submit', 'class' => 'btn primary'));
	$this->mvc->Template->form = $form;
	

	$this->mvc->Template->show('slider/edit');
	}
	else
	{
	Router::redirect('sliderpop');
	}

}
else
{
$this->mvc->Session->setFlash('Vous n\'avez pas le droit d\'accès à cette page', 'error');
}

}


}