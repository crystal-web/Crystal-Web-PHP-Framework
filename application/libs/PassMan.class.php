<?php
class PassMan {

private $key;			# Cle de cryptage
private $output=array();

	public function __construct($key)
	{
		$this->key = $key;
	}

	/* Generateur de clé */
	private function GenKey($Texte,$key)
	{
	$keyMd = md5($key);
	$Compteur=0;$VariableTemp = "";
	
		for ($Ctr=0;$Ctr<strlen($Texte);$Ctr++)
		{
			if ($Compteur==strlen($keyMd))
			$Compteur=0;
			$VariableTemp.= substr($Texte,$Ctr,1) ^ substr($keyMd,$Compteur,1);
			$Compteur++;
		}
	return $VariableTemp;
	}

	/* Cryptage */
	public function Crypte($toCrypt)
	{
		$keyMd = md5(rand(0,32000) );
		$Compteur=0;
		$VariableTemp = "";
		for ($Ctr=0;$Ctr<strlen($toCrypt);$Ctr++)
		{
			if ($Compteur==strlen($keyMd))
			$Compteur=0;
			$VariableTemp.= substr($keyMd,$Compteur,1).(substr($toCrypt,$Ctr,1) ^ substr($keyMd,$Compteur,1) );
			$Compteur++;
		}
	return base64_encode($this->GenKey($VariableTemp,$this->key) );
	}

	/* Decryptage */
	public function Decrypte($toDecrypt)
	{
	$toDecrypt = $this->GenKey(base64_decode($toDecrypt),$this->key);
	$VariableTemp = "";
		for ($Ctr=0;$Ctr<strlen($toDecrypt);$Ctr++)
		{
			$subValue = substr($toDecrypt,$Ctr,1);
			$Ctr++;	
			$VariableTemp.= (substr($toDecrypt,$Ctr,1) ^ $subValue);
		}
	return $VariableTemp;
	}
}