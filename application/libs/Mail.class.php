<?php
class Mail
{
	private $m_entete;
	private $m_objet;
	private $m_priority;
	private $m_importance;
	private $m_textTxt;
	private $m_textHtml;
	private $m_destinataire;
	private $m_emetteur;
	private $m_reponse;
	private $m_boundary;

	public function __construct($p_objet, $p_text,$p_destinataire,$p_emetteur,$p_reponse = null,$p_importance = "Normal",$p_priority = 3)
	{
		Log::setLog('Mail enabled', 'mail');
		$this->m_boundary = "----".$_SERVER['HTTP_HOST']."----".md5(time());
		$this->m_priority = $p_priority;
		$this->m_importance = $p_importance;
		$this->m_emetteur = $p_emetteur;
		
		
		if($p_reponse == null)
		{
			$this->m_reponse = $p_emetteur;
		}
		else
		{
			$this->m_reponse = $p_reponse;
		}
		
			$this->m_objet = $p_objet;
			$this->m_destinataire = $p_destinataire;
			$this->m_textTxt = $p_text;
	}
	
	public function __get($champ)
	{
		Log::setLog('Get ' . $champ, 'mail');
		return isSet($this->{$champ}) ? $this->{$champ} : '';
	}
	
	public function __set($champ,$value)
	{
		Log::setLog('Set ' . $champ . ' => ' . $value, 'mail');
		$this->{$champ} = $value;
	}
	
	private function reload_entete()
	{
		Log::setLog('Chargement de l\'entete', 'mail');
		$this->m_entete = "From: ".$_SERVER['HTTP_HOST']." <".$this->m_emetteur .">\n";
		$this->m_entete .= "Mime-Version: 1.0\n";
		$this->m_entete .= "Content-Type: multipart/alternative; boundary=\"".$this->m_boundary ."\"\n";
		$this->m_entete .= "X-Sender: <".$_SERVER['HTTP_HOST'].">\n";
		$this->m_entete .= "X-Mailer: PHP/" . phpversion() . " \n" ;
		$this->m_entete .= "X-Priority: ".$this->m_priority ."\n";
		$this->m_entete .= "X-auth-smtp-user: ".$this->m_emetteur ."\n";
		$this->m_entete .= "X-abuse-contact: ".$this->m_emetteur ."\n";
		$this->m_entete .= "Importance: ".$this->m_importance ."\n";
		$this->m_entete .= "Reply-to: ".$this->m_reponse ."\n";
	}
	
	public function sendMailHtml()
	{
		$this->reload_entete();
		
		
		$this->m_textHtml = "--".$this->m_boundary ."\n";
		$this->m_textHtml .= "Content-Type: text/html; charset=ISO-8859-1\n";
		$this->m_textHtml .= "Content-Transfer-Encoding: 8bit\n\n";
		$this->m_textHtml .= $this->m_textTxt;
		
		
		Log::setLog('Envois du mail en mode html', 'mail');
		return (mail($this->m_destinataire,'['.SITENAME.'] ' . $this->m_objet, $this->m_textHtml, $this->m_entete)) ? true : false;	
	}
	
	public function sendMailText()
	{
		Log::setLog('Envois du mail en mode texte', 'mail');
		$this->reload_entete();
		
		$this->m_textHtml = "--".$this->m_boundary ."\n";
		$this->m_textHtml .= "Content-Type: text/html; charset=ISO-8859-1\n";
		$this->m_textHtml .= "Content-Transfer-Encoding: 8bit\n\n";
		$this->m_textHtml .= $this->m_textTxt;
		
		return (mail($this->m_destinataire,'['.SITENAME.'] ' . $this->m_objet, $this->m_textHtml, $this->m_entete)) ? true : false;
	}
	
	
	public function sendMailTemplate($p_subtitle = NULL)
	{
		Log::setLog('Envois du mail en mode texte', 'mail');
		$this->reload_entete();
		
		$this->m_subtitle = $p_subtitle;
		
		$this->loadTemplate();
		

		return (mail($this->m_destinataire,'['.SITENAME.'] ' . $this->m_objet, $this->m_textHtml, $this->m_entete)) ? true : false;
		
	}
	
	
	
	private function loadTemplate()
	{
		Log::setLog('Chargement du Template', 'mail');
	
		ob_start();
		
		$politesse = array(
			'Cordialement ',
			'Bien cordialement ',
			'Cordialement vôtre ',
			'Sincèrement ',
			'Bien sincèrement ',
			'Sincèrement vôtre ',
			'Sincères salutations '
			);
		
		$file = file_get_contents(__APP_PATH . DS . 'mail' . DS . 'base.mail');
		
		$file = preg_replace("#{EMAILTITLE}#", $this->m_objet, $file);
		$file = preg_replace("#{EMAILSUBTITLE}#", $this->m_subtitle, $file);
		$file = preg_replace("#{TEXT}#", $this->m_textTxt, $file);
		$file = preg_replace("#{SINCERLY}#", $politesse[rand(0,count($politesse)-1)], $file);
		$file = preg_replace("#{EMAIL}#", $this->m_destinataire, $file);
		$file = preg_replace("#{TEAMNAME}#", TEAM_NAME, $file);
		$file = preg_replace("#{SITENAME}#", SITENAME, $file);
		
		$file = preg_replace("#{UNSUBSCRIBE}#", Router::url('member/unsubscribe'), $file);//*/
		echo $file;
		$content = ob_get_contents();
  		ob_end_clean();
 
		$this->m_textHtml = "--".$this->m_boundary ."\n";
		$this->m_textHtml .= "Content-Type: text/html; charset=ISO-8859-1\n";
		$this->m_textHtml .= "Content-Transfer-Encoding: 8bit\n\n";
		$this->m_textHtml .= htmlspecialchars_decode(htmlentities($content, ENT_NOQUOTES, 'UTF-8'));  

  	//	return $content;
	}
}
?>