<?php

class Upload{
private $file;					// Fichier uploader $_FILES['name']
private $tmp = './files/tmp/';	// Dossier temporaire
public $tmp_file;

public $ext;						// Extention
private $extBlacklist = array(
  # HTML may contain cookie-stealing JavaScript and web bugs
  'html', 'htm', 'js', 'jsb', 'mhtml', 'mht',
  # PHP scripts may execute arbitrary code on the server
  'php', 'phtml', 'php3', 'php4', 'php5', 'phps',
  # Other types that may be interpreted by some servers
  'shtml', 'jhtml', 'pl', 'py', 'cgi',
  # May contain harmful executables for Windows victims
  'exe', 'scr', 'dll', 'msi', 'vbs', 'bat', 'com', 'pif', 'cmd', 'vxd', 'cpl' );
  private $MimeTypeBlacklist = array(
        # HTML may contain cookie-stealing JavaScript and web bugs
 'text/html', 'text/javascript', 'text/x-javascript',  'application/x-shellscript',
        # PHP scripts may execute arbitrary code on the server
 'application/x-php', 'text/x-php',
        # Other types that may be interpreted by some servers
 'text/x-python', 'text/x-perl', 'text/x-bash', 'text/x-sh', 'text/x-csh', 'text/x-shellscript',
        # Client-side hazards on Internet Explorer
 'text/scriptlet', 'application/x-msdownload',
        # Windows metafile, client-side vulnerability on some systems
 'application/x-msmetafile',
        # A ZIP file may be a valid Java archive containing an applet which exploits the
 # same-origin policy to steal cookies
 'application/zip',
 
 # MS Office OpenXML and other Open Package Conventions files are zip files
 # and thus blacklisted just as other zip files. If you remove these entries
 # from the blacklist in your local configuration, a malicious file upload
 # will be able to compromise the wiki's user accounts, and the user 
 # accounts of any other website in the same cookie domain.
 'application/x-opc+zip',
        'application/msword',
        'application/vnd.ms-powerpoint',
        'application/vnd.msexcel',
);
private $mime;
public $save_as;				// Nom du fichier de ortie
private $upload_max_filesize;	// Taille en octe maximum des fichiers uploader
public $log=array();			// log ;-)


	/*** Télécharge le fichier et le stock temporairement ***/
	public function __construct($file, $strict=true)
	{
		
		$this->file = $file;
		$this->tmp_file = basename($this->file['name']);
		$this->tmp_file = clean($this->tmp_file, 'str'); 

	}

	
	/**
	 * 
	 * Preparation de l'upload
	 * @return bool
	 */
	public function prepare()
	{
		
		#$this->tmp_file = preg_replace('/([^.a-z0-9]+)/i', '-', $this->tmp_file);
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
			$finfo = finfo_open(FILEINFO_MIME_TYPE); // Retourne le type mime à la extension mimetype
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
		
		
		//Si la fonction renvoie TRUE, c'est que ça a fonctionné...
		if(move_uploaded_file($this->file['tmp_name'], $this->tmp . $this->tmp_file)) 
		{
			Log::setLog( 'Upload effectu&eacute; avec succ&egrave;s ! ' . $this->tmp . $this->tmp_file, 'Upload');
			return true;
		}
		else //Sinon (la fonction renvoie FALSE).
		{
			Log::setLog( 'Echec de l\'upload ! Vérifié qut ' . $this->tmp . ' qoit accèssible en écriture', 'Upload');
			return false;
		}
		
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
	
	
	
	/**
	 * 
	 * Test si le fichier est blacklister
	 */
	public function isBlacklisted()
	{
		/*
		 * Controle si l'extention est blacklisté
		 */
		if ( array_search($this->mime, $this->MimeTypeBlacklist) )
		{
			Log::setLog('Mime-type, blacklister  ('.$this->mime.')', 'Upload');
			return true;
		}
		elseif ( array_search(trim($this->ext, '.'), $this->extBlacklist) )
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
	
	
	public function isWhitelisted($aListExtent)
	{
		/*
		 * Controle si l'extention est blacklisté
		 */
		if ( array_search(strtolower(trim($this->ext, '.')), $aListExtent) )
		{
			Log::setLog('Extention, whitelister ('.$this->ext.')', 'Upload');
			return true;
		}
		else
		{
			Log::setLog('Extention et mime-type, non-whitelister  ('.$this->ext.')', 'Upload');
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
		if(move_uploaded_file($this->file['tmp_name'], $this->tmp)) 
		{
			Log::setLog('Upload effectu&eacute; avec succ&egrave;s dans le dossier "'.$this->tmp.'" !', 'Upload');
			return true;
		}
		else //Sinon (la fonction renvoie FALSE).
		{
			Log::setLog('Echec de l\'upload !', 'Upload');
			return false;
		}
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
	
	

	/**
	 * 
	 * Crée l'arboressence des dossiers
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
	
				if (mkdir($last, 0775))
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
	
	$this->save_as = $dir_to_save.'/'.$this->tmp_file;
	

	
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




 public function controlExtWhiteList($arrayWhiteList=array('.png','.jpeg','.jpg','.gif'))
  {
	// Control Liste blanche
	if (in_array($this->ext, $arrayWhiteList))
	{
	Log::setLog( 'Listed extention', 'Upload');
	Log::setLog( 'Controle white list extention : type is '.$this->ext, 'Upload');
	return true;
	}return false;
}






public function get_upload_max_filesize()
{
$max_filesize = ini_get('upload_max_filesize');
$mul = substr($this->upload_max_filesize, -1);
$mul = ($mul == 'M' ? 1048576 : ($mul == 'K' ? 1024 : ($mul == 'G' ? 1073741824 : 1)));
return $mul*(int)$max_filesize;
}



public function __destruct()
{
Log::setLog( 'Effacement du tampon', 'Upload');
if (file_exists($this->tmp . $this->tmp_file) && !is_dir($this->tmp .$this->tmp_file)) {@unlink($this->tmp .$this->tmp_file);}
}


}
?>
