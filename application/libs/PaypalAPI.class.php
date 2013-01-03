<?php
/**
* @title Connection
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description 
*/

Class PaypalAPI {

// Version de l'API
private $version = 56.0;
// Utilisateur API
private $user = 'pro_1330047737_biz_api1.crystal-web.org';
// Mot de passe API
private $pass = '1330047769';
// Signature de l'API
private $signature = 'AVRDdk9c6LgG1dJutZew0Up3ESgaAbcAzy01IYcFW7vTq3qPkvSaCGnC';
// Site de l'API PayPal. On ajoute déjà le ? afin de concaténer directement les paramètres.
private $api_paypal = 'https://api-3t.sandbox.paypal.com/nvp?';

private $get_rules;
private $post_rules;
	/**
	* Constructeur de l'url de connection Paypal
	*/
	public function __construct()
	{
		if (isSet($_GET)){
		$this->get_rules = $_GET;
		// Sauvegarde les données get pour test
		$cache = new Cache('paypal_get');
		$cache->setCache($this->get_rules);
		}
		
		if (isSet($_POST)){
		$this->post_rules = $_POST;
		// Sauvegarde les données post pour test
		$cache = new Cache('paypal_post');
		$cache->setCache($this->post_rules);
		}

		
		$this->api_paypal = $this->api_paypal.'VERSION='.$this->version.'&USER='.$this->user.'&PWD='.$this->pass.'&SIGNATURE='.$this->signature; // Ajoute tous les paramètres		
		return 	$this;
	}

	public function getAPI()
	{
	return $this->api_paypal;
	}
	
	/**
	*	Ajout des paramettres a l'api
	*/
	public function params(
					$cancelURL,
					$returnURL,
					$custom,
					$desc,
					$amout, 
					$currencycode = 'EUR',
					$local='FR',
					$logo='http://beta.crystal-web.org/media/image/png/logo.81.png')
	{
	$currencycode	= strtoupper($currencycode);
	$local			= strtoupper($local);
	
	$this->api_paypal = $this->api_paypal.
			"&METHOD=SetExpressCheckout".
			"&CANCELURL=".urlencode($cancelURL).
			"&RETURNURL=".urlencode($returnURL).
			"&AMT=".$amout.
			"&CURRENCYCODE=".$currencycode.
			"&DESC=".urlencode($desc).
			"&LOCALECODE=".$local.
			"&CUSTOM=".$custom.
			"&HDRIMG=".urlencode($logo);
	return $this;
	}
	

	private function paramToArray($resultat_paypal)
	{
		// On récupère la liste de paramètres, séparés par un 'et' commercial (&)
		$liste_parametres = explode("&",$resultat_paypal);
		// Pour chacun de ces paramètres, on exécute le bloc suivant, en intégrant le paramètre dans la variable $param_paypal
		foreach($liste_parametres as $param_paypal)
		{
			// On récupère le nom du paramètre et sa valeur dans 2 variables différentes. Elles sont séparées par le signe égal (=)
			list($nom, $valeur) = explode("=", $param_paypal);
			// On crée un tableau contenant le nom du paramètre comme identifiant et la valeur comme valeur.
			$liste_param_paypal[$nom]=urldecode($valeur); // Décode toutes les séquences %##  et les remplace par leur valeur. 
		}
	return $liste_param_paypal; // Retourne l'array
	}
	
	
	/**
	*	Génére la connection au serveur paypal
	*	renvois l'adresse d'acces et la réponse du serveur
	*/
	public function initTransaction()
	{
	// Initialise notre session cURL. On lui donne la requête à exécuter
	$ch = curl_init($this->api_paypal);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	// Retourne directement le transfert sous forme de chaîne de la valeur retournée par curl_exec() au lieu de l'afficher directement. 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	// On lance l'exécution de la requête URL et on récupère le résultat dans une variable
	$resultat_paypal = curl_exec($ch);

		// S'il y a une erreur, on affiche "Erreur", suivi du détail de l'erreur.
		if (!$resultat_paypal)
		{
		return false;
		}
		else
		{
		$liste_param_paypal = $this->paramToArray($resultat_paypal); // Lance notre fonction qui dispatche le résultat obtenu en un array
		echo "<pre>";
		print_r($liste_param_paypal);
		echo "</pre>";
			// Si la requête a été traitée avec succès
			if ($liste_param_paypal['ACK'] == 'Success')
			{
			$liste_param_paypal['api_url'] = 'https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token='.$liste_param_paypal['TOKEN'];
			return $liste_param_paypal;
			}
			else // En cas d'échec, affiche la première erreur trouvée.
			{
			return false;
			}		
		}
	curl_close($ch);
	}
	
	
	
	
	public function initConfirm()
	{
		if (isSet($this->get_rules['token']) && isSet($this->get_rules['PayerID']))
		{
		// On ajoute le reste des options
		// La fonction urlencode permet d'encoder au format URL les espaces, slash, deux points, etc.)
		$requete = $this->api_paypal."&METHOD=DoExpressCheckoutPayment".
				"&TOKEN=".htmlentities($this->get_rules['token'], ENT_QUOTES). // Ajoute le jeton qui nous a été renvoyé
				"&AMT=10.0".
				"&CURRENCYCODE=EUR".
				"&PayerID=".htmlentities($this->get_rules['PayerID'], ENT_QUOTES). // Ajoute l'identifiant du paiement qui nous a également été renvoyé
				"&PAYMENTACTION=sale";
		
		

		// Initialise notre session cURL. On lui donne la requête à exécuter.
		$ch = curl_init($requete);

		// Modifie l'option CURLOPT_SSL_VERIFYPEER afin d'ignorer la vérification du certificat SSL. Si cette option est à 1, une erreur affichera que la vérification du certificat SSL a échoué, et rien ne sera retourné. 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		// Retourne directement le transfert sous forme de chaîne de la valeur retournée par curl_exec() au lieu de l'afficher directement. 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// On lance l'exécution de la requête URL et on récupère le résultat dans une variable
		$resultat_paypal = curl_exec($ch);
		
		// S'il y a une erreur, on affiche "Erreur", suivi du détail de l'erreur.
		if (!$resultat_paypal) 
		{
		echo "<p>Erreur</p><p>".curl_error($ch)."</p>";
		}
		// S'il s'est exécuté correctement, on effectue les traitements...
		else
		{
		// Lance notre fonction qui dispatche le résultat obtenu en un array
		$liste_param_paypal = $this->paramToArray($resultat_paypal);

		// On affiche tous les paramètres afin d'avoir un aperçu global des valeurs exploitables (pour vos traitements). Une fois que votre page sera comme vous le voulez, supprimez ces 3 lignes. Les visiteurs n'auront aucune raison de voir ces valeurs s'afficher.
		echo "<pre>";
		print_r($liste_param_paypal);
		echo "</pre>";

			// Si la requête a été traitée avec succès
			if ($liste_param_paypal['ACK'] == 'Success')
			{
			return $liste_param_paypal;
			echo "<h1>Youpii, le paiement a été effectué</h1>"; // On affiche la page avec les remerciements, et tout le tralala...
			// Mise à jour de la base de données & traitements divers...
			//mysql_query("UPDATE commandes SET etat='OK' WHERE id_commande='".$liste_param_paypal['TRANSACTIONID']."'");
			}
			else // En cas d'échec, affiche la première erreur trouvée.
			{
			echo "<p>Erreur de communication avec le serveur PayPal.<br />".$liste_param_paypal['L_SHORTMESSAGE0']."<br />".$liste_param_paypal['L_LONGMESSAGE0']."</p>";
			}
		}
		// On ferme notre session cURL.
		curl_close($ch);

		}
		else
		{
		return false;
		}
	}
}