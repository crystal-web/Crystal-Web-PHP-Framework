<?php
/**
 * Directory separator
 */
define('DS', DIRECTORY_SEPARATOR);

function rHeader($url)
{
$url = preg_replace('#'.$_SERVER['SCRIPT_URL'].'#','', $_SERVER['SCRIPT_URI']).'/'.$url;
return get_headers($url, 1);
}


if (isSet($_GET['thumb']) && !empty($_GET['thumb']))
{

	$file = 'media'.$_GET['thumb'];
	$max = 64;
	if (preg_match('#(jpg|jpeg)#',$file))
	{
	header('Content-type: image/jpeg');
	$img = imagecreatefromjpeg($file);
	$save='imagejpeg';
	}
	elseif (preg_match('#png#',$file))
	{
	header('Content-type: image/png');
	$img = imagecreatefrompng($file);
	$save='imagepng';
	}
	

	
	$x = imagesx($img);
	$y = imagesy($img);
		if($x>$max or $y>$max)
		{
			if($x>$y)
			{
			$nx = $max;
			$ny = $y/($x/$max);
			}
			else
			{
			$nx = $x/($y/$max);
			$ny = $max;
			}
		}
		else
		{
			$nx = $x;
			$ny = $y;
		}
	$nimg = imagecreatetruecolor($nx,$ny);
	imagecopyresampled($nimg,$img,0,0,0,0,$nx,$ny,$x,$y);
	
	$save($nimg);

}
elseif(isset($_GET["pck"]))
{

require_once '../application/libs/Captcha.class.php';
$Captcha = new Captcha();
$txt = $Captcha->_getDisplayText($_GET["pck"]);

$width = CAPTCHA_LENGTH*10+10;
$height = 30;
$image = imagecreatetruecolor($width, $height);
$bgCol = imagecolorallocate($image, rand(128,255), rand(128,255), rand(128,255));
imagefilledrectangle($image,0,0,$width,$height,$bgCol);


   
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
imagedestroy($image);
}
else
{
header("HTTP/1.0 404 Not Found");
header("location: /");
}
?>