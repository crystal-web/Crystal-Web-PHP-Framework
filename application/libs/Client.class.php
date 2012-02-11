<?php
/**
* @title Simple MVC systeme - Registre
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/

class client {
private $idClient = NULL;

public function setIdClient($iClient)
{
$this->idClient = (int) $iClient;
}
	public function newClient($idmember, $id_civilite, $prenom, $nom, $adresse1, $adresse2, $code_postal, $ville, $pays, $date_birthday, $telephone, $portable, $fax, $msn, $hash_validation)
	{

	$pdo = DB::getInstance();
	try
	{
	
	$requete = $pdo->prepare("INSERT INTO `" . __SQL . "_client`  SET
		idmember = :idmember,
		civility = :civility,
		first_name = :first_name,
		last_name = :last_name,
		adresse1 = :adresse1,
		adresse2 = :adresse2,
		code_postal = :code_postal,
		ville = :ville,
		pays = :pays,
		birthday = :birthday,
		msn = :msn,
		fax = :fax,
		portable = :portable,
		telephone = :telephone
		");

	$requete->bindValue(':idmember',$idmember, PDO::PARAM_INT);
	$requete->bindValue(':civility',$id_civilite, PDO::PARAM_INT); /* /!\ */
	$requete->bindValue(':first_name',$prenom, PDO::PARAM_STR);
	$requete->bindValue(':last_name',$nom, PDO::PARAM_STR);
	$requete->bindValue(':adresse1',$adresse1, PDO::PARAM_STR);
	$requete->bindValue(':adresse2',$adresse2, PDO::PARAM_STR);
	$requete->bindValue(':code_postal',$code_postal, PDO::PARAM_STR);
	$requete->bindValue(':ville',$ville, PDO::PARAM_STR);
	$requete->bindValue(':pays',$pays, PDO::PARAM_STR);	
	$requete->bindValue(':birthday',$date_birthday, PDO::PARAM_STR);
	$requete->bindValue(':msn',$msn, PDO::PARAM_STR);	
	$requete->bindValue(':fax',$fax, PDO::PARAM_STR);	
	$requete->bindValue(':telephone',$telephone, PDO::PARAM_STR);	
	$requete->bindValue(':portable',$portable, PDO::PARAM_STR);		
	$requete->execute();
		
		return $idmember;
	}
	catch (PDOException $e)
	{
		die ('Erreur interne: ' .$e->getMessage());
	}
}

	public function updClient($id_civilite, $prenom, $nom, $adresse1, $adresse2, $code_postal, $ville, $pays, $date_birthday, $telephone, $portable, $fax, $msn)
	{

	$pdo = DB::getInstance();
	try
	{
	
	$requete = $pdo->prepare("UPDATE `" . __SQL . "_client`  SET
		civility = :civility,
		first_name = :first_name,
		last_name = :last_name,
		adresse1 = :adresse1,
		adresse2 = :adresse2,
		code_postal = :code_postal,
		ville = :ville,
		pays = :pays,
		birthday = :birthday,
		msn = :msn,
		fax = :fax,
		portable = :portable,
		telephone = :telephone
		WHERE `idmember` = :idmember
		");

	$requete->bindValue(':idmember',$this->idClient, PDO::PARAM_INT);
	$requete->bindValue(':civility',$id_civilite, PDO::PARAM_INT); /* /!\ */
	$requete->bindValue(':first_name',$prenom, PDO::PARAM_STR);
	$requete->bindValue(':last_name',$nom, PDO::PARAM_STR);
	$requete->bindValue(':adresse1',$adresse1, PDO::PARAM_STR);
	$requete->bindValue(':adresse2',$adresse2, PDO::PARAM_STR);
	$requete->bindValue(':code_postal',$code_postal, PDO::PARAM_STR);
	$requete->bindValue(':ville',$ville, PDO::PARAM_STR);
	$requete->bindValue(':pays',$pays, PDO::PARAM_STR);	
	$requete->bindValue(':birthday',$date_birthday, PDO::PARAM_STR);
	$requete->bindValue(':msn',$msn, PDO::PARAM_STR);	
	$requete->bindValue(':fax',$fax, PDO::PARAM_STR);	
	$requete->bindValue(':telephone',$telephone, PDO::PARAM_STR);	
	$requete->bindValue(':portable',$portable, PDO::PARAM_STR);		
			
		return $requete->execute();
	}
	catch (PDOException $e)
	{
		die ('Erreur interne: ' .$e->getMessage());
	}
}


public function updAvatar($avatar)
{

	$pdo = DB::getInstance();
	try
	{
	$requete = $pdo->prepare("UPDATE `" . __SQL . "_client`  SET
		avatar = :avatar 
		WHERE `idmember` = :idmember
		");

	$requete->bindValue(':idmember',$this->idClient, PDO::PARAM_INT);
	$requete->bindValue(':avatar',$avatar, PDO::PARAM_STR);
			
		return $requete->execute();
	}
	catch (PDOException $e)
	{
		die ('Erreur interne: ' .$e->getMessage());
	}
}



public function updSignature($sign)
{

	$pdo = DB::getInstance();
	try
	{
	$requete = $pdo->prepare("UPDATE `" . __SQL . "_client`  SET
		signature = :sign 
		WHERE `idmember` = :idmember
		");

	$requete->bindValue(':idmember',$this->idClient, PDO::PARAM_INT);
	$requete->bindValue(':sign',$sign, PDO::PARAM_STR);
			
		return $requete->execute();
	}
	catch (PDOException $e)
	{
		die ('Erreur interne: ' .$e->getMessage());
	}
}

public function updBio($biographie)
{

	$pdo = DB::getInstance();
	try
	{
	$requete = $pdo->prepare("UPDATE `" . __SQL . "_client`  SET
		biographie = :biographie 
		WHERE `idmember` = :idmember
		");

	$requete->bindValue(':idmember',$this->idClient, PDO::PARAM_INT);
	$requete->bindValue(':biographie',$biographie, PDO::PARAM_STR);
			
		return $requete->execute();
	}
	catch (PDOException $e)
	{
		die ('Erreur interne: ' .$e->getMessage());
	}
}


public function updExtra($extra)
{
	$pdo = DB::getInstance();
	try
	{
	$requete = $pdo->prepare("UPDATE `" . __SQL . "_client`  SET
		extra = :extra 
		WHERE `idmember` = :idmember
		");

	$requete->bindValue(':idmember',$this->idClient, PDO::PARAM_INT);
	$requete->bindValue(':extra',serialize($extra), PDO::PARAM_STR);
			
		return $requete->execute();
	}
	catch (PDOException $e)
	{
		die ('Erreur interne: ' .$e->getMessage());
	}
}

	/*********************************************/
	/* Verifie le hash membre/client			 */
	/*********************************************/
	static function checkHash($hash, $mail=null)
	{
	$pdo = DB::getInstance();
	if ($mail == null && $hash != NULL)
	{

		$requete = $pdo->prepare("UPDATE  `" . __SQL . "_member` SET
			`hash_validation` =  '',
			`levelmember` = 2,
			`validemember` = 'on'
			WHERE `hash_validation`
				LIKE  :hash;");
		$requete->bindValue(':hash', $hash, PDO::PARAM_STR);
	}
	else
	{
		
		$requete = $pdo->prepare("UPDATE  `" . __SQL . "_member` SET
			`hash_validation` =  '',
			`levelmember` = 2,
			`validemember` = 'on'
			WHERE `hash_validation`
				LIKE  :hash
				AND `mailmember`
					LIKE :mail");
		$requete->bindValue(':hash', $hash, PDO::PARAM_STR);
		$requete->bindValue(':mail', $mail, PDO::PARAM_STR);
	}
		$requete->execute();
		return ($requete->rowCount() == 1);
	}
	
	
	/*********************************************/
	/* Vérifie la connection membre/client		 */
	/*********************************************/
	static function checkLogin($user, $password)
	{
	$pdo = DB::getInstance();
	$req = $pdo->prepare("SELECT * FROM `" . __SQL . "_client`
		WHERE
		login = '". $user."' AND 
		password = '".Securite::Hcrypt($user.$password)."' ");
	$req->execute();
	$result = $req->fetch();
		return $result;
	}
	

	public function getInfo()
	{
	$pdo = DB::getInstance();

	$requete = $pdo->query("SELECT *
		FROM  `" . __SQL . "_member`
			LEFT JOIN `" . __SQL . "_client`
				ON `" . __SQL . "_client`.`idmember` = `" . __SQL . "_member`.`idmember`
		WHERE  `" . __SQL . "_member`.`idmember` = " . $this->idClient . " ");

	$result = $requete->fetch(PDO::FETCH_ASSOC);

	$requete->closeCursor();
		
	return $result;
	}
	/*********************************************/
	/* Récupération des infos membre/client		 */
	/* Deprecate 	readInfo					*/
	/*********************************************/
	static function readInfo($id_utilisateur)
	{
	$pdo = DB::getInstance();

	$requete = $pdo->query("SELECT *
		FROM  `" . __SQL . "_member`
			LEFT JOIN `" . __SQL . "_client`
				ON `" . __SQL . "_client`.`idmember` = `" . __SQL . "_member`.`idmember`
		WHERE  `" . __SQL . "_member`.`idmember` = " . $id_utilisateur . " ");

	$result = $requete->fetch(PDO::FETCH_ASSOC);

	$requete->closeCursor();
		
	return $result;
	}
	
	static function readGroup($id){
	if ($id != NULL)
	{
	$id = explode("|", $id);
	$pdo = DB::getInstance();
	
	for ($i = 0; $i<count($id); ++$i)
	{
	$requete = $pdo->query("SELECT * 
		FROM  `" . __SQL . "_clientGroup`
		WHERE  `id` = ".$id[$i]."");

	$result[] = $requete->fetch(PDO::FETCH_ASSOC);

	$requete->closeCursor();
	}
	return $result;	
	}
	}
	
	

public function install()
{
$pdo = DB::getInstance();
$tableMembre = $pdo->prepare("CREATE TABLE IF NOT EXISTS `". __SQL . "_member` (
  `idmember` int(11) NOT NULL auto_increment,
  `loginmember` varchar(50) NOT NULL,
  `passmember` varchar(256) NOT NULL,
  `mailmember` varchar(256) NOT NULL,
  `validemember` enum('on','off') NOT NULL default 'off',
  `levelmember` int(1) NOT NULL default '1',
  `groupmember` text NOT NULL,
  `firstactivitymember` int(11) NOT NULL,
  `lastactivitymember` int(11) NOT NULL,
  `hash_validation` varchar(255) NOT NULL,
  PRIMARY KEY  (`idmember`),
  UNIQUE KEY `loginmember` (`loginmember`,`mailmember`),
  UNIQUE KEY `loginmember_2` (`loginmember`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
");
$tableMembre->execute();

$tableClient = $pdo->prepare("CREATE TABLE IF NOT EXISTS `". __SQL . "_client` (
  `idmember` int(11) NOT NULL,
  `civility` enum('2','1','0') NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `adresse1` varchar(200) NOT NULL,
  `adresse2` varchar(200) default NULL,
  `code_postal` varchar(20) default NULL,
  `pays` varchar(75) default NULL,
  `telephone` varchar(20) default NULL,
  `portable` varchar(20) default NULL,
  `fax` varchar(20) default NULL,
  `msn` varchar(250) default NULL,
  `birthday` int(11) NOT NULL,
  `ville` varchar(75) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
$tableClient->execute();
}

}