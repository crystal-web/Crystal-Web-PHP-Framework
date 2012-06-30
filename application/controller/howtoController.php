<?php
class howtoController extends Controller {

	public function index()
	{
		$c = new Cryptographe();
		// Nous allons crypter test, sans indiquer de table de caractere
		$resp = $c->polybeCryper('test');
		// Retourn un tableau
		// la premier valeur $resp[0] est la forme codé
		// La seconde est le tableau aillant servi a le codé
		debug( $resp );
		
		// Ici on peu décrypter en indiquant, la forme codé et le tableau
		debug( $c->polybeDecryper($resp[0], $resp[1]));
		echo ' <iframe src="http://payment.rentabiliweb.com/form/acte/form_fb.php?docId=121775&siteId=405025&cnIso=geoip" frameborder="0" width="580" height="400"></iframe>';
		
		
		
		


// Identifiants de votre document
$docId      = 121775;
$siteId      = 405025;

// PHP5 avec register_long_arrays désactivé?
if (!isset($HTTP_GET_VARS)) {
    $HTTP_SESSION_VARS    = $_SESSION;
    $HTTP_SERVER_VARS     = $_SERVER;
    $HTTP_GET_VARS        = $_GET;
}

// Construction de la requête pour vérifier le code

$query      = 'http://payment.rentabiliweb.com/checkcode.php?';
$query     .= 'docId='.$docId;
$query     .= '&siteId='.$siteId;
$query     .= '&code='.$HTTP_GET_VARS['code'];
$query     .= "&REMOTE_ADDR=".$HTTP_SERVER_VARS['REMOTE_ADDR'];


echo $query;
/*$result     = @file($query);


if(trim($result[0]) !== "OK") {
    header('Location: http://imagineyourcraft.fr/monnaie/error');
    exit();
}*/


// Accès à votre page protégée

		
		
	}

}