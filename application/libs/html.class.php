<?php
/**
* @title html 
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/

class html {
private $mvc;
    private $content;
    private $head=array('API' => NULL, 'js' => NULL,'css' => NULL);
	
	private $css=NULL;
	private $jsScript=NULL;

	private $Breadcrumb;
	private $zone=array(
		'header' => NULL,
		'cHeader' => NULL,
		'main' => NULL,
		'cFooter' => NULL,
		'aside' => NULL,
		'nav' => NULL,
		'footer' => NULL,
		);


	public function __construct($mvc){
	$this->mvc = $mvc;
	}


/*** Script ***/
    /* Ajout d'un script via l'url
	Permet de charger un script, interne ou externe
	*/
    public function setSrcScript($ceci){
        $this->head['js'].='<script src="'.$ceci.'" type="text/javascript"></script>'.PHP_EOL;
        }
    /* Ajout d'un script en code
	Permet de charger un code
	*/
    public function setCodeScript($ceci){
        $this->jsScript .= $ceci . PHP_EOL;
        }
	
	/*
	*	Importation des différentess API
	*	depuis Google
	*/
	public function ChromeFrame() { $this->head['API']['ChromeFrame']=NULL; }	
	public function Dojo() { $this->head['API']['Dojo']=NULL; }	
	public function ExtCore() { $this->head['API']['ExtCore']=NULL; }
	public function JQuery() { $this->head['API']['JQuery']=NULL; }
	public function JQueryUI() { $this->head['API']['JQueryUI']=NULL; }
	public function MooTools() { $this->head['API']['MooTools']=NULL; }
	public function Prototype() { $this->head['API']['Prototype']=NULL; }	
	public function ScriptAculoUs() { $this->head['API']['ScriptAculoUs']=NULL; }	
	public function SWFObject() { $this->head['API']['SWFObject']=NULL; }
	public function WebFont() { $this->head['API']['WebFont']=NULL; }	
	public function Sexy() { $this->head['API']['JQuery']=NULL; $this->head['API']['Sexy']=NULL; }	
	
	private function loadChromeFrame() { $this->setSrcScript('https://ajax.googleapis.com/ajax/libs/chrome-frame/1.0.2/CFInstall.min.js'); }	
	private function loadDojo() { $this->setSrcScript('https://ajax.googleapis.com/ajax/libs/dojo/1.6.1/dojo/dojo.xd.js'); }	
	private function loadExtCore() { $this->setSrcScript('https://ajax.googleapis.com/ajax/libs/ext-core/3.1.0/ext-core.js'); }
	private function loadJQuery() { $this->setSrcScript('https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');	}
	private function loadJQueryUI() { $this->setSrcScript('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js'); }
	private function loadMooTools() { $this->setSrcScript('https://ajax.googleapis.com/ajax/libs/mootools/1.4.1/mootools-yui-compressed.js'); }
	private function loadPrototype() { $this->setSrcScript('https://ajax.googleapis.com/ajax/libs/prototype/1.7.0.0/prototype.js'); }	
	private function loadScriptAculoUs() { $this->setSrcScript('https://ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/scriptaculous.js'); }	
	private function loadSWFObject() { $this->setSrcScript('https://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js'); }
	private function loadWebFont() { $this->setSrcScript('https://ajax.googleapis.com/ajax/libs/webfont/1.0.23/webfont.js'); }	
	private function loadSexy() { $this->setSrcScript(__CW_PATH . '/files/js/Sexy.min.js'); }	



/*** Style ***/
    /* Ajout d'une feuille de style via l'url
	Permet de charger une feuille de style
	*/
    public function setSrcCss($ceci) { $this->head['css'].='<link rel=stylesheet type="text/css" href="'.$ceci.'">'.PHP_EOL; }
    /* Ajout d'une feuille de style en code
	Permet de place dans le <head> le style CSS
	*/
    public function setStyleCss($ceci) { $this->css.=$ceci; }
	
	/* Récupération du header */
	public function getHead()
	{
		if (!is_null($this->head['API']))
		{
			foreach($this->head['API'] AS $method => $null)
			{
				if (method_exists($this, 'load' . $method))
				{
				$m = 'load' . $method;
				$this->$m();
				}
			}
		}
		
		$cssStyle = (!empty($this->css)) ?  '<style type="text/css">' . PHP_EOL .$this->css . PHP_EOL . '</style>'.PHP_EOL	: NULL;
		$jsScript = (!empty($this->jsScript)) ? '<script type="text/javascript">' . PHP_EOL .$this->jsScript . PHP_EOL . '</script>'.PHP_EOL : NULL;

	return $jsScript.$this->head['js'].$cssStyle.$this->head['css'];
	}


/* Contenu */
		
    /* Ajout du contenu
	Tout information qui devra être imprimé sur la page.
	*/
	public function setContent($new_value, $zone = 'main') { $this->zone[$zone].=$new_value; }

	/* Récupération du contenu */
	public function getContent(){ return $this->zone; }
    
}
?>