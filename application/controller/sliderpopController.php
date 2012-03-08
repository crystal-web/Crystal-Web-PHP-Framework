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
$slider = $this->loadModel('Slider');

	if ($this->mvc->Request->data)
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

}