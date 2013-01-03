<?php
/**
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/
if (!defined('__APP_PATH'))
{
	echo '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You don\'t have permission to access this file on this server.</p></body></html>'; die;
}

Class contactController extends Controller {
private $p_destinataire = NULL;
public function index()
{
	$page = Page::getInstance();
	$request = Request::getInstance();
	$session = Session::getInstance();
	$template = Template::getInstance();
	
	$page->setPageTitle('Contact');

if (isSet($request->data->mail))
{
	$this->p_destinataire = $this->mvc->config->mailContact;
	
	$Captcha = new Captcha();
	if ($Captcha->checkCaptcha())
	{
		$motif = clean($request->data->motif, 'str');
		
		$firstname = clean($request->data->firstname, 'str');
		$lastname = clean($request->data->lastname, 'str');
		$mail = clean($request->data->mail, 'str');
		$message = clean($request->data->message, 'str') . '<hr>' . $firstname . ' ' . $lastname;
		
		
		if (Securite::isMail($mail))
		{
			$oMail = new Mail($motif, $message, $this->p_destinataire, $mail);
			
			if ($oMail->sendMailHtml())
			{
				$session->setFlash('Votre message &agrave; bien &eacute;t&eacute; envoy&eacute;');
				Router::redirect('contact');
			}
			else
			{
				$session->setFlash('Erreur interne, l\'e-mail, pourrai ne pas arriv&eacute;', 'warning');
			}
		}
		else
		{
			$session->setFlash('Votre adresse e-mail est incorrect', 'warning');
		}
		
	}
	else
	{
		$session->setFlash('Veuillez corriger les erreurs', 'warning');
	}
}

$template->captcha_img = Captcha::generateImgTags("..");
$template->captcha_hidden = Captcha::generateHiddenTags();
$template->captcha_input = Captcha::generateInputTags();
$template->motif = (isSet($data->motif)) ? $data->motif : '';
$template->show('contact/index');
}

}
?>