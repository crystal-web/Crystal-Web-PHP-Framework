<?php

class Upload{
private $file;					// Fichier uploader $_FILES['name']
private $tmp = './files/tmp/';	// Dossier temporaire
public $tmp_file;

public $ext;						// Extention
private $extBlacklist = array(
	# HTML may contain cookie-stealing JavaScript and web bugs
	'html',
	'htm',
	'js',
	'jsb',
	'mhtml',
	'mht',
	# PHP scripts may execute arbitrary code on the server
	'php',
	'phtml',
	'php3',
	'php4',
	'php5',
	'phps',
	# Other types that may be interpreted by some servers
	'shtml',
	'jhtml',
	'pl',
	'py',
	'cgi',
	# May contain harmful executables for Windows victims
	'exe',
	'scr',
	'dll',
	'msi',
	'vbs',
	'bat',
	'com',
	'pif',
	'cmd',
	'vxd',
	'cpl'
);
private $MimeTypeBlacklist = array(
	# HTML may contain cookie-stealing JavaScript and web bugs
	'text/html',
	'text/javascript',
	'text/x-javascript',
	'application/x-shellscript',
	# PHP scripts may execute arbitrary code on the server
	'application/x-php',
	'text/x-php',
	# Other types that may be interpreted by some servers
	'text/x-python',
	'text/x-perl',
	'text/x-bash',
	'text/x-sh',
	'text/x-csh',
	'text/x-shellscript',
    # Client-side hazards on Internet Explorer
	'text/scriptlet',
	'application/x-msdownload',
    # Windows metafile, client-side vulnerability on some systems
	'application/x-msmetafile',
	# A ZIP file may be a valid Java archive containing an applet which exploits the
	# same-origin policy to steal cookies
	'application/zip',
	'application/x-opc+zip',
	'application/msword',
	'application/vnd.ms-powerpoint',
	'application/vnd.msexcel',
);
private $mime;
public $save_as;				// Nom du fichier de sortie
private $upload_max_filesize;	// Taille en octe maximum des fichiers uploader
private $strictMode = true;
public $log=array();			// log ;-)


	/**
	 * Récupération de $_FILES
	 * @param $file the file in $_FILE['name']
	 * @param $strict bool use strict rules
	 */
	public function __construct($file, $strict=true)
	{
		$this->strictMode = $strict;
		$this->file = $file;
		$this->tmp_file = basename($this->file['name']);
		$this->tmp_file = clean($this->tmp_file, 'str'); 
	}

	/**
	 * Set the temporay directory
	 * @param $dir string path to temp directory
	 * @return $this
	 */
	public function setTempDir($dir)
	{
		if (!is_dir($dir))
		{
			throw new Exception('$dir is not a directory', 1);
		}
		return $this;
	}
	
	
	/**
	 * 
	 * Preparation de l'upload
	 * @return bool
	 */
	public function prepare()
	{
		Log::setLog('File is ' . $this->tmp_file, 'Upload');
		
		$this->ext = strtolower(strrchr($this->file['name'], '.'));
		Log::setLog('File extention is ' . $this->ext, 'Upload');
		
		if (strlen($this->file['tmp_name']) == 0)
		{
			Log::setLog('Pas de fichier', 'Upload');
			return false;
		}
		
		/*
		 * Test de l'extention
		 */
		if (function_exists('finfo_open'))
		{
			Log::setLog('Recherche du mime-type par finfo', 'Upload');
			$finfo = finfo_open(FILEINFO_MIME_TYPE); // Retourne le type mime à l'extension mimetype
			$this->mime = finfo_file($finfo, $this->file['tmp_name']);
			Log::setLog('Mime-type est ' . $this->mime, 'Upload');
			finfo_close($finfo);
		}
		elseif (function_exists('mime_content_type'))
		{
			Log::setLog('Recherche du mime-type par mime_content_type', 'Upload');
			$this->mime = mime_content_type($this->file['tmp_name']);
			Log::setLog('Mime-type est ' . $this->mime, 'Upload');
		}
		else
		{
			$this->mime = $this->file['type'];
			Log::setLog('/!\ Serveur insuffisament s&eacute;ris&eacute; : Requ&egrave;te Mime-Type non trait&eacute;', 'Upload');
		}
		
		if ($this->strictMode)
		{
			if ($this->isBlacklisted())
			{
				throw new Exception('File type is blacklisted', 2);
			}
		}
		
		return $this->move();
	}


	/**
	 * 
	 * Test si le fichier est blacklister
	 */
	public function isBlacklisted()
	{
		/*
		 * Controle si l'extention est blacklisté
		 */
		if ( array_search($this->mime, $this->MimeTypeBlacklist) !== false )
		{
			Log::setLog('Mime-type, blacklister  ('.$this->mime.')', 'Upload');
			return true;
		}
		elseif ( array_search(trim($this->ext, '.'), $this->extBlacklist) !== false )
		{
			Log::setLog('Extention, blacklister ('.$this->ext.')', 'Upload');
			return true;
		}
		else
		{
			Log::setLog('Extention et mime-type, non-blacklister  ('.$this->ext.')', 'Upload');
			return false;
		}		
		
	}


	/**
	 * Test si le mime-type est dans la whitelist
	 * @param $aListExtent array ext, ext, ext 
	 */
	public function isWhitelisted($aListExtent)
	{
		/*
		 * Controle si l'extention est blacklisté
		 */
		if ( array_search(strtolower(trim($this->ext, '.')), $aListExtent) !== false )
		{
			Log::setLog('Extention, whitelister ('.$this->ext.')', 'Upload');
			return true;
		}
		elseif ( array_search(strtolower($this->ext), $aListExtent) !== false )
		{
			Log::setLog('Extention, whitelister ('.$this->ext.')', 'Upload');
			return true;
		}
		else
		{
			Log::setLog('Extention, non-whitelister  ('.$this->ext.')', 'Upload');
			return false;
		}		
	}


	/**
	 * 
	 * Deplace le fichier du tampon, vers le dossier temporaire du systeme
	 */
	public function move()
	{
		//Si la fonction renvoie TRUE, c'est que ça a fonctionné...
		if(move_uploaded_file($this->file['tmp_name'], $this->tmp . $this->tmp_file)) 
		{
			Log::setLog( 'Upload effectu&eacute; avec succ&egrave;s ! ' . $this->tmp . $this->tmp_file, 'Upload');
			return true;
		}
		else //Sinon (la fonction renvoie FALSE).
		{
			Log::setLog( 'Echec de l\'upload ! Vérifié que ' . $this->tmp . ' soit accessible en écriture', 'Upload');
			return false;
		}
	}
	

	/**
	 *
	 * Alias pour save()
	 * @see save() 
	 * @param string $dir_to_save
	 * @return bool
	 */
	public function setDirToSave($dir)
	{
		return $this->save($dir);
	}


	/**
	 * 
	 *  Enregistrement du fichier
	 * @param string $dir_to_save
	 * @return bool
	 */
	public function save($dir_to_save)
	{
		if (!is_dir($dir_to_save))
		{
			Log::setLog( 'Dossier "' . $dir_to_save . ' n\'existe pas" essaye de créer...', 'Upload');

			if ($this->mkdir($dir_to_save)==false)
			{
				Log::setLog( 'Echec de la création du dossier "' . $dir_to_save, 'Upload');
				return false;
			}
		}
	
		$this->save_as = $dir_to_save.'/'.$this->clean($this->tmp_file);

		if (file_exists($this->save_as))
		{
			$this->save_as = $dir_to_save.'/'.rand().'-'.$this->clean($this->tmp_file);
		}
		
		if (!copy($this->tmp . $this->tmp_file, $this->save_as))
		{
			Log::setLog( 'La copie  du fichier "' . $this->save_as . '" a &eacute;chou&eacute;...' , 'Upload');
			return false;
		}
		else
		{
			Log::setLog( 'La copie  du fichier "' . $this->save_as . '" effectu&eacute;...' , 'Upload');
			return true;
		}
	
	}
	
	/***************************************************/
	/*	Private
	/***************************************************/

	
	/**
	 * 
	 * Crée l'arboressence des dossiers recursivement
	 * @param string $dir_to_save
	 */
	private function mkdir($dir_to_save)
	{
	$dir_array = explode('/', $dir_to_save);
	$last = NULL;
	
		foreach ($dir_array AS $key => $dir)
		{
		$last=$last.$dir.'/';
	
			if (!is_dir($last))
			{
	
				if (mkdir($last, 0777))
				{
				Log::setLog( 'Cr&eacute;ation du dossier "' . $last . ' effectu&eacute;"', 'Upload');
				}
				else
				{
				Log::setLog( 'Cr&eacute;ation du dossier "' . $last . ' a &eacute;chou&eacute;...', 'Upload');
				return false;
				}
				
			}
	
		}
	return true;
	}


	private function clean($string)
	{
		if (get_magic_quotes_gpc()) {
			$string = htmlentities($string, ENT_NOQUOTES, 'utf-8');
		}
		else {
			$string = htmlentities(addslashes($string), ENT_NOQUOTES, 'utf-8');
		}

		$string = preg_replace ( '#\&([A-Za-z])(?:grave|acute|circ|tilde|uml|ring|cedil)\;#', '\1', $string );
		$string = preg_replace ( '#\&([A-Za-z]{2})(?:lig)\;#', '\1', $string );
		$string = preg_replace ( '#\&([A-Za-z])(.*)\;#', '', $string );
		$string = str_replace ( "'", '-', $string );
		$string = str_replace ( ' ', '-', $string );
		$string = preg_replace ( '#[^A-Za-z0-9_\-\.]#', '', $string );
		$string = str_replace ( '--', '-', $string );
		$string = trim ( $string, '-' );
		return $string;
	}

	private function generateRandomString($length = 5) 
	{    
	    $string = ""; 
	    
	    //character that can be used 
	    $possible = "0123456789bcdfghjkmnpqrstvwxyz"; 
	    
	    for($i=0;$i < $length;$i++) 
	    { 
	        $char = substr($possible, rand(0, strlen($possible)-1), 1); 
	        
	        if (!strstr($string, $char)) 
	        { 
	            $string .= $char; 
	        } 
	    } 
	
	    return $string; 
	}
	
	
	/*** Renomage du fichier ***/
	private function rename($dir_to_save)
	{
		$rename = $dir_to_save.'/'.$this->generateRandomString().$this->tmp_file;
		return (file_exists($rename)) ? $dir_to_save.'/'.$this->generateRandomString(6).$this->tmp_file : $rename;
	}

	
	/***************************************************/
	/*	Getter
	/***************************************************/
	
	
	public function get_upload_max_filesize()
	{
		$max_filesize = ini_get('upload_max_filesize');
		$mul = substr($this->upload_max_filesize, -1);
		$mul = ($mul == 'M' ? 1048576 : ($mul == 'K' ? 1024 : ($mul == 'G' ? 1073741824 : 1)));
		return $mul*(int)$max_filesize;
	}


	/**
	 * 
	 * Retourne le mime type
	 */
	public function getMime() {return $this->mime;}
	
	
	/**
	 * 
	 * Retourne le chemin vers le fichier temporaire
	 */
	public function getUploadPath(){return $this->tmp . $this->tmp_file;}
	
	
	/**
	 * 
	 * Retourne le nom du fichier uploader
	 */
	public function getFileName() {return $this->tmp_file; }


	public function __destruct()
	{
		Log::setLog( 'Effacement du tampon', 'Upload');
		if (file_exists($this->tmp . $this->tmp_file) && !is_dir($this->tmp .$this->tmp_file))
		{
			@unlink($this->tmp .$this->tmp_file);
		}
	}
	
	
	/***************************************************/
	/*	Deprecated
	/***************************************************/

	/**
	 * @deprecated utilisé isWhitelisted($aListExtent)
	 */
	public function controlExtWhiteList($arrayWhiteList=array('.png','.jpeg','.jpg','.gif'))
	{
		return $this->isWhitelisted($arrayWhiteList);
	}

	
	/**
	 * @deprecated
	 * Controle le type mime
	 */
	public function control($extAttendu)
	{
		// Control BlackListage
		if (in_array($this->mime, $this->MimeTypeBlacklist))
		{
		Log::setLog('@deprecated BlackListed MineType', 'Upload');
		return false;
		}
	Log::setLog('@deprecated Controle extention : type is '.$this->ext, 'Upload');
	return in_array($this->ext, $extAttendu);
	}
	
	
	/**
	 * @deprecated
	 * Controle le type mime
	 */	
	public function control_ext($extAttendu)
	{
		Log::setLog('@deprecated controle_ext', 'Upload');
		return $this->control($extAttendu);
	}
	
	
}

/***********************-= USAGE =-***************************
// Mode strict 

if (isSet($_FILES['meFile']))
{
    $file = $_FILES['meFile'];
	if (!empty($file['name']))
	{
		try
		{
		$up = new Upload($file, true);
		    if ($up->prepare())
		    {
		    	if ($up->controlExtWhiteList(array('.png','.jpeg','.jpg','.gif')))
		    	{
		    		// File uploaded and controlled
		    		if ($up->save('path/to/dir/fileName.ext'))
					{
						
					}
				}
			}
		} catch (exception $e) {
			// File blacklisted
		}
	}
}
*/
?>
