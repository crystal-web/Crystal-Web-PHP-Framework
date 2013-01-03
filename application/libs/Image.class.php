<?php
class Image {
/**
* @title Image manipulation
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* Doc http://mtodorovic.developpez.com/php/gd/
*/

// File image 
private $file;
private $mime;

// File extention
private $ext;	

// File size
private $image_width;
private $image_height;

// File size convert
private $to_width=false;
private $to_height=false;


// id to Extention

//private $types = array(1 => 'gif', 2 => 'jpeg', 3 => 'png', 4 => 'SWF', 5 => 'psd', 6 => 'bmp', 7 => 'tiff', 8 => 'tiff', 9 => 'jpc', 10 => 'jp2', 11 => 'jpx', 12 => 'jb2', 13 => 'swc', 14 => 'iff');
private $types = array(1 => 'gif', 2 => 'jpeg', 3 => 'png');

// Quality 
private $quality = 80;

// Poid
private $file_size;
private $file_size_ko;

// crop
private $top = 0;
private $left = 0;
private $crop = false;
private $show;

private $merge_parm=array();

private $error=array();

public $log;

private $dir=NULL;

public $name2save;


public function __construct($file)
{
	if (!file_exists($file))
	{ 
		Log::setLog('404 file not found', 'Image'); 
		return false; 
	}

	
	if (! extension_loaded('gd'))
	{
		Log::setLog('1100 GB Library not load', 'Image'); 
		return false;
	}


	/* Enregistrement des données
	/***************************/
	
	// Set file image 
	$this->file = $file;
	Log::setLog('Set file image : "'.$this->file.'"', 'Image');
	
	// Get image info
	$info = getimagesize($this->file);
	Log::setLog('Get image info : width '.$info[0].'px height '.$info[1] . 'px', 'Image');
	
	// Set Image info
	$this->image_width = $info[0];
	$this->image_height = $info[1];
	
		if (!array_key_exists($info[2], $this->types))
		{ 
			Log::setLog('1000 Unautorized extention', 'Image');
			return false; 
		}
	
	// Recherche l'extention et du type
	$this->ext=$this->types[$info[2]];
	Log::setLog('Extention type : '.$this->ext, 'Image');
	
	$this->mime=image_type_to_mime_type($info[2]);
	Log::setLog('Mime type : '.$this->mime, 'Image'); 
	
	// Poids de l'image
	$this->file_size = filesize($this->file); //récupération de la taille en octets
	$this->file_size_ko = round($this->file_size/1024); //conversion en ko
	Log::setLog('File size : '.$this->file_size_ko . ' ko', 'Image');
	return true;
}


public function check_gb()
{
	if (function_exists('gd_info'))
	{
		$ver_info = gd_info();
		preg_match('/\d/', $ver_info['GD Version'], $match);
		Log::setLog('GB version '.$ver_info['GD Version'], 'Image');
		return $match[0];
	}
}

/**
 * 
 * Paramettre de qualiter d'image, en particulier pour les jpeg
 * @param int $quality
 */
function quality($quality=80)
{
	$this->quality = $quality;
	Log::setLog('Quality JPG change to ' . $this->quality . '%', 'Image');
}


public function getExt()
{
	return $this->ext;
}


/**
 * 
 * Spécification de la largeur de l'image
 * @param int $width
 */
function width($width=false)
{
	$this->to_width = $width;
	Log::setLog('Image width out ' . $this->to_width, 'Image');
}


/**
 * 
 * Spécification de la lauteur de l'image
 * @param int $height
 */
function height($height=false)
{
	$this->to_height = $height;
	Log::setLog('Image height out ' . $this->to_height, 'Image');
}


/**
 * 
 * Pour modifier au pourcentage l'image
 * @param int $percentage
 * @param b $crop
 */
function resize($percentage=50, $crop = false)
{
	Log::setLog('Resize to '.$percentage.'%', 'Image');

	if($crop)
	{
		$this->to_width = round($this->to_width*($percentage/100));
		$this->to_height = round($this->to_height*($percentage/100));
		$this->image_width = round($this->to_width/($percentage/100));
		$this->image_height = round($this->to_height/($percentage/100));
	}
	else
	{
		$this->to_width = round($this->image_width*($percentage/100));
		$this->to_height = round($this->image_height*($percentage/100));
	}

	Log::setLog('New size width ' . $this->to_width . 'px height ' . $this->to_height.'px', 'Image');
}


/**
 * 
 * Indique le dossier ou sera sauvegarder l'image, si le dossier n'hesiste pas on essayé de le créer
 * @param string $dir_to_save
 * @return bool
 */
public function setdir($dir_to_save)
{
	Log::setLog('setdir to ' . $dir_to_save, 'Image');

	if (!is_dir($dir_to_save))
	{
	Log::setLog('Dir not exist make it', 'Image');

		if ($this->mkdir($dir_to_save)==false)
		{
			return false;
		}
	}

	$this->dir = $dir_to_save;
	return true;
}





/*****************************
*
*	Image fonction 
*
******************************/

function text($string)
{
	Log::setLog('text not implemented', 'Image');
}


/*** Application, du logo ***/
private $logo=false;
function logo($logo, $position='down_right')
{
	if (!file_exists($logo))
	{ 
		Log::setLog('LOGO 404 file not found', 'Image'); 
		return false; 
	}

	$info = getimagesize($logo);
	Log::setLog('Get logo info : width '.$info[0].'px height '.$info[1] . 'px', 'Image');


	// Set Logo info
	$this->logo['file'] = $logo;
	$this->logo['width'] = $info[0];
	$this->logo['height'] = $info[1];

	if (!array_key_exists($info[2], $this->types))
	{ 
		Log::setLog('1000 Unautorized extention logo', 'Image'); 
		$this->logo=false;
		return false; 
	}

	$this->logo['position']=$position;
	// Recherche l'extention et du type
	$this->logo['ext']=$this->types[$info[2]];
	Log::setLog('Extention type : '.$this->logo['ext'], 'Image'); 
}


/**
 * 
 * Demande l'affichage directement dans le script
 */
public function show()
{
	Log::setLog('Show enable', 'Image');
	$this->show=true;
}


/**
 * 
 * Sauvegarde l'image ou l'affiche si $this->show() est appeler
 * @param string $name2save
 */
public function save($name2save=null)
{
	if (!is_null($name2save))
	{
		$this->name2save = $this->dir.'/'.$name2save;
		Log::setLog('Set save file to ' .$this->name2save, 'Image');
	}
	
	if($this->show)
	{
		@header('Content-Type: '.$this->mime);
	}

	
	
	
	// Si on aucun redimensionnement n'est demandé
	if(!$this->to_width && !$this->to_height)
	{
		// On donne la taille original
		$this->to_width = $this->image_width;
		$this->to_height = $this->image_height;
		Log::setLog('Save with this size '.$this->to_width.'/'.$this->to_height, 'Image');
	}
	// Si on connais la largeur et pas la hauteur
	elseif (is_numeric($this->to_width) && $this->to_height==false)
	{
		// On attribue le ratio
		$ratio = ( $this->to_width/$this->image_width );
		Log::setLog('Calcul ratio '.$ratio, 'Image');

		$this->to_height = ceil( $this->image_height * $ratio );	
		Log::setLog('Save with this size '.$this->to_width.'/'.$this->to_height, 'Image');

	// Si on connais la hauteur et pas la largeur
	}
	elseif (is_numeric($this->to_height) && $this->to_width==false)
	{

		// On attribue le ratio
		$ratio = ( $this->to_height/$this->image_height );
		Log::setLog('Calcul ratio '.$ratio, 'Image');
		
		$this->to_width = ceil( $this->image_width * $ratio );	
		Log::setLog('Save with this size '.$this->to_width.'/'.$this->to_height, 'Image');
	}

	
	
	switch($this->ext)
	{
		case 'jpeg':
		$image = imagecreatefromjpeg($this->file);
		Log::setLog('imagecreatefromjpeg', 'Image');
		break;
		case 'jpg':
		$image = imagecreatefromjpeg($this->file);
		Log::setLog('imagecreatefromjpeg', 'Image');
		break;
		
		case 'png':
		$image = imagecreatefrompng($this->file);
		Log::setLog('imagecreatefrompng', 'Image');
		break;
		case 'gif':
		$image = imagecreatefromgif($this->file);
		Log::setLog('imagecreatefromgif', 'Image');
		break;
	}

	$new_image = imagecreatetruecolor($this->to_width, $this->to_height);
	Log::setLog('image true color', 'Image');


	$red = imagecolorallocate($new_image, 255, 0, 0);
	$black = imagecolorallocate($new_image, 0, 0, 0);
	$index = imagecolorexact($new_image, 255, 255, 255); 
	$trans = imagecolorallocatealpha($new_image, 255, 255, 255, 0);
	imagecolortransparent($new_image, $trans); 	

	imagecopyresampled($new_image, $image, 0, 0, $this->top, $this->left, $this->to_width, $this->to_height, $this->image_width, $this->image_height);

	/*** App Logo ***
	Creation du logo sur l'image retravailler
	***/
	
	if ($this->logo!=false)
	{
	// Le logo est la source

		if($this->logo['ext']=='jpeg' or $this->logo['ext']=='jpg')
		{
			$source = imagecreatefromjpeg($this->logo['file']);
			Log::setLog('Load logo form jpg', 'Image');
		}

		if($this->logo['ext']=='png')
		{
			$source = imagecreatefrompng($this->logo['file']);
			Log::setLog('Load logo form png', 'Image');
		}

		if($this->logo['ext']=='gif')
		{
			$source = imagecreatefromgif($this->logo['file']);
			Log::setLog('Load logo form gif', 'Image');
		}

		// On veut placer le logo en bas à droite, on calcule les coordonnées où on doit placer le logo sur la photo
		
		switch ($this->logo['position'])
		{
		case 'down_right':
		$destination_x = $this->to_width - $this->logo['width'];
		$destination_y = $this->to_height - $this->logo['height'];
		break;

		case 'down_left':
		$destination_x = 0;
		$destination_y = $this->to_height - $this->logo['height'];
		break;

		case 'up_right':
		$destination_x = $this->to_width - $this->logo['width'];
		$destination_y = 0;
		break;

		case 'up_left':
		$destination_x = 0;
		$destination_y = 0;
		break;
		
		default:
		$destination_x = $this->to_width - $this->logo['width'];
		$destination_y = $this->to_height - $this->logo['height'];
		break;
		}

		// On met le logo (source) dans l'image de destination (la photo)
		imagecopymerge($new_image, $source, $destination_x, $destination_y, 0, 0, $this->logo['width'], $this->logo['height'], 100);
	}

	
	if (!is_null($this->name2save))
	{
	//$info=explode(".", $this->name2save);
	$info=pathinfo($this->name2save,PATHINFO_EXTENSION);

		if($info=='jpeg' or $info=='jpg')
		{
			imagejpeg($new_image, $this->name2save, $this->quality);
			Log::setLog('Save form jpg', 'Image');
		}
		if($info=='png')
		{
			imagepng($new_image, $this->name2save);
			Log::setLog('Save form png', 'Image');
		}
		if($info=='gif')
		{
			imagegif($new_image, $this->name2save);
			Log::setLog('Save form gif', 'Image');
		}
		
		chmod($this->name2save, 0777);
		Log::setLog('ChMod file "' . $this->name2save.'"', 'Image');
		
	}
	else
	{

		if($this->ext=='jpeg' or $this->ext=='jpg')
		{
			
			if (!$this->show)
			{
				ob_start();
				imagejpeg($new_image, $this->name2save, $this->quality);
				$jpegString = ob_get_contents();
				ob_end_clean();
				return 'data:image/jpeg;base64,' . base64_encode($jpegString);
			}
			else
			{
				imagejpeg($new_image, $this->name2save, $this->quality);
			}
		}
		
		if($this->ext=='png')
		{
			if (!$this->show)
			{
				ob_start();
				imagepng($new_image, $this->name2save);
				$pngString = ob_get_contents();
				ob_end_clean();
				return 'data:image/png;base64,' . base64_encode($pngString);
			}
			else
			{
				imagepng($new_image, $this->name2save);
			}
			
		}
		
		if($this->ext=='gif')
		{
			if (!$this->show)
			{
				ob_start();
				imagegif($new_image, $this->name2save);
				$gifString = ob_get_contents();
				ob_end_clean();
				return 'data:image/gif;base64,' . base64_encode($gifString);
			}
			else
			{
				imagegif($new_image, $this->name2save);
			}
		}		
	}

	if ($this->logo!=false)
	{
		imagedestroy($source);
	}
	
	imagedestroy($image); 
	imagedestroy($new_image);

	// var desctruct
	$this->to_width=false;
	$this->to_height=false;
	$this->quality = 80;
	$this->left = 0;
	$this->top = 0;
	$this->dir = NULL;
}





/****************
*
*	Systeme fonction
*
*****************/

/**
 * 
 * Conversion de rgb vers hex et de hex vers rgb
 * @param string $c
 */
private function rgb2hex2rgb($c = false)
{ 
	if(!$c) { return false; }
	$c = trim($c); 
	$out = false;
	 
	if(preg_match("/^[0-9ABCDEFabcdef\#]+$/i", $c))
	{ 
	$c = str_replace('#','', $c); 
	$l = strlen($c) == 3 ? 1 : (strlen($c) == 6 ? 2 : false); 
	
		if($l)
		{
			 unset($out); 
			 $out[0] = $out['r'] = $out['red'] = hexdec(substr($c, 0,1*$l)); 
			 $out[1] = $out['g'] = $out['green'] = hexdec(substr($c, 1*$l,1*$l)); 
			 $out[2] = $out['b'] = $out['blue'] = hexdec(substr($c, 2*$l,1*$l)); 
		} else { $out = false; }
	}
	elseif (preg_match("/^[0-9]+(,| |.)+[0-9]+(,| |.)+[0-9]+$/i", $c))
	{ 
		$spr = str_replace(array(',',' ','.'), ':', $c); 
		$e = explode(":", $spr); 

		if(count($e) != 3)
		{
			return false;
		}
		
		$out = '#';
		for($i = 0; $i<3; $i++)
		{
			$e[$i] = dechex(($e[$i] <= 0)?0:(($e[$i] >= 255)?255:$e[$i])); 
		}

		for($i = 0; $i<3; $i++)
		{
		$out .= ((strlen($e[$i]) < 2)?'0':'').$e[$i]; 
		}
		
	$out = strtoupper($out); 
	} else { $out = false; } 

return $out; 
}


/**
 * 
 * Création d'un string aléatoire utilisé notament pour les nom de fichier
 * @param unknown_type $length
 */
private function generateRandomString($length = 5) 
{ 
	Log::setLog('Generate Random filename', 'Image');
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


/**
 * 
 * Modification du nom de fichier lorsque celui-ci existe
 * @param string $dir_to_save
 */
private function rename($dir_to_save)
{
	Log::setLog('Auto rename', 'Image');
	$rename = $dir_to_save.'/'.$this->generateRandomString().$this->tmp_file;
	return (file_exists($rename)) ? $dir_to_save.'/'.$this->generateRandomString(6).$this->tmp_file : $rename;
}


/**
 * 
 * Création de l'arboresence automatique
 * @param string $dir_to_save
 */
private function mkdir($dir_to_save)
{
Log::setLog('Make dir ' . $dir_to_save, 'Image');
$dir_array = explode('/', $dir_to_save);
$last = NULL;

	foreach ($dir_array AS $key => $dir)
	{
	$last=$last.$dir.'/';

		if (!is_dir($last))
		{
			if (mkdir($last, 0777))
			{
				Log::setLog('Cr&eacute;ation du dossier "' . $last . ' effectu&eacute;"', 'Image');
			}
			else
			{
				Log::setLog('Cr&eacute;ation du dossier "' . $last . ' a &eacute;chou&eacute;...', 'Image');
				return false;
			}
		}
	}
return true;
}


}