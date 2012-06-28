<?php 
/**
* captcha : simple php captcha system * 
* @author Jean-Pierre Morfin 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by/3.0/
*/  
  
/* Choose length (max 32) */ 
define("CAPTCHA_LENGTH",5);
define("ROBOT",'simple-php-captcha-system');
$_SESSION["captcha_akey"] = md5(uniqid(rand(), true));

/** 
* Helper to generate html form tags * 
*/
class Captcha {

	/** * Generate IMG Tag *
	* @param string $baseuri : relative or absolute path to folder containing this file on web 
	* @return IMG Tag */
	static function generateImgTags() {
	return "<a href=\"#\"><img alt=\"Clique pour chang&eacute; les couleurs\" title=\"Clique pour chang&eacute; les couleurs\"". " src=\"" . __CW_PATH . "/media.php?pck=".$_SESSION['captcha_akey']."\"". " id=\"captcha\"". " onclick=\"javascript:this.src='" . __CW_PATH . "/media.php?pck=". $_SESSION['captcha_akey']. "&z='+Math.random();return false;\" /></a>\n";
	}

	/**
	* Generate hidden tag (must be in a form) *
	* @return input hidden tag */
	static function generateHiddenTags()
	{
	return "<input type=\"hidden\" name=\"captcha_key\" value=\"".$_SESSION['captcha_akey']."\"/>";
	}

	/**
	* Generate input tag (must be in a form) *
	* @return input tag */
	static function generateInputTags() {
	return "<input id=\"captcha\" type=\"text\" name=\"captcha_entry\" value=\"\"  required=\"required\" />";
	}

	/** 
	* Check if user input is correct *
	* @return boolean (true=correct, false=incorrect) */
	static function checkCaptcha()
	{
	$Captcha = new Captcha();
		if(isset($_POST['captcha_entry']) &&  $_POST['captcha_entry'] == $Captcha->_getDisplayText($_POST['captcha_key']))
		{
		return true;
		}
	return false;
	}

	/**
	* Internal function *
	* @param string $pck 
	* @return string */ 
	public function _getDisplayText($pck)//internal function 
	{
	$src=md5(ROBOT.$pck);
	$txt="";
	for($i=0;
	$i<CAPTCHA_LENGTH;
	$i++) $txt.=substr($src,$i*32/CAPTCHA_LENGTH,1);
	return $txt;
	}
}

?>