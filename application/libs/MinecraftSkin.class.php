<?php

/**
* PHP Minecraft Skin Grabber
*
* This code is provided as is without warranty of any kind.
*
* Example usage:
* $skin = new MinecraftSkin($username);
* $head = $skin->getMinecraftSkinHead();
*
* Don't forget to create the skin folder.
*
* @author   Félix Yadomi <dev.yadomi@gmail.com>
* @version  0.1
* @access   public
* @see      http://bioslord.homelinux.com
*/
class MinecraftSkin{
	private $image_width  = 64;
	private $image_height = 32;
	public $scale = 0.0875;
	public  $skin_img_obj;
	public  $username;
	public $skin_folder = 'media/others/skins/';

	public $skin_has_hat = FALSE;


	private $pixel_loc = array(
	    "head" => array(
        	 "face" => array("x" => '8', "y" => '8'),
        	 "top" => array("x" => '8', "y" => '0'),
        	 "left" => array("x" => '0', "y" => '8')
	    ),
	    "hat" => array(
        	 "face" => array("x" => '40', "y" => '8'),
        	 "top" => array("x" => '40', "y" => '0'),
        	 "left" => array("x" => '32', "y" => '8'),
    		),
	    "body" => array(
        	 "face" => array("x" => '20', "y" => '20'), /*8x12*/
        	 "top" => array("x" => '20', "y" => '16'),  /*8x4*/
        	 "left" => array("x" => '16', "y" => '20'), /*4x12*/
    		),
	    "legs" => array(
        	 "face" => array("x" => '4', "y" => '20'), 
        	 "left" => array("x" => '8', "y" => '20'), /*4x12*/
    		),
	    "arm" => array(
        	 "top" => array("x" => '44', "y" => '16'), /*8x8*/
        	 "face" => array("x" => '44', "y" => '20'), 
        	 "left" => array("x" => '48', "y" => '20'), 
    		)
	);

	public function __construct($username = null) {	
		$this->username = $username .'.png';
		if(!$this->isSkinAlreadyDownloaded()){
			if(!$this->downloadSkinFromMinecraft()){
				$this->setDefaultSkin();
			}
		}
		
		noError(true);
		$this->skin_img_obj = @imagecreatefrompng($this->skin_folder.$this->username);
			if (!$this->skin_img_obj) {
				$this->skin_img_obj = imageToPng($this->skin_folder.$this->username);
				imagepng($this->skin_img_obj, $this->skin_folder.$this->username);
			}
		noError(false);
    	}
	/**
	 * Expand image with ressampling
	 */
	private function ToolsExpandPixelImage($small_image, $new_taille){
		$NouvelleImage = $this->ToolsImageCreateTransparent($new_taille , $new_taille) or die ("Erreur");
		imagecopyresampled($NouvelleImage , $small_image, 0, 0, 0, 0, $new_taille, $new_taille, 8,8);
		return $NouvelleImage;
	}
	/**
	 * Create an transparent image
	 */
	private function ToolsImageCreateTransparent($x, $y) { 
	   	$imageOut = imagecreate($x, $y);
	    	$colourBlack = imagecolorallocate($imageOut, 0, 0, 0);
	    	imagecolortransparent($imageOut, $colourBlack);
	    	return $imageOut;
	}
	/**
	 * Return the skin head in 3 parts
	 * @return array
	 */
	public function getMinecraftSkinHead(){
		$array_head = Array();
		$image_size = '8';
		foreach ($this->pixel_loc['head'] as $k => $v) {
			//echo $this->pixel_loc['head'][$k][$xy];
			$tmp_img = $this->ToolsImageCreateTransparent($image_size,$image_size);
			imagecopy($tmp_img, $this->skin_img_obj, 0, 0,
				$this->pixel_loc['head'][$k]['x'],
				$this->pixel_loc['head'][$k]['y'],
				$this->image_width, $this->image_height
			);
			$tmp_img = $this->ToolsExpandPixelImage($tmp_img, $image_size*($this->scale*100));
			ob_start(); // buffers future output
			imagepng($tmp_img); // writes to output/buffer
			$tmp_img = base64_encode(ob_get_contents()); // returns output
			ob_end_clean(); // clears buffered output
			$array_head[$k] = $tmp_img;
		}	
		return $array_head;	
	}
	 
	public function getMinecraftSkinHat(){
		$array_hat = Array();
		$image_size = '8';
		foreach ($this->pixel_loc['hat'] as $k => $v) {
		
			//echo $this->pixel_loc['head'][$k][$xy];
			$tmp_img = $this->ToolsImageCreateTransparent($image_size,$image_size);
			imagecopy($tmp_img, $this->skin_img_obj, 0, 0,
				$this->pixel_loc['hat'][$k]['x'],
				$this->pixel_loc['hat'][$k]['y'],
				$this->image_width, $this->image_height
			);
			$tmp_img = $this->ToolsExpandPixelImage($tmp_img, $image_size*($this->scale*100));
			ob_start(); // buffers future output
			imagepng($tmp_img); // writes to output/buffer
			$tmp_img = base64_encode(ob_get_contents()); // returns output
			ob_end_clean(); // clears buffered output
			$array_hat[$k] = $tmp_img;
		}	
		return $array_hat;	
	}
	public function getMinecraftSkinLegs(){
		$array = Array();
		$x = 4;
		$y = 12;

		foreach ($this->pixel_loc["legs"] as $k => $v) {
		//if($k == 'face'){$y = $x;}		
			//echo $this->pixel_loc['head'][$k][$xy];
			$tmp_img = $this->ToolsImageCreateTransparent($x,$y);
			imagecopy($tmp_img, $this->skin_img_obj, 0, 0,
				$this->pixel_loc["legs"][$k]['x'],
				$this->pixel_loc["legs"][$k]['y'],
				$this->image_width, $this->image_height
			);
			$NouvelleImage = $this->ToolsImageCreateTransparent($x*$this->scale*100 , $y*$this->scale*100) or die ("Erreur");
			imagecopyresampled($NouvelleImage , $tmp_img, 0, 0, 0, 0, $x*$this->scale*100 , $y*$this->scale*100, $x,$y);

			ob_start(); // buffers future output
			imagepng($NouvelleImage); // writes to output/buffer
			$NouvelleImage = base64_encode(ob_get_contents()); // returns output
			ob_end_clean(); // clears buffered output
			$array[$k] = $NouvelleImage;
		}	
		return $array;	
	}
	public function getMinecraftSkinArms(){
		$array = Array();
		

		foreach ($this->pixel_loc["arm"] as $k => $v) {
		$x = 4;$y = 12;
		if($k == 'top'){$y = $x;}
			//echo $this->pixel_loc['head'][$k][$xy];
			$tmp_img = $this->ToolsImageCreateTransparent($x,$y);
			imagecopy($tmp_img, $this->skin_img_obj, 0, 0,
				$this->pixel_loc["arm"][$k]['x'],
				$this->pixel_loc["arm"][$k]['y'],
				$this->image_width, $this->image_height
			);
			$NouvelleImage = $this->ToolsImageCreateTransparent($x*$this->scale*100 , $y*$this->scale*100) or die ("Erreur");
			imagecopyresampled($NouvelleImage , $tmp_img, 0, 0, 0, 0, $x*$this->scale*100 , $y*$this->scale*100, $x,$y);

			ob_start(); // buffers future output
			imagepng($NouvelleImage); // writes to output/buffer
			$NouvelleImage = base64_encode(ob_get_contents()); // returns output
			ob_end_clean(); // clears buffered output
			$array[$k] = $NouvelleImage;
		}	
		return $array;	
	}
	public function getMinecraftSkinBody(){
		foreach ($this->pixel_loc['body'] as $k => $v) {
		
			//echo $this->pixel_loc['head'][$k][$xy];
			if($k == 'face'){
				$x = 8; $y = 12;
			}elseif($k == 'top'){
				$x = 8; $y = 4;
			}elseif($k == 'left'){
				$x = 4; $y = 12;
			}
			$tmp_img = $this->ToolsImageCreateTransparent($x,$y);
			imagecopy($tmp_img, $this->skin_img_obj, 0, 0,
				$this->pixel_loc['body'][$k]['x'],
				$this->pixel_loc['body'][$k]['y'],
				$this->image_width, $this->image_height
			);
			
			$NouvelleImage = $this->ToolsImageCreateTransparent($x*$this->scale*100 , $y*$this->scale*100) or die ("Erreur");
			imagecopyresampled($NouvelleImage , $tmp_img, 0, 0, 0, 0, $x*$this->scale*100 , $y*$this->scale*100, $x,$y);

			ob_start(); // buffers future output
			imagepng($NouvelleImage); // writes to output/buffer
			$NouvelleImage = base64_encode(ob_get_contents()); // returns output
			ob_end_clean(); // clears buffered output
			$array[$k] = $NouvelleImage;
		}	
		return $array;
	}
	/**
	 * Set a fallback default skin.
	 */
	public function setDefaultSkin(){
		$this->username = 'skindefault.png';
	}
	/**
	 * Download the skin form Minecraft.net
	 */
	public function downloadSkinFromMinecraft(){
		noError(true);
		if($fp = @fopen('http://www.minecraft.net/skin/' . $this->username ,"rb")) {
			if($pointer = fopen($this->skin_folder . $this->username,"wb+")){
				while($buffer = fread($fp, 1024)) {
					if(!fwrite($pointer,$buffer))
					{
					return FALSE;
					}
				}
			}
		fclose($pointer);
		}else{
			return FALSE;
		}
		fclose($fp);
		noError(false);
	}
	/**
	 * Check if the skin is already downloaded (So the script will not donwload at every script run)
	 */
	public function isSkinAlreadyDownloaded(){
		$filename = $this->skin_folder.$this->username;
		if(file_exists($filename)){
		return TRUE;
		}else{
		return FALSE;
		}
	}
}


function imageToPng($srcFile, $maxSize = 100) {  
    list($width_orig, $height_orig, $type) = getimagesize($srcFile);        
 
    // Get the aspect ratio
    $ratio_orig = $width_orig / $height_orig;
 
    $width  = $maxSize; 
    $height = $maxSize;
 
    // resize to height (original is portrait) 
    if ($ratio_orig < 1) {
        $width = $height * $ratio_orig;
    } 
    // resize to width (original is landscape)
    else {
        $height = $width / $ratio_orig;
    }
 
    // Temporarily bump up the memory limit to allow for larger images
    ini_set('memory_limit', '32M'); 
 
    switch ($type) 
    {
        case IMAGETYPE_GIF: 
            $image = imagecreatefromgif($srcFile); 
            break;   
        case IMAGETYPE_JPEG:  
            $image = imagecreatefromjpeg($srcFile); 
            break;   
        case IMAGETYPE_PNG:  
            $image = imagecreatefrompng($srcFile);
            break; 
        default:
            throw new Exception('Unrecognized image type ' . $type);
    }
 
    // create a new blank image
    $newImage = imagecreatetruecolor($width, $height);
 
    // Copy the old image to the new image
    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
 
    // Output to a temp file
    $destFile = tempnam("/tmp", "conv");
    imagepng($newImage, $destFile);  
 
    // Free memory                           
  //  imagedestroy($newImage);
 
    if ( is_file($destFile) ) {
        /* $f = fopen($destFile, 'rb');
        $data = fread($f);
        fclose($f);//*/
		
        // Remove the tempfile
        unlink($destFile);
        return $newImage;
    }
	
    // Free memory                           
	imagedestroy($newImage);
	
    throw new Exception('Image conversion failed.');
}