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
		
	}

}