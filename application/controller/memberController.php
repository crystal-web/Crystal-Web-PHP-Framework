<?php
/**
* @title Espace membre
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description 
*/
Class memberController extends Controller {

public function index()
{
$this->mvc->Page->setPageTitle('Espace Membre');

if ($this->mvc->Session->isLogged())
{



}
else
{
$this->mvc->Session->setFlash('Vous devez être connecté', 'error');
}

}


public function profile()
{


if ($this->mvc->Session->isLogged())
{
$switch = (isSet($this->mvc->Request->params['switch'])) ? $this->mvc->Request->params['switch'] : NULL;
	switch($switch)
	{
	case 'info':
	$this->mvc->Page->setPageTitle('Informations publique');
	/*
	Site Internet:	
	Localisation:	
	Emploi:	
	Centres d’intérêt:
	*/	
	break;
	case 'sign':
	$this->mvc->Page->setPageTitle('Changement de signature');
	/*
	Modifier la signature
	*/	
	break;
	case 'avatar':
	$this->mvc->Page->setPageTitle('Changement d\'avatar');
	/*
	Modifier l'avatar
	*/
	break;
	case 'auth':
	$this->mvc->Page->setPageTitle('Changement de mot de passe');
	/*
	user name
	password
	email
	*/
	break;
	default:
	$this->mvc->Page->setPageTitle('Profil de '.$this->mvc->Session->user('login'));
	/*
	current profile
	*/
	var_dump($this->mvc->Acl->isGrant());
	
	foreach($this->mvc->Acl->log AS $k=>$v)
	{
	echo $v.'<br>';
	}
	var_dump($this->mvc->Session->user('id'));
	$this->mvc->Template->show('member/user-profile');
	break;
	}



}
else
{
$this->mvc->Session->setFlash('Vous devez être connecté', 'error');
}

}


}
?>