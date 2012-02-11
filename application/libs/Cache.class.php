<?php
/**
* @title Cache systeme
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/

/* Le but ici est de minimizer les acces a la BDD en utilisant le cache 
L'utilisation complique légèrement le développement, 
une bonne connaissance des array est primordial on peut aussi stocker du texte brute */

class Cache
{
public $tmpCache=false;
private $filename;
private $toCache;
private $output=NULL;
private $type;
private $errno;
public $stat;
 
public function Cache($filename, $toCache = NULL, $type = NULL){
$this->filename = $filename;
$this->toCache = $toCache;
$this->type = (!empty($type)) ? $type.'/' : NULL;

    // On test si c'est un dossier
    if(is_dir(__APP . '/cache/'.$type)==false)
    {
    @mkdir(__APP . '/cache/'.$type);
    // Si pas on essaye de le créer
    $its_ok=chmodDir(__APP . '/cache/'.$type);
        // Si une erreur on return false
        if ($its_ok['bool']==false){
            throw new Exception('Impossible de cr&eacute;er le dossier');
        }
    }

    if (file_exists(__APP . 'cache/'.$this->type . $this->filename . '.cache'))
    {
        $this->stat = stat(__APP . '/cache/'.$this->type . $this->filename . '.cache');
    }

}


/*
 * Set file cache
 */
public function setCache($arr = NULL)
{
$arr = ($arr == NULL) ? $this->toCache : $arr;
    // Écriture du code dans le fichier.
    if (file_put_contents(__APP . '/cache/'.$this->type . $this->filename . '.cache', serialize($arr), LOCK_EX) === false)
    {
        throw new Exception('Impossible d\'&eacute;crire le fichier cache');
    }
    else
    {
        // Renvoie true si l'écriture du fichier a réussi.
        return true;
    }
}
	
/*
 * Get file cache
 */
public function getCache($return_bool = false)
{
// Vérifie que le fichier de cache existe.
    if (is_file(__APP . '/cache/'.$this->type . $this->filename . '.cache'))
    {
	$this->tmpCache = file_get_contents(__APP . '/cache/'.$this->type . $this->filename . '.cache');
    $this->output = unserialize($this->tmpCache);
    return ($return_bool == false) ? $this->output : true;
    }
    else
    {
        if ($return_bool == false)
        {
        return (!empty($this->toCache)) ? $this->toCache : array();
        }
        else
        {
        return false;
        }
    }
}
/*
 * Delete file cache
 */
public function delCache()
{
    if (file_exists(__APP . '/cache/'.$this->type . $this->filename . '.cache'))
    {
    @chmod(__APP . '/cache/'.$this->type . $this->filename . '.cache',0777);
    @unlink(__APP . '/cache/'.$this->type . $this->filename . '.cache');
    return true;
    }
    else
    {
    return false;
    }		
}

public function search($string)
{
	if ($this->tmpCache!=false){
	$pos = strpos($this->tmpCache, $string);
	return $pos;
	}
	else
	{
	return false;
	}
}
	
/*
 * Get file time
 */
public function getTime()
{
if (file_exists(__APP . '/cache/'.$this->type . $this->filename . '.cache')) {
        return  filemtime(__APP . '/cache/'.$this->type . $this->filename . '.cache');
}
return 0;
}


/*
 *  Destruct var  
 */
public function __destruct() {
        unset($this->filename);
        unset($this->toCache);
        unset($this->type);
        unset($this->errno);
}
}
?>