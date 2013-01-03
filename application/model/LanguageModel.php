<?php 
Class LanguageModel extends Model 
{
	public function install()
	{
		$this->query("CREATE TABLE  `" . __SQL . "_Language` (
		`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
		`flag` VARCHAR( 2 ) NOT NULL ,
		`controller` VARCHAR( 16 ) NOT NULL ,
		`language` TEXT NOT NULL ,
		PRIMARY KEY (  `id` )
		) ENGINE = MYISAM ;
		");
		$this->query('INSERT INTO `' . __SQL . '_Language` (`id`, `flag`, `controller`, `language`) VALUES
					(1, \'fr\', \'mediamanager\', \'a:12:{s:20:"Drop your files here";s:31:"D&eacute;posez vos fichiers ici";s:6:"Browse";s:9:"Parcourir";s:7:"Browser";s:10:"Navigateur";s:2:"or";s:2:"ou";s:6:"Upload";s:25:"T&eacute;l&eacute;chargez";s:13:"Media library";s:25:"M&eacute;diath&egrave;que";s:15:"Library manager";s:33:"M&eacute;diath&egrave;que manager";s:17:"Missing parameter";s:25:"Param&egrave;tre manquant";s:39:"Do you really want to delete this file?";s:43:"Voulez-vous vraiment supprimer ce fichier ?";s:6:"Delete";s:9:"Supprimer";s:4:"Save";s:11:"Sauvegarder";s:10:"Saved file";s:25:"Fichier sauvegard&eacute;";}\'),
					(2, \'en\', \'mediamanager\', \'a:12:{s:20:"Drop your files here";s:20:"Drop your files here";s:6:"Browse";s:6:"Browse";s:7:"Browser";s:7:"Browser";s:2:"or";s:2:"or";s:6:"Upload";s:6:"Upload";s:13:"Media library";s:13:"Media library";s:15:"Library manager";s:15:"Library manager";s:17:"Missing parameter";s:17:"Missing parameter";s:39:"Do you really want to delete this file?";s:39:"Do you really want to delete this file?";s:6:"Delete";s:6:"Delete";s:4:"Save";s:4:"Save";s:10:"Saved file";s:10:"Saved file";}\'),
					(3, \'fr\', \'auth\', \'a:42:{s:10:"Your login";s:12:"Votre pseudo";s:13:"Your password";s:18:"Votre mot de passe";s:19:"Your password again";s:29:"Votre mot de passe à nouveau";s:19:"Your e-mail address";s:20:"Votre adresse e-mail";s:25:"Your e-mail address again";s:30:"Votre adresse email à nouveau";s:44:"Your password and confirmation are different";s:61:"Votre mot de passe et la confirmation sont diff&eacute;rents ";s:35:"Your new password is now registered";s:59:"Votre nouveau mot de passe est maintenant enregistr&eacute;";s:26:"Your password is too short";s:42:"Votre mot de passe est trop court (%s min)";s:23:"Your login is too short";s:36:"Votre pseudo est trop court (%s min)";s:48:"Your password is different from the verification";s:65:"Votre mot de passe est diff&eacute;rent de la v&eacute;rification";s:54:"Your e-mail address is different from the verification";s:66:"Votre adresse email est diff&eacute;rent de la v&eacute;rification";s:47:"Your e-mail address is incorrect or blacklisted";s:54:"Votre adresse e-mail est incorrecte ou sur liste noire";s:41:"This e-mail address has already been used";s:57:"Cette adresse email est d&eacute;j&agrave; utilis&eacute;";s:32:"This login has already been used";s:47:"Ce pseudo est d&eacute;j&agrave; utilis&eacute;";s:44:"I read and agree to the General Terms of Use";s:79:"J\'\'ai lu et j\'\'accepte les Conditions g&eacute;n&eacute;ral d\'\'utilisation du site";s:10:"Connection";s:9:"Connexion";s:13:"Disconnection";s:19:"D&eeacute;connexion";s:14:"Authentication";s:16:"Authentification";s:17:"Choose a password";s:26:"Choisissez un mot de passe";s:16:"Save my password";s:28:"Enregistrer mon mot de passe";s:7:"Welcome";s:12:"Bienvenue %s";s:12:"Join website";s:21:"Rejoindre le site web";s:15:"Change password";s:26:"Changement de mot de passe";s:18:"Change my password";s:24:"Changer mon mot de passe";s:13:"Find my login";s:26:"Retrouver mes identifiants";s:11:"Remember me";s:18:"Se souvenir de moi";s:15:"Forgot password";s:26:"Mot de passe oubli&eacute;";s:24:"E-mail address not found";s:25:"Adresse email introuvable";s:52:"An email has been sent to you to reset your password";s:80:"Un email vous a été envoy&eacute; pour r&eacute;initialiser votre mot de passe";s:21:"An error has occurred";s:25:"Une erreur c\'\'est produite";s:48:"Our team received a warning to correct the error";s:70:"Notre &eacute;quipe a re&ccedil;u une alerte afin de corriger l\'\'erreur";s:24:"You are now disconnected";s:51:"Vous &ecirc;tes maintenant d&eacute;connect&eacute;";s:35:"Hello, your account has been banned";s:75:"Bonjour <strong>%s</strong>, votre compte &agrave; &eacute;t&eacute; bannis";s:28:"Hello, you are now connected";s:71:"<strong>Bonjour %s</strong>, vous &ecirc;tes maintenant connect&eacute;";s:35:"Your login or password is incorrect";s:41:"Votre login ou mot de passe est incorrect";s:5:"Hello";s:10:"Bonjour %s";s:102:"An e-mail has been sent to you.<br>Please follow the link in the e-mail to complete your registration.";s:136:"Un courriel vient de vous &ecirc;tre envoy&eacute;. Veuillez suivre le lien dans ce courriel afin de compl&eacute;ter votre inscription.";s:77:"Warning: If you didn‘t receive any email. Check your spam and junk e-mails.";s:105:"Attention: si vous ne recevez pas de courriell. V&eacute;rifier vos spams et courrier ind&eacute;sirable.";s:95:"Thank you for your registration.<br>Please follow the link below to complete your registration.";s:103:"Merci pour votre inscription.<br>Veuillez suivre le lien ci-dessous afin de terminer votre inscription.";s:85:"You asked a password recovery.<br>Please follow the link below to set a new password.";s:156:"Vous avez demand&eacute; a r&eacute;cup&eacute;rer votre mot de passe.<br>Veuillez suivre le lien ci-dessous afin de d&eacute;finir un nouveau mot de passe.";s:28:"Sincerely, our best regards.";s:59:"Veuillez agr&eacute;er, nos salutations distingu&eacute;es.";s:72:"In case of abuse or use by a third part, do not hesitate to let us know.";s:96:"En cas d\'\'abus ou d\'\'utilisation par un tiers, n\'\'h&eacute;sitez pas &agrave; nous le faire savoir.";}\'),
					(4, \'en\', \'auth\', \'a:42:{s:10:"Your login";s:10:"Your login";s:13:"Your password";s:13:"Your password";s:19:"Your password again";s:19:"Your password again";s:19:"Your e-mail address";s:19:"Your e-mail address";s:25:"Your e-mail address again";s:25:"Your e-mail address again";s:44:"Your password and confirmation are different";s:44:"Your password and confirmation are different";s:35:"Your new password is now registered";s:35:"Your new password is now registered";s:26:"Your password is too short";s:35:"Your password is too short (%s min)";s:23:"Your login is too short";s:32:"Your login is too short (%s min)";s:48:"Your password is different from the verification";s:48:"Your password is different from the verification";s:54:"Your e-mail address is different from the verification";s:54:"Your e-mail address is different from the verification";s:47:"Your e-mail address is incorrect or blacklisted";s:47:"Your e-mail address is incorrect or blacklisted";s:41:"This e-mail address has already been used";s:41:"This e-mail address has already been used";s:32:"This login has already been used";s:32:"This login has already been used";s:44:"I read and agree to the General Terms of Use";s:44:"I read and agree to the General Terms of Use";s:10:"Connection";s:10:"Connection";s:13:"Disconnection";s:13:"Disconnection";s:14:"Authentication";s:14:"Authentication";s:17:"Choose a password";s:17:"Choose a password";s:16:"Save my password";s:16:"Save my password";s:7:"Welcome";s:10:"Welcome %s";s:12:"Join website";s:12:"Join website";s:15:"Change password";s:15:"Change password";s:18:"Change my password";s:18:"Change my password";s:13:"Find my login";s:13:"Find my login";s:11:"Remember me";s:11:"Remember me";s:15:"Forgot password";s:15:"Forgot password";s:24:"E-mail address not found";s:24:"E-mail address not found";s:52:"An email has been sent to you to reset your password";s:52:"An email has been sent to you to reset your password";s:21:"An error has occurred";s:21:"An error has occurred";s:48:"Our team received a warning to correct the error";s:48:"Our team received a warning to correct the error";s:24:"You are now disconnected";s:24:"You are now disconnected";s:35:"Hello, your account has been banned";s:55:"Hello <strong>%s</strong>, your account has been banned";s:28:"Hello, you are now connected";s:48:"Hello <strong>%s</strong>, you are now connected";s:35:"Your login or password is incorrect";s:35:"Your login or password is incorrect";s:5:"Hello";s:8:"Hello %s";s:102:"An e-mail has been sent to you.<br>Please follow the link in the e-mail to complete your registration.";s:102:"An e-mail has been sent to you.<br>Please follow the link in the e-mail to complete your registration.";s:77:"Warning: If you didn‘t receive any email. Check your spam and junk e-mails.";s:77:"Warning: If you didn‘t receive any email. Check your spam and junk e-mails.";s:95:"Thank you for your registration.<br>Please follow the link below to complete your registration.";s:95:"Thank you for your registration.<br>Please follow the link below to complete your registration.";s:85:"You asked a password recovery.<br>Please follow the link below to set a new password.";s:85:"You asked a password recovery.<br>Please follow the link below to set a new password.";s:28:"Sincerely, our best regards.";s:28:"Sincerely, our best regards.";s:72:"In case of abuse or use by a third part, do not hesitate to let us know.";s:72:"In case of abuse or use by a third part, do not hesitate to let us know.";}\');');

	}
	
	public function addLanguage($flag, $controller, $lang)
	{
		$meLanguage = $this->getLanguage($flag, $controller);
		if ($meLanguage)
		{
			$meLanguage->language = serialize($lang);
		}
		else
		{
			$meLanguage = new stdClass();
			$meLanguage->flag = $flag;
			$meLanguage->controller = $controller;
			$meLanguage->language = serialize($lang);
		}
		
		return $this->save($meLanguage);
	}
	
	public function getLanguage($flag, $controller)
	{
		return $this->findFirst(
			array('conditions' => 
				array(
					'flag' => $flag,
					'controller' => $controller
					)
				)
			);
	}
	
}
?>