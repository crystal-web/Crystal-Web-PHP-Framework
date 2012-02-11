<?php
/* Source : http://a-pellegrini.developpez.com/tutoriels/php/templates/?page=sommaire */
/* NOTE /
* Tentative d'imbrication de boucle infructueuse
**/
class Skin {
	private $page;                // Code source HTML de la page - fichier.tpl
	private $infoTpl = array();   // Tableau des constantes => données
	/**
	* Vérifie l'existence du fichier
	*méthode __construct()
	*/    


	public function __construct($file)
	{
		// Teste si le fichier existe et si il est autorisé en lecture
		if(empty($file) or !file_exists($file) or !is_readable($file))
		{
		// Si le fichier est inexistant pas : erreur
		$refer = (isSet($_SERVER['HTTP_REFERER']))? $_SERVER['HTTP_REFERER'] : 'index.php';
		die('Template error : file '.$file.' not found.<br />
			Erreur de template : fichier  '.$file.' introuvable.<br />
			<a href="'.$refer.'" onclick="history.back();">Return - Retour</a>');
		}
		else
		{
		// Ouverture du fichier
		$handle = fopen($file, 'rb');

		if ($size = filesize ($file)>0)
		{
		// Enregistrement du fichier dans $this->page
		$this->page = fread($handle, filesize ($file));
		}
		else
		{
		exit('Skin not found '.$file);
		}
		// Fermeture du fichier
		fclose ($handle);
		}
	}
	
	public function includeFile ($file) {
		// Enclenche la temporisation de sortie
		ob_start();
		
		include $file;
		
		// Enregistre le contenu du tampon de sortie
		$buffer = ob_get_contents();
		
		// Efface le contenu du tampon de sortie
		ob_clean();
		
		// Retourne les données enregistrées
		return $buffer;
	}
	/**
	* Enregistre les constantes dans $infoTpl
	* infoTpl[.][][constant] = data;
	*/
	public function simpleVar($varArray = array())
	{
	// Si le tableau est vide, on stoppe le script
	if (empty($varArray)) exit;

		// Parcours du tableau
		foreach ($varArray as $var => $data)
		{
		// Enregistrement dans le tableau $this->infoTpl
		$this->infoTpl['.'][][$var] = $data;
		}
	}

	/**
	* Enregistre les constantes dans $infoTpl
	* infoTpl[type][lastID][constant] = data;
	*
	* - type    = nom du bloc contenant la boucle
	* - lastID    = ID du tableau où se trouve le script
	*/
	public function loopVar($type, $varArray = array())
	{
		// Si le tableau est vide, on stoppe le script
		if (empty($varArray)) exit;

		// Calcule le nombre de lignes dans le type courant
		// Si 0 ligne    => 0
		// Si X lignes    => X
		// Pourquoi X et non (X + 1) ?
		// -> Car on comtpe à partir de 0 donc X retourne toujours
		//      le dernier id + 1
		if (array_key_exists($type, $this->infoTpl))
		{
		$lastID = count($this->infoTpl[$type]);
		}
		else
		{
		$lastID = 0;
		}


		foreach ($varArray as $constant => $data)
		{
		$this->infoTpl[$type][$lastID][$constant] = $data;
		}
	}   

	/**
	 * Remplace les constantes par leurs données
	 */
	public function constantReplace()
	{
	// Parcours de tout le taleau $this->infoTpl
	foreach($this->infoTpl as $type => $info)
		{
		// Si le type est '.' càd
		// provient de la fonction simpleVar()
		// ou encore de constantes places hors-boucle
		if ($type == '.')
			{
			for ($i = 0, $imax = count($info); $i < $imax; $i++)
				{
				foreach ($info[$i] as $constant => $data)
					{
					// Remplace {CONSTANTE} par les donneés correspondantes
					// et mets  jour le code HTML du fichier test.tpl
					// stock dans $this->page
					//$data = (file_exists($data.'.inc')) ? $this->includeFile($data.'.inc') : $data;
					$this->page = preg_replace('`{'.$constant.'}`', $data, $this->page);
					}
				}
			// Sinon si le type est autre càd
			// provient de la fonction loopVar()
			// ou encore de constantes places dans une boucle
			}
		else
			{
			// Calcule la taille du tableau $info
			$infoSize = count($info);

			// Variable qui contiendra le code à la place de
			//  <!-- BEGIN country -->
			//  {country.ID} => {country.COUNTRY}
			// <!-- END country -->
			$block = '';

			// Parcourt le tableau $info
			for ($i = 0; $i < $infoSize; $i++)
				{
				// Encode les caractres spéciaux
				$page = htmlentities($this->page);

				// $page est une variable string
				// Remplit le tableau $infoArray ligne par ligne
				$infoArray = explode("\n", $page);

				// Suppression des espaces blancs avant/après
				// dus aux indentations du code
				for ($k = 0, $kmax = count($infoArray); $k < $kmax; $k++)
					{
					$infoArray[$k] = trim($infoArray[$k]);
					}    

				// Récupration et formatage des tags
				$startTag = '<!-- BEGIN '.$type.' -->';
				$startTag = htmlentities($startTag);

				$endTag = '<!-- END '.$type.' -->';
				$endTag = htmlentities($endTag);

				// Récupration de la clé des tags dans le tableau $infoArray
				$startTag = (array_search($startTag, $infoArray)) + 1;
				$endTag = (array_search($endTag, $infoArray)) - 1;
				// Nombre de lignes entre les tags
				$lengthTag = ($endTag - $startTag) + 1;

				// Récupration de la portion du tableau
				// délimite par les tags (tags non compris)
				$blockTag = array_slice($infoArray, $startTag, $lengthTag);

				// Remise en type 'string' et non plus 'array'
				// Facilitera le remplacement des constantes par leurs donneés
				$blockTag = implode("\n", $blockTag);

				// Remplacement des constantes par leurs données
				foreach($info[$i] as $constant => $data) {
						//$data = (file_exists($data.'.inc')) ? $this->includeFile($data.'.inc') : $data;
						$blockTag = preg_replace('`{'.$type.'.'.$constant.'}`', $data, $blockTag);
				}

				// Ajout des données à la variable block globale pour la boucle
				// Ajoute \n ou pas et ajoute les données
				// de la nouvelle boucle à la suite de $block
				$block = ($block == '') ? $blockTag : $block."\n".$blockTag;
				}

			// Mise en tableau de $block
			// Facilitera l'opération de reconstitution des tableaux
			$block = explode ("\n", $block);

			// Coupe du tableau en 2
			// $fisrtPart = début du tableau   ----->    <!-- BEGIN country -->
			// $secondPart = <!-- BEGIN country -->    ----->    fin du tableau
			$firstPart = array_slice($infoArray, 0, $startTag - 1);
			$secondPart = array_slice($infoArray, $startTag + $lengthTag + 1);

			// Reconstitution du code source
			// en insrant au milieu les donnes
			$page = array_merge($firstPart, $block, $secondPart);

			// Décode les balises HTML qui étaient encodées avec htmlentities()
			for ($i = 0, $imax = count($page); $i < $imax; $i++)
				{
				$page[$i] = html_entity_decode($page[$i]);
				}

			// Mets  jour le code HTML du fichier test.tpl
			// stock dans $this->page
			$this->page = implode("\n", $page);
			}
		}
	}

	/**
	 * Retourne le code HTML parser
	 */
	public function parse()
	{
	$this->constantReplace();
	return $this->page;
	}
}
/* Plus d'exemple
******************

// Instanciation de la classe
$t = new Skin('test.tpl');

// Simple variable
$t->simpleVar(array(
        'WELCOME_MSG' => 'Bonjour !!',
        'GOODBYE' => 'Au revoir !!',
));
$t->parse();

<body>
    {WELCOME_MSG} <br />
    {GOODBYE}
</body>

******************

// Instanciation de la classe
$t = new Skin('test.tpl');

// Variable avec boucle
$country_array = array('BE' => 'Belgique',
                       'FR' => 'France',
                       'ITA' => 'Italie',
);

foreach ($country_array as $id => $country) {
    $t->loopVar('country', array(
            'ID' => $id,
            'COUNTRY' => $country,
    ));
}
$t->parse();

<body>
    <!-- BEGIN country -->
    boucle :
    <b>{country.ID}</b> => {country.COUNTRY} <br/>
    <!-- END country -->
</body>
*/


/* PENSER
***********/

/*
N'a pas été réalisé en conditionnel

preg_replace_callback, qui cherche les expressions du type 

<!-- IF ([^\-]+) TRUE -->
(.*)
<!-- ELSEIF ([^\-]+) -->
(.*)
<!-- ENDIF ([^\-]+) -->

*/
?>