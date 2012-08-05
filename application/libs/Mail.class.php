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
		global $mvc;
		$this->reload_entete();
		
		
		$this->m_textHtml = "--".$this->m_boundary ."\n";
		$this->m_textHtml .= "Content-Type: text/html; charset=ISO-8859-1\n";
		$this->m_textHtml .= "Content-Transfer-Encoding: 8bit\n\n";
		$this->m_textHtml .= $this->m_textTxt;
		$this->m_textHtml .= $this->m_fileJoin;
		
		
		Log::setLog('Envois du mail en mode html', 'mail');
		return (mail($this->m_destinataire,'['.$mvc->Page->getSiteTitle().'] ' . $this->m_objet, $this->m_textHtml, $this->m_entete)) ? true : false;	
	}
	
	public function sendMailText()
	{
		Log::setLog('Envois du mail en mode texte', 'mail');
		$this->reload_entete();
		
		$this->m_textHtml = "--".$this->m_boundary ."\n";
		$this->m_textHtml .= "Content-Type: text/html; charset=ISO-8859-1\n";
		$this->m_textHtml .= "Content-Transfer-Encoding: 8bit\n\n";
		$this->m_textHtml .= $this->m_textTxt;
		$this->m_textHtml = "--".$this->m_boundary ."--\n\n";
		$this->m_textHtml .= $this->m_fileJoin;
		
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
	
	public function addFile($file_path, $file_name)
	{
		if (file_exists($file_path . DS . $file_name))
		{
	
		Log::setLog('Ajout de ' . $file_path . DS . $file_name . ' en pièce jointe', 'mail');
		$attached_file = file_get_contents($file_path . DS . $file_name); //file name ie: ./image.jpg 
		$attached_file = chunk_split(base64_encode($attached_file));


		
		$this->m_fileJoin = "\n\n". "--" .$this->m_boundary . "\nContent-Type: application; name=\"$file_name\"\r\nContent-Transfer-Encoding: base64\r\nX-Attachment-Id: f_".rand(1,65400)."\r\nContent-Disposition: attachment; filename=\"$file_name\"\r\n\n".$attached_file . "--" . $this->m_boundary . "--";
		}
		else
		{
		Log::setLog('Ajout de ' . $file_path . DS . $file_name . ' en pièce jointe', 'mail');
		}
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
		
		

		
		require __APP_PATH . DS . 'mail' . DS . 'mail.php';
		
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