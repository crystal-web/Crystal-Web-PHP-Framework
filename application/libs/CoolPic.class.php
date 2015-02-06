<?php
/*##################################################
 *                             CoolPic.class.php
 *                            -------------------
 *   begin                : 2012-22-08
 *   copyright            : (C) 2012 DevPHP
 *   email                : developpeur@crystal-web.org
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/
Class CoolPic extends CoolPicTools{
	
	private $gdVersion = 1;
	private $file;
	public $extention;
	private $supportedExt = array('jpeg', 'jpg', 'gif', 'png');
	protected $imageSize = array();
		private $fileSize = 0;
		private $fileSizeKo = 0;
	public $image;
	public $isTmp = false;
	
	public function __construct()
	{
		if (!extension_loaded('gd'))
		{
			Log::setLog('GD Library not load', 'CoolPic');
			throw new Exception('GD Library not load');
		}

		// Enregistre la version courant de la librairie graphique GD
		if (function_exists('gd_info'))
		{
			$ver_info = gd_info();
			preg_match('/\d/', $ver_info['GD Version'], $match);
			Log::setLog('GB version '.$ver_info['GD Version'], 'CoolPic');
			$this->gdVersion = $match[0];
		}
	}
	

	/**
	 * Charge un fichier image
	 * 
	 * @param string $file
	 * @return bool
	 */
	public function loadImage($file)
	{
		// Adresse de l'image
		$this->file = $file;
		Log::setLog('Set file image : "'.$this->file.'"',  'CoolPic');

		if (!preg_match('#http#', $file))
		{
			if (!file_exists($file))
			{ 
				Log::setLog('404 file not found: ' . $file, 'CoolPic'); 
				throw new Exception('404 file not found');
			}
		} else {

			$getfile = file_get_contents_curl($file);

			$this->file = __PUBLIC_PATH . '/assets/tmp/' . randCar(). '.'.pathinfo($file, PATHINFO_EXTENSION);
			if (!file_put_contents($this->file, $getfile)) {
				throw new Exception('File cache error');
			}
			$this->isTmp = true;
			/*
			$hdrs = @get_headers($file); 
			$hdrs = isSet($hdrs[0]) ? $hdrs[0] : NULL;
			if (!preg_match('/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/', $hdrs) ) 
			{
				Log::setLog('404 file not found (url): ' . $file, 'CoolPic'); 
				throw new Exception('404 file not found (url)');
			}//*/
			
		}

		// Extention du fichier
		$this->extention = pathinfo($this->file, PATHINFO_EXTENSION);
		Log::setLog('Set extention image : "'.$this->extention.'"', 'CoolPic');

		if (!in_array(strtolower($this->extention), $this->supportedExt)) {
			Log::setLog('Extention not supported : "'.$this->extention.'"', 'CoolPic');
			throw new Exception('Extention not supported');
		} 

		// Information sur l'image
		$this->imageSize = getimagesize($this->file);
		
		if (!$this->imageSize)
		{
			Log::setLog('Incorrect file', 'CoolPic');
			throw new Exception('Incorrect file');	
		}
		
		if (!preg_match( '#image\/#', $this->imageSize['mime']))
		{
			Log::setLog('Mime type, is not a picture', 'CoolPic');
			throw new Exception('Mime type, is not a picture');
		}
		
		$this->imageSize['width'] = $this->imageSize[0];
		$this->imageSize['height'] = $this->imageSize[1];
		$this->extention = $this->imageSize['ext'] = preg_replace('#image\/#', '', $this->imageSize['mime']);
		Log::setLog('Get image info : (mime: ' . $this->imageSize['mime'] . ')' . $this->imageSize['width'] . 'px height ' . $this->imageSize['height'] . 'px', 'CoolPic');		
		

		// Poids de l'image
		$this->fileSize = filesize($this->file); //récupération de la taille en octets
		$this->fileSizeKo = round($this->fileSize/1024); //conversion en ko
		Log::setLog('File size : '.$this->fileSizeKo . ' ko', 'CoolPic');
		
		
		$this->image = imagecreatetruecolor($this->getWidth(), $this->getHeight());
		$black = imagecolorallocate($this->image, 0, 0, 0);

		// On rend l'arrière-plan transparent
		imagecolortransparent($this->image, $black);
		
			switch($this->extention)
			{
				case 'jpeg':
					$this->image = imagecreatefromjpeg($this->file);
					Log::setLog('imagecreatefromjpeg', 'CoolPic');
				break;
				case 'jpg':
					$this->image = imagecreatefromjpeg($this->file);
					Log::setLog('imagecreatefromjpeg', 'CoolPic');
				break;
				
				case 'png':
					$this->image = imagecreatefrompng($this->file);
					imagealphablending($this->image, false);
					imagesavealpha($this->image, true);
					Log::setLog('imagecreatefrompng', 'CoolPic');
				break;
				case 'gif':
					$this->image = imagecreatefromgif($this->file);
					imagealphablending($this->image, false);
					imagesavealpha($this->image, true);
					Log::setLog('imagecreatefromgif', 'CoolPic');
				break;
			}
			
			if (!$this->image)
			{
				Log::setLog('Image create false', 'CoolPic');
				throw new Exception('Image create false');
			}
		return $this;
	}
	
	public function createImage($width, $height)
	{	
		$this->imageSize['width'] = (int) $width;
		$this->imageSize['height'] = (int) $height;
		
		$this->image = imagecreatetruecolor($this->imageSize['width'], $this->imageSize['height']);
			if (!$this->image)
			{
				Log::setLog('Image create false', 'CoolPic');
				throw new Exception('Image create false');
			}
		Log::setLog('Create image : width ' . $this->imageSize['width'] . 'px height ' . $this->imageSize['height'] . 'px',  'CoolPic');
		$this->extention = 'png';
		return $this;
	}
	
	
	/**
	 * Retourne l'image en base64
	 */
	public function getBase64()
	{
		if (!$this->image)
		{
			Log::setLog('Image create false', 'CoolPic');
			throw new Exception('Image create false');
		}
			
		if($this->extention == 'jpeg' or $this->extention == 'jpg')
		{
			ob_start();
			imagejpeg($this->image, NULL, 100 /* Qualité */);
			$jpegString = ob_get_contents();
			ob_end_clean();
			
			return 'data:image/jpeg;base64,' . base64_encode($jpegString);

		}
		
		if($this->extention == 'png')
		{
			ob_start();
			imagepng($this->image);
			$pngString = ob_get_contents();
			ob_end_clean();

			return 'data:image/png;base64,' . base64_encode($pngString);
			
		}
		
		if($this->extention == 'gif')
		{
			ob_start();
			imagegif($this->image);
			$gifString = ob_get_contents();
			ob_end_clean();

			return 'data:image/gif;base64,' . base64_encode($gifString);
		}
	}
	
	
	/**
	 * Sauvegarde l'image dans $thisName
	 * 
	 * @param string $thisName
	 */
	public function save($thisName)
	{
		Log::setLog('Save image as ' . $thisName, 'CoolPic');
		
		if($this->extention == 'jpeg' or $this->extention == 'jpg')
		{
			imagejpeg($this->image, $thisName, 90 /* Qualité */);
		}
		
		if($this->extention == 'png')
		{
			imagepng($this->image, $thisName);
		}
		
		if($this->extention == 'gif')
		{
			imagegif($this->image, $thisName);
		}
		
	}
	
	
	/**
	 * Affiche l'image
	 * 
	 */
	public function show() {
		
		if($this->extention == 'jpeg' or $this->extention == 'jpg') {
            header('Content-type: image/jpeg');
			imagejpeg($this->image);
		}
		
		if($this->extention == 'png') {
            header('Content-type: image/png');
			imagepng($this->image);
		}
		if($this->extention == 'gif') {
            header('Content-type: image/gif');
			imagegif($this->image);
		}
	}
	
	
	
	public function getWidth()
	{
		return (int) $this->imageSize['width'];
	}
	
	public function getHeight()
	{
		return (int) $this->imageSize['height'];
	}
	
	public function getExtention()
	{
		return $this->extention;
	}
	
	public function getImage()
	{
		return $this->image;
	}
	
	
	public function removeTmpFile(){
		@unlink($this->file);
	}
	
	public function __destruct() {
		if ($this->isTmp) {
			$this->removeTmpFile();
		}
	}
}






/*##################################################
 *                             CoolPic.class.php
 *                            -------------------
 *   begin                : 2012-22-08
 *   copyright            : (C) 2012 DevPHP
 *   email                : developpeur@crystal-web.org
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, M/*A  02110-1301, USA.
 *
###################################################*/
Class CoolPicTools{
	

	/** 
	 * Pour modifier au pourcentage l'image
	 * 
	 * @param int $percentage
	 * @param b $crop
	 */
	function percent($percent=0.5)
	{
		Log::setLog('Resize to '.$percent.' percent', 'CoolPicTools');
			
		$newWidth = round($this->imageSize['width'] * $percent);
		$newHeight = round($this->imageSize['height'] * $percent);
		
		// Creer une nouvelle image
		$newImage = imagecreatetruecolor($newWidth, $newHeight);
		
		// Resize
		imagecopyresampled($newImage, $this->image, 0, 0, 0, 0, $newWidth, $newHeight, $this->imageSize['width'], $this->imageSize['height']);
		
		// Echange des valeurs
		$this->image = $newImage;
		$this->imageSize['width'] = $newWidth;
		$this->imageSize['height'] = $newWidth;
	
		Log::setLog('New size width ' . $this->imageSize['width'] . 'px height ' . $this->imageSize['height'] . 'px', 'CoolPicTools');
		
		return $this;
	}
	
	public function resize($width = 0, $height = 0, $truecolor = true /* For transparenty use false*/)
	{
        if ($height == 0 && $width == 0) {
            return $this;
        }

		if ($height > 0 && $width > 0) {
            if ($truecolor) {
                // Creer une nouvelle image
                $newImage = imagecreatetruecolor($width, $height);
            } else {
                // Creer une nouvelle image
                $newImage = imagecreate($width, $height);
            }

			// On crée une transparence
			$color = imagecolorallocatealpha($newImage, 1, 2, 3, 127);
			// On remplis de transparence... 
			imagefill($newImage, 0, 0, $color);		
			// Resize
			imagecopyresampled($newImage, $this->image, 0, 0, 0, 0, $width, $height, $this->imageSize['width'], $this->imageSize['height']);
			
			// Voila l'image est redimensionné et transparente ^^
			
			// Echange des valeurs
			$this->image = $newImage;
			$this->imageSize['width'] = $width;
			$this->imageSize['height'] = $height;
			
			Log::setLog('New size width ' . $this->imageSize['width'] . 'px height ' . $this->imageSize['height'] . 'px', 'CoolPicTools');
		} elseif ($width > 0 && $height == 0) {
            $Reduction = ( ($width * 100)/$this->imageSize['width'] );
            $height = ( ($this->imageSize['height'] * $Reduction)/100 );

            if ($truecolor) {
                // Creer une nouvelle image
                $newImage = imagecreatetruecolor($width, $height);
            } else {
                // Creer une nouvelle image
                $newImage = imagecreate($width, $height);
            }

            // On crée une transparence
            $color = imagecolorallocatealpha($newImage, 1, 2, 3, 127);
            // On remplis de transparence...
            imagefill($newImage, 0, 0, $color);
            // Resize
            imagecopyresampled($newImage, $this->image, 0, 0, 0, 0, $width, $height, $this->imageSize['width'], $this->imageSize['height']);

            // Voila l'image est redimensionné et transparente ^^

            // Echange des valeurs
            $this->image = $newImage;
            $this->imageSize['width'] = $width;
            $this->imageSize['height'] = $height;

            Log::setLog('New size width ' . $this->imageSize['width'] . 'px height ' . $this->imageSize['height'] . 'px', 'CoolPicTools');
        } elseif ($width == 0 && $height > 0) {

            $Reduction = ( ($height * 100)/$this->imageSize['height'] );
            $width = ( ($this->imageSize['width'] * $Reduction)/100 );

            if ($truecolor) {
                // Creer une nouvelle image
                $newImage = imagecreatetruecolor($width, $height);
            } else {
                // Creer une nouvelle image
                $newImage = imagecreate($width, $height);
            }

            // On crée une transparence
            $color = imagecolorallocatealpha($newImage, 1, 2, 3, 127);
            // On remplis de transparence...
            imagefill($newImage, 0, 0, $color);
            // Resize
            imagecopyresampled($newImage, $this->image, 0, 0, 0, 0, $width, $height, $this->imageSize['width'], $this->imageSize['height']);

            // Voila l'image est redimensionné et transparente ^^

            // Echange des valeurs
            $this->image = $newImage;
            $this->imageSize['width'] = $width;
            $this->imageSize['height'] = $height;

            Log::setLog('New size width ' . $this->imageSize['width'] . 'px height ' . $this->imageSize['height'] . 'px', 'CoolPicTools');
        }


		
		return $this;
	}
	
	
	
	/**
	 * Redimension, en gardant un ratio/deformation correct
	 * 
	 * @param int $maxPixel
	 */
	public function rate($maxPixel)
	{
		// Ratio de l'image (100%)
		$ratio = $this->imageSize['width'] / $this->imageSize['height'];
		
		
		if ( $ratio > 1 )
		{
			// Paysage
			$newHeight = round( $maxPixel / $ratio );
			$newWidth  = $maxPixel;
		}
		else
		{
			// Portrait
			$newHeight = $maxPixel;
			$newWidth  = round( $maxPixel * $ratio );
		}
		

		// Creer une nouvelle image
		$newImage = imagecreatetruecolor($newWidth, $newHeight);
		
		// Resize
		imagecopyresampled($newImage, $this->image, 0, 0, 0, 0, $newWidth, $newHeight, $this->imageSize['width'], $this->imageSize['height']);
		
		// Echange des valeurs
		$this->image = $newImage;
		$this->imageSize['width'] = $newWidth;
		$this->imageSize['height'] = $newHeight;
	
		Log::setLog('New size width ' . $this->imageSize['width'] . 'px height ' . $this->imageSize['height'] . 'px', 'CoolPicTools');
		
		return $this;
	}
	
	
	

	/**
	 * Permet l'ajout d'un logo, sur l'un des coins de l'image de base
	 * 
	 * @param string $logo
	 * @param string $position (down_right|down_left|up_right|up_left)
	 */
	function logo($logo, $position='down_right')
	{

		try
		{
			$imgLogo = new CoolPic();
			$imgLogo->loadImage($logo);
	
			switch ($position)
			{
			case 'down_right':
				$destination_x = $this->getWidth() - $imgLogo->getWidth();
				$destination_y = $this->getHeight() - $imgLogo->getHeight();
			break;
			case 'down_left':
				$destination_x = 0;
				$destination_y = $this->getHeight() - $imgLogo->getHeight();
			break;
			case 'up_right':
				$destination_x = $this->getWidth() - $imgLogo->getWidth();
				$destination_y = 0;
			break;
			case 'up_left':
				$destination_x = 0;
				$destination_y = 0;
			break;
			default:
				$destination_x = $this->getWidth() - $imgLogo->getWidth();
				$destination_y = $this->getHeight() - $imgLogo->getHeight();
			break;
			}
	
				if (!$imgLogo->getImage())
				{
					Log::setLog('Image create false', 'CoolPic');
					throw new Exception('Image create false');
				}
	
			// On met le logo (source) dans l'image de destination (la photo)
			imagecopymerge($this->image, $imgLogo->getImage(), $destination_x, $destination_y, 0, 0, $imgLogo->getWidth(), $imgLogo->getHeight(), 100);
			
			
			
			return $this;
		} catch (exception $e) {
			throw new Exception('Logo ' . $e->getMessage());
		}
	}




	/**
	 * Dessiner une bordure
	 * 
	 * @param int $red
	 * @param int $green
	 * @param int $blue
	 * @param int $thickness
	 */ 
	public function drawBorder($red, $green, $blue, $thickness = 1) 
	{
		$color = ImageColorAllocate($this->image, $red, $green, $blue);  
	    $x1 = 0; 
	    $y1 = 0; 
	    $x2 = $this->getWidth() - 1; 
	    $y2 = $this->getHeight() - 1; 
	
	    for($i = 0; $i < $thickness; $i++) 
	    { 
	        ImageRectangle($this->image, $x1++, $y1++, $x2--, $y2--, $color); 
	    }
		
		return $this;
	}
	
	
	/**
	 * 
	 * Chart Lineaire
	 * Beta test
	 */
	public function lineare($array) 
	{
	
		// on calcule le nombre maximal
		$max = max($array);

		Log::setLog('Create color panel', 'CoolPic');
		$blanc = ImageColorAllocate ($this->image, 255, 255, 255); 
		
		// on place aussi le noir dans notre palette, ainsi qu'un bleu foncé et un bleu clair
		$noir = ImageColorAllocate ($this->image, 0, 0, 0);
		$bleu_fonce = ImageColorAllocate ($this->image, 75, 130, 195);
		$bleu_clair = ImageColorAllocate ($this->image, 95, 160, 240);

		$largeur = $this->getWidth();
		$hauteur = $this->getHeight();

		$spaceSize = ceil( $this->getWidth() / ( count($array) + 1) ) - 1;

		// Les chiffres sous la chart
		for ($i=1; $i<=count($array); $i++) {
			if ($i==1) {
				ImageString ($this->image, 2, $spaceSize+3, $hauteur-38, $i, $noir);
			}
			else {
				ImageString ($this->image, 2, ($i)*$spaceSize+3, $hauteur-38, $i, $noir);
			}
		}



		// on affiche les legendes sur les deux axes ainsi que différents textes (note : pour que le script trouve la police verdana, vous devrez placer la police verdana dans un repertoire /fonts/)
		//imagettftext($this->image, 14, 0, $largeur-70, $hauteur-10, $noir, "./fonts/verdana.ttf", "Mois");
		//imagettftext($this->image, 14, 0, 10, 20, $noir, "./fonts/verdana.ttf", "Nb. de pages vues");
		//imagettftext($this->image, 14, 0, $largeur-250, 20, $noir, "./fonts/verdana.ttf", "Statistiques pour l'année 2003");
		ImageString ($this->image, 14, 0, 0, 'coucou', $noir);
	
		// on parcourt le tableau
		for ($m=1; $m <= count($array); $m++) {
			// on calcule la hauteur du baton
			$hauteurImageRectangle = ceil(((($array[$m])*($hauteur-50))/$max));
		
			// si le mois est different de janvier, on affiche les autres batons
			ImageFilledRectangle ($this->image, ($m)*$spaceSize, $hauteur-$hauteurImageRectangle, ($m)*$spaceSize+( (int) $spaceSize / 2 ), $hauteur-41, $noir);
			ImageFilledRectangle ($this->image, ($m)*$spaceSize+2, $hauteur-$hauteurImageRectangle+2, ($m)*$spaceSize+( (int) $spaceSize / 2 ) - 2, $hauteur-41-1, $bleu_fonce);
			ImageFilledRectangle ($this->image, ($m)*$spaceSize+2, $hauteur-$hauteurImageRectangle+2, ($m)*$spaceSize+( (int) $spaceSize / 2 ) - 8, $hauteur-41-1, $bleu_clair);
		}

		// on dessine un trait horizontal pour représenter l'axe du temps
		ImageLine ($this->image, 20, $hauteur-40, $largeur, $hauteur-40, $noir);

		// on dessine un trait vertical pour représenter le nombre de pages vues
		ImageLine ($this->image, 20, 20, 20, $hauteur-40, $noir);


		return $this;
	}



	/**
	 * Bug si  depasse 256
	 */
	public function degrade($color1,$color2)
	{
	
	$size = imagesy($this->image);
	$sizeinv = imagesx($this->image);
	
	
	$diffs = array(
		(($color2[0]-$color1[0])/$size),
		(($color2[1]-$color1[1])/$size),
		(($color2[2]-$color1[2])/$size)
		);
		
		for($i=0;$i<$size;$i++)
		{
			$r = $color1[0]+($diffs[0]*$i);
			$g = $color1[1]+($diffs[1]*$i);
			$b = $color1[2]+($diffs[2]*$i);
			
		imageline($this->image,0,$i,$sizeinv,$i,imagecolorallocate($this->image,$r,$g,$b));
		}
		
		return $this;
	}
	
	public function minecraftFace($width = 64) {
		$this->extention = 'png';
		$destination = new CoolPic();
		$destination->createImage(8, 8);
		
		$destination_x = 0;
		$destination_y =  0;
		
		imagecopymerge($destination->image, $this->image, $destination_x /* 0 */, $destination_y /* 0 */, 8, 8, $this->getWidth(), $this->getHeight(), 100);
		
		$destination->rate($width);
		$this->image = $destination->image;
		return $this;
	}
	
	public function about($str = null)
	{
		$this->extention = 'png';
		$this->createImage(350,20);
	
		$white=imagecolorallocate($this->image,255,255,255);
		$black = imagecolorallocate($this->image, 0, 0, 0);
		
		$red = imagecolorallocate($this->image, 255, 0, 0);
		$green = imagecolorallocate($this->image, 0, 255, 0);
		$blue = imagecolorallocate($this->image, 0, 0, 255);
		
		$lightblue = imagecolorallocate($this->image, 152, 203, 253);
		// Gris avec opacité
		$lightgrey = imagecolorallocatealpha($this->image, 240, 240, 240, 50);
		$php = imagecolorallocatealpha($this->image, 119, 123, 180, 30);

		$this->degrade(array(255,255,255),array(57,130,157));

		imagefilledarc($this->image,
		(350/2),// Horiz
		-10,// Verti
		300, // Largeur
		45,
		25,
		155,
		$lightgrey,
		IMG_ARC_PIE);
		
			// Diagonal
			for($i = 0; $i < 370; $i+=5) {
				imageline($this->image, $i, 0, 0, $i, $lightblue);
			}
		

	    $text = $str . ' By DevPHP';
	
	    //$font = 'files/fonts/Commodore Rounded v1.2.ttf';
		$font = './media/font/pixel.ttf';
		if (!file_exists($font)) {
			noError(true);
			$fontStyle = file_get_contents_curl('http://service.crystal-web.org/aide_forum/font/pixel.ttf');	
			noError(false);
			
			if ($fontStyle) {
				@mkdir('./assets/fonts');
				file_put_contents($font, $fontStyle);
			} else {
				@mkdir('./assets/fonts');
				// ByPass file_get_content url
				exec('cd assets/fonts && wget http://service.crystal-web.org/aide_forum/font/pixel.ttf && chmod 777 pixel.ttf');
			}
		}
		
	    $fontsize = 10;
	    
		// Calcul la taille de la boite contenant le texte
		$size = imagettfbbox($fontsize, 0, $font, $text); 
		
		//Calcul la postion de depart de la boite
	    $dx = (imagesx($this->image)) - (abs($size[2]-$size[0])) - 10; 
		
		/***
		Effet ombre
		***/
	    imagettftext($this->image, $fontsize, 0, $dx, 15, $black, $font, $text);
			imagettftext($this->image, $fontsize, 0, $dx-1, 14, $lightgrey, $font, $text);
			
			
			
		/* carré PHP */
		imagefilledrectangle ($this->image , 0, 0, 23, 20, $php); // 20*20
		
		/***
		Effet ombre
		***/
		imagettftext($this->image, 8, 0, 1, 13, $white, $font, 'php');
			imagettftext($this->image, 8, 0, 3, 15, $white, $font, 'php');
				imagettftext($this->image, 8, 0, 2, 14, $black, $font, 'php');
			
		// Création du cadre
		ImageRectangle ($this->image, 0, 0, 349, 19, $black);

	return $this;
	}
}

