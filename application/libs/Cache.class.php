<?php
/**
* @title Cache systeme
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
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
 

	public function Cache($filename, $toCache = NULL, $type = NULL){
	$this->filename = DB_USERNAME. '_' .$filename;
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

	
	/*
	 * Set file cache
	 */
	public function setCache($arr = NULL)
	{
	$arr = ($arr == NULL) ? $this->toCache : $arr;
	
	    // �criture du code dans le fichier.
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
	

	/*
	 * Get file cache
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


	/*
	 * Delete file cache
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

		
	/*
	 * Get file time
	 */
	public function getTime()
	{
		if (file_exists(__APP_PATH . DS . 'cache' . DS . $this->type . $this->filename . '.cache'))
		{
		return  filemtime(__APP_PATH . DS . 'cache' . DS . $this->type . $this->filename . '.cache');
		}
	return 0;
	}


	/*
	 *  Destruct var  
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