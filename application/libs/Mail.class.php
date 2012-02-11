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
	
	public function __construct($p_objet,$p_text,$p_destinataire,$p_emetteur,$p_reponse = null,$p_importance = "Normal",$p_priority = 3)
	{
		
		$this->m_boundary = "----www.".$_SERVER['HTTP_HOST']."----".md5(time());
		$this->m_priority = $p_priority;
		$this->m_importance = $p_importance;
		$this->m_emetteur = $p_emetteur;
		if($p_reponse == null)
			$this->m_reponse = $p_emetteur;
		else
			$this->m_reponse = $p_reponse;
		$this->m_objet = $p_objet;
		$this->m_destinataire = $p_destinataire;
		$this->m_textTxt = $p_text;
	}
	
	public function __get($champ)
	{
		return $this->{$champ};
	}
	
	public function __set($champ,$value)
	{
		$this->{$champ} = $value;
	}
	
	private function reload_entete()
	{
		$this->m_entete = "From: ".$_SERVER['HTTP_HOST']." <".$this->m_emetteur .">\n";
		$this->m_entete .= "Mime-Version: 1.0\n";
		$this->m_entete .= "Content-Type: multipart/alternative; boundary=\"".$this->m_boundary ."\"\n";
		$this->m_entete .= "X-Sender: <www.".$_SERVER['HTTP_HOST'].">\n";
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
		
		 return (mail($this->m_destinataire,$this->m_objet,$this->m_textHtml,$this->m_entete)) ? true : false;	
	}
	
	public function sendMailText()
	{
		$this->reload_entete();
		return (mail($this->m_destinataire,$this->m_objet,$this->m_textTxt,$this->m_entete)) ? true : false;
	}
}
?>