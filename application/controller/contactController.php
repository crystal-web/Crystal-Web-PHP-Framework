<?php
/**
* @title Contact
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description Formulaire de contact
*/
Class contactController extends Controller {
private $p_destinataire = 'contact@imagineyourcraft.fr';
public function index()
{
$this->mvc->Page->setPageTitle('Contact');

if (isSet($this->mvc->Request->data->mail))
{
	$Captcha = new Captcha();
	if ($Captcha->checkCaptcha())
	{
		$motif = clean($this->mvc->Request->data->motif, 'str');
		
		$firstname = clean($this->mvc->Request->data->firstname, 'str');
		$lastname = clean($this->mvc->Request->data->lastname, 'str');
		$mail = clean($this->mvc->Request->data->mail, 'str');
		$message = clean($this->mvc->Request->data->motif, 'str') . '<hr>' . $firstname . ' ' . $lastname;
		
		
		if (Securite::isMail($mail))
		{
			$oMail = new Mail('[P4F-Craft] ' . $motif, $message, $this->p_destinataire, $mail);
			
			if ($oMail->sendMailHtml())
			{
				$this->mvc->Session->setFlash('Votre message à bien été envoyé');
				Router::redirect('contact');
			}
			else
			{
				$this->mvc->Session->setFlash('Erreur interne, l\'e-mail, pourrai ne pas arrivé', 'warning');
			}
		}
		else
		{
			$this->mvc->Session->setFlash('Votre adresse e-mail est incorrect', 'warning');			
		}
		
	}
	else
	{
	$this->mvc->Session->setFlash('Veuillez corriger les erreurs', 'warning');
	}
	

}


$this->mvc->Template->captcha_img = Captcha::generateImgTags("..");
$this->mvc->Template->captcha_hidden = Captcha::generateHiddenTags();
$this->mvc->Template->captcha_input = Captcha::generateInputTags();
$this->mvc->Template->motif = (isSet($data->motif)) ? $data->motif : '';
$this->mvc->Template->show('contact/index');
}

}
?>