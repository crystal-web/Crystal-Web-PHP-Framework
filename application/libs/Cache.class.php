<?php
/**
* @title Cache systeme
* @package systeme
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @documentation: http://www.crystal-web.org/viki/class-cache
*/

/* Le but ici est de minimizer les acces a la BDD en utilisant le cache 
 * L'utilisation complique légérement le développement, 
 * une bonne connaissance des array est primordial on peut aussi stocker du texte brute
 * PS cette class manque de class xD 
 */

class Cache
{
public $tmpCache=false;
private $filename;
private $toCache;
private $output=NULL;
private $type;
private $errno;
public $stat;
 

	/**
	 * 
	 * Constructeur de class, ouvre un nouveau cache de $filename
	 * $filename correspond au non du cache
	 * $toCache permet de pre-enregistré un cache
	 * $type permet de mettre dans un dossier au dessus
	 * @param string $filename
	 * @param $toCache
	 * @param string $type
	 * @throws Exception
	 */
	public function Cache($filename, $toCache = NULL, $type = NULL){
	$this->filename = __SQL. '_' .$filename;
	$this->toCache = $toCache;
	$this->type = (!empty($type)) ? $type . DS : NULL;
	Log::setLog('Starting Cache with file ' . $filename, 'Cache');
	
	    // On test si c'est un dossier
	    if(is_dir(__APP_PATH . DS . 'cache' . DS . $type)==false)
	    {
	    Log::setLog('Is a directory ' . __APP_PATH . DS . 'cache' . DS . $type, 'Cache');
	    @mkdir(__APP_PATH . DS . 'cache' . DS . $type);
	    // Si pas on essaye de le cr�er
	    $its_ok=chmod(__APP_PATH . DS . 'cache' . DS . $type, '0777');
	        // Si une erreur on return false
	        if ($its_ok==false){
	            throw new Exception('Impossible de cr&eacute;er le dossier');
	        } else {Log::setLog($filename . ' is Chmod (wre)', 'Cache');}
	    }
	
	    if (file_exists(__APP_PATH . DS . 'cache' . DS .$this->type . $this->filename . '.cache'))
	    {
	        $this->stat = @alt_stat(__APP_PATH . DS . 'cache' . DS . $this->type . $this->filename . '.cache');
	        Log::setLog('Stat file value <div style="display:inline"><input type="button" value="Afficher" style="width:45px;font-size:10px;margin:0px;padding:0px;" onclick="if (this.parentNode.parentNode.getElementsByTagName(\'div\')[1].getElementsByTagName(\'div\')[0].style.display != \'\') { this.parentNode.parentNode.getElementsByTagName(\'div\')[1].getElementsByTagName(\'div\')[0].style.display = \'\';        this.innerText = \'\'; this.value = \'Cacher\'; } else { this.parentNode.parentNode.getElementsByTagName(\'div\')[1].getElementsByTagName(\'div\')[0].style.display = \'none\'; this.innerText = \'\'; this.value = \'Afficher\'; }"></div>
	        <div class="quotecontent"><div style="display: none;"><pre>' . print_r($this->stat, true).'</pre></div></div>', 'Cache');
	    }
	
	}

	
	/**
	 * 
	 * Ecrit dans le fichier de cache la valeur de $arr sans traitement
	 * @param $arr
	 * @throws Exception
	 */
	public function setCache($arr = NULL)
	{
	$arr = ($arr == NULL) ? $this->toCache : $arr;
	
	    // écriture du code dans le fichier.
	    if (file_put_contents(__APP_PATH . DS . 'cache' . DS . $this->type . $this->filename . '.cache', serialize($arr), LOCK_EX) === false)
	    {
	        throw new Exception('Impossible d\'&eacute;crire le fichier cache');
	    }
	    else
	    {
	    	Log::setLog('Set to cache file ' . $this->filename, 'Cache');
	        // Renvoie true si l'�criture du fichier a r�ussi.
	        return true;
	    }
	}
	
	
	/**
	 * 
	 * Retourne le cache sans traitement, par defaut retourne un tableau vide
	 * @param bool $return_bool
	 */
	public function getCache($return_bool = false)
	{
	// V�rifie que le fichier de cache existe.
	    if (is_file(__APP_PATH . DS . 'cache' . DS . $this->type . $this->filename . '.cache'))
	    {
	    Log::setLog('Read file ' . $this->filename, 'Cache');
		$this->tmpCache = file_get_contents(__APP_PATH . DS . 'cache' . DS . $this->type . $this->filename . '.cache');
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


	/**
	 * 
	 * Supprime le fichier de cache courant, si il existe et que la suppréssion est possible
	 */
	public function delCache()
	{
	    if (file_exists(__APP_PATH . DS . 'cache' . DS .$this->type . $this->filename . '.cache'))
	    {
	    	Log::setLog('Delete file ' . $this->filename, 'Cache');
		    @chmod(__APP_PATH . DS . 'cache' . DS . $this->type . $this->filename . '.cache',0777);
		    @unlink(__APP_PATH . DS . 'cache' . DS . $this->type . $this->filename . '.cache');
		    return true;
	    }
	    else
	    {
	    	return false;
	    }		
	}

	
	/**
	 * 
	 * Recherche si un mot, une lettre se trouve dans la source sérializé
	 * a utiliser avec prudence, cette requète peut retourner des valeurs érroné
	 * @param string $string
	 */
	public function search($string)
	{
		Log::setLog('Search ' . $string, 'Cache');
		if ($this->tmpCache!=false)
		{
			$pos = strpos($this->tmpCache, $string);
			return $pos;
		}
		else
		{
			return false;
		}
	}

		
	/**
	 * 
	 * Retourne un tableau contenant la date d'acces, de modification et de création
	 * @return array
	 */
	public function getTime()
	{	
		return $this->stat['time'];
	}


	/**
	 * 
	 * Destruction du cache, libération de la mémoire
	 */
	public function __destruct() {
		Log::setLog('Erase session ' . $this->filename, 'Cache');
	        unset($this->filename);
	        unset($this->toCache);
	        unset($this->type);
	        unset($this->errno);
	}
}




?>