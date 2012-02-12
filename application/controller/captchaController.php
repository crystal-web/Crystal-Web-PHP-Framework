<?php
/**
* @title Simple MVC systeme 
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/
Class captchaController Extends baseController {
    
    public function index()
    {
            
    }
    
    public function ajax()
    {
        // If script called directly : generate image 
        if(isset($_GET["pck"]))
        {
        $width = CAPTCHA_LENGTH*10+10;
        $height = 30;
        $image = imagecreatetruecolor($width, $height);
        $bgCol = imagecolorallocate($image, rand(128,255), rand(128,255), rand(128,255));
        imagefilledrectangle($image,0,0,$width,$height,$bgCol);

        $Captcha = new Captcha();
        $txt = $Captcha->_getDisplayText($_GET["pck"]);

            for($c=0;$c<CAPTCHA_LENGTH*2;$c++)
            {
            $bgCol = imagecolorallocate($image, rand(100,255), rand(100,255), rand(100,255));
            $x=rand(0,$width);
            $y=rand(0,$height);
            $w=rand(5,$width/2);
            $h=rand(5,$height/2);
            imagefilledrectangle($image,$x,$y,$x+$w,$y+$h,$bgCol);
            imagecolordeallocate($image,$bgCol);
            }

            for($c=0;$c<CAPTCHA_LENGTH;$c++)
            {
            $txtCol = imagecolorallocate($image, rand(0,128) , rand(0,128), rand(0,128));
            imagestring($image,5,5+10*$c,rand(0,10),substr($txt,$c,1),$txtCol);
            imagecolordeallocate($image,$txtCol);
            }
            
        header("Content-type: image/png");
        imagepng($image);
        imagedestroy($image);//*/
        }
    }
    
}
?>
