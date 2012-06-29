<?php

/**
* @title Stater systeme - Ne pas perdre un client
* @author Christophe BUFFET <developpeur@crystal-web.org>
* @license Creative Commons By
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @package org.crystal-web.starter
*/
Class Starter{
private $localCache = array(
    'title' => NULL,
    'message' => NULL,
    'status' => NULL,
    'time2open' => NULL,
    'display_delay' => true,
    'mail' => array(),
    'textForm' => 'M\'informer de l\'ouverture'
    );
private $oCache;

/**
 * 
 * Construit la class a partir du cache array
 * @param array $oStarterCache
 */
public function  __construct(array $oStarterCache=array())
{
// hydrate
    if (count($oStarterCache)){
    $this->localCache = $oStarterCache;
    }
}

/*
*   GETTER
*/


/**
 * 
 * Retourne le statut (enabled|disabled)
 */
public function getStatus() { return $this->localCache['status']; }


/**
 * 
 * Retourne le titre
 * @note non implementé
 */
public function getTitle() { return $this->localCache['title']; }


/**
 * 
 * Retourne le message
 */
public function getMessage() { return $this->localCache['message']; }


/**
 * 
 * Retourne le temps en secondes avant ouverture
 */
public function getTime2open() { return $this->localCache['time2open']; }


/**
 * 
 * Reourne la listes des e-mails enregistré
 */
public function getMail() { return $this->localCache['mail']; }


/**
 * 
 * Retourne la liste des e-mail admin aillant accès
 */
public function getMailAdmin()
{
$tmp=array();
    foreach ($this->localCache['mail'] AS $mail => $isAdmin)
    {
        if ($isAdmin['a'])
        {
            $tmp[] = $mail;
        }
    }
return $tmp;
}


/**
 * 
 * Retourne le texte du bouton
 */
public function getDisplayTextForm(){
return $this->localCache['textForm'];
}


/**
 *   Recuperation du statut du client
 *   Recherche de l'adresse email dans le cachhe
 */
public function getUserStatus($mail) {
return (array_key_exists($mail, $this->localCache['mail'])) ? $this->localCache['mail'][$mail] : false;
}

/**
 * 
 * Savoir si on affiche ou pas le temps restant avant ouverture
 * @return bool
 */
public function getDisplayTime() { return $this->localCache['display_delay']; }




/*
*   SETTER
*/

/**
 * 
 * Enregistre le statut 
 * @param bool $status
 */
public function setStatus($status) { $this->localCache['status'] = ($status) ? 'enabled' : 'disabled'; }


/**
 * 
 * Indique le titre de la page
 * @note non implémenté
 * @param string $myTitle
 */
public function setTitle($myTitle) { $this->localCache['title'] = $myTitle; }


/**
 * 
 * Indiquer le message affiché
 * @param unknown_type $myDescription
 */
public function setMessage($myDescription) { $this->localCache['message'] = $myDescription; }


/**
 * 
 * Indique le temps en seconde avant ouverture
 * @param int $timestamp
 */
public function setTime2open($timestamp) { $this->localCache['time2open'] = (int) $timestamp; }


/**
 * 
 * Definit si le temps avant ouverture est affiche ou pas
 * @param bool $boolean
 */
public function setDisplayTime($boolean) { $this->localCache['display_delay'] = (boolean) $boolean; }


/**
 * 
 * Definit le texte du bouton
 * @param unknown_type $textForm
 */
public function setDisplayTextForm($textForm) { $this->localCache['textForm'] = $textForm; }


/**
 *   Ajout d'une adresse e-mail dans le cache
 *   Boolean
 */
public function addMail($mail, $isAdmin=false)
{
	
	$mailJetable = library ( 'mailjetable' );
	$mail = strtolower ( $mail ) ;
	$explode = explode ( '@', $mail);
	
	$item = (isSet($explode [1])) ? $explode [1] : 'yopmail.com';
	if (( bool ) array_search ($item, $mailJetable ))
	{
		return false;
	}
	
	if (! filter_var ( $mail, FILTER_VALIDATE_EMAIL ))
	{
		return false;
	}
	
	
    $isAdmin = (!count($this->localCache['mail']) && $isAdmin == false) ? true : $isAdmin;

	$this->localCache['mail'][$mail] = array(
            'a' => $isAdmin);   // isAdmin or VIP xD


    return true;
}


/**
 * 
 * Recupération du tableau d'information pour stockage
 */
public function getParam() { return $this->localCache; }


/**
 * 
 * Affichage du tableau de stockage
 */
public function debug() { return $this->localCache; }

}