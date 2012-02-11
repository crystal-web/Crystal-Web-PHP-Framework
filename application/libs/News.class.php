<?php
/*====================================================================================
	   ______                __        __                   __                     
	  / ____/______  _______/ /_____ _/ /    _      _____  / /_   ____  _________ _
	 / /   / ___/ / / / ___/ __/ __ `/ /____| | /| / / _ \/ __ \ / __ \/ ___/ __ `/
	/ /___/ /  / /_/ (__  ) /_/ /_/ / /_____/ |/ |/ /  __/ /_/ // /_/ / /  / /_/ / 
	\____/_/   \__, /____/\__/\__,_/_/      |__/|__/\___/_.___(_)____/_/   \__, /  
			  /____/                                                      /____/   
	 
 Creat by : Christophe BUFFET @Crystal-web.org
 Design by : J-C alias Stazus @Dyraz.com
 Tested by : Frag_For_Fun Team @gamer.crystal-web.org
 
 ====================================================================================*/
 /***
 
 List of functions
 
 // Creat flux RSS
 fct makeflux()
 
 // Add comment to news
 fct addComm($pseudo, $content, $mail, $website, $idnews, $valide = "0")
 
 // Add category et description
 fct addCat($categorie, $description)
 
 // Add a news
 fct addnews($id_auteur, $categorie, $titre, $content)
 
 // @private, add hit to news
 fct hits($id)
 
 // Get news
 fct getnews($start = 0, $limit = 30)
 
 // Count nb of news
 fct countnews()
 
 // Get category
 fct getCat()
 
 // Count comment
 fct countComm($id, $valide = "1")
 
 // Count all comment
 fct countTotalComm($valide = "0")
 
 // Get comment and nb of comment
 fct getComm($id = 'all', $valide = 1, $start = 0, $limit = 10)
 
 // Get unique news
 fct getUniquenews($id)
 
 // update news
 fct updnews($id, $categorie, $titre, $content)
 
 // Valide comment
 fct valideCom($id, $valide)
 
 // Delete news
 fct delnews($id)
 
 // @private install
 fct instal()
 
 ***/

class News {

public $output=NULL;
private  $pdo;
 public function __construct(PDO  $pdo)
 {
 $this->pdo =  $pdo;

 }
// Supprime une news
// Retourne un booléen : TRUE/FALSE 
	public function delnews($id) {
	//  $this->pdo = DB::getInstance();
	$req =  $this->pdo->prepare("DELETE FROM `" . __SQL . "_news` WHERE `id` = :id");
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	if ($req->execute())
	{
	
	$req =  $this->pdo->prepare("DELETE FROM `" . __SQL . "_news_hit` WHERE `id_news` = :id");
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->execute();
	
	$req =  $this->pdo->prepare("DELETE FROM `" . __SQL . "_news_commentaires` WHERE `id_news` = :id");
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->execute();
	
	}
	$this->makeflux();
	}

	

public function valideCom($id, $valide)
{
//  $this->pdo = DB::getInstance();
$req =  $this->pdo->prepare('UPDATE `' . __SQL . '_news_commentaires` SET
`valide` =  :valide
WHERE `id` = :id');
$req->bindValue(':id', $id, PDO::PARAM_INT);
$req->bindValue(':valide', $valide, PDO::PARAM_STR);
$req->execute();
}

public function updnews($id, $categorie, $titre, $content)
{
//  $this->pdo = DB::getInstance();
$req =  $this->pdo->prepare('UPDATE  `' . __SQL . '_news` SET
`categorieid` = :categorie,
`titre` = :titre,
`content` = :content
WHERE `id` = :id
');
$req->bindValue(':id', $id, PDO::PARAM_INT);
$req->bindValue(':categorie', $categorie, PDO::PARAM_INT);

$req->bindValue(':titre', $titre, PDO::PARAM_STR);
$req->bindValue(':content', $content, PDO::PARAM_STR);
$req->execute();
$this->makeflux();
return true;
}	

// Affichage d'un post unique
public function getUniquenews($id)
{
//  $this->pdo = DB::getInstance();
$req =  $this->pdo->prepare('SELECT `id`, `titre`, `content`, `date`, `categorieid`, `categorie`
	FROM `' . __SQL . '_news`
	LEFT JOIN `' . __SQL . '_news_cat` 
		ON `' . __SQL . '_news`.`categorieid` = `' . __SQL . '_news_cat`.`idcategorie`
	WHERE `id` = :id');
$req->bindValue(':id', $id, PDO::PARAM_INT);
if ($req->execute()) { $this->hits($id); }
return $req->fetch(PDO::FETCH_ASSOC);
}
 
// Récupére les commentaires d'une news
// Retourne le nombre de commentaires
public function getComm($id = 'all', $valide = 1, $start = 0, $limit = 10, $ordre='ASC')
{
//  $this->pdo = DB::getInstance();
if ($id == 'all')
{
$req =  $this->pdo->prepare('SELECT `' . __SQL . '_news_commentaires`.`id`,
`id_news`, `pseudo`,
`' . __SQL . '_news_commentaires`.`content`,
`ip`,
`Cdate`,
`mail`,
`website`,
`titre`
FROM `' . __SQL . '_news_commentaires`
LEFT JOIN `' . __SQL . '_news` ON `' . __SQL . '_news_commentaires`.`id_news` = `' . __SQL . '_news`.`id`
WHERE `valide` =  :valide
ORDER BY id '.$ordre.' 
LIMIT :start, :limit');
}
else
{
$req =  $this->pdo->prepare('SELECT `pseudo`, `content`, `ip`, `Cdate`, `mail`, `website`
FROM `' . __SQL . '_news_commentaires`
WHERE `id_news` = :id AND `valide` =  :valide
ORDER BY id '.$ordre.' 
LIMIT :start, :limit');
$req->bindValue(':id', $id, PDO::PARAM_INT);
}
$req->bindValue(':valide', $valide, PDO::PARAM_STR);
$req->bindValue(':start', $start, PDO::PARAM_INT);
$req->bindValue(':limit', $limit, PDO::PARAM_INT);
$req->execute();
return $req->fetchAll();
}

// Compte le nombre total de commentaires
static function countTotalComm($valide = "0")
{
$pdo = DB::getInstance();
$req =  $pdo->prepare('SELECT COUNT(*) AS `count`
FROM  `' . __SQL . '_news_commentaires` 
WHERE `valide` =  :valide');
$req->bindValue(':valide', $valide, PDO::PARAM_STR);
$req->execute();
$data = $req->fetch();
return ( (int) $data['count'] );
}
 
// Compte le nombre de commentaires d'une news 
public function countComm($id, $valide = "1")
{
//  $this->pdo = DB::getInstance();
$req =  $this->pdo->prepare('SELECT COUNT(*) AS `count`
FROM  `' . __SQL . '_news_commentaires` 
WHERE  `id_news` =:id
AND  `valide` =  :valide');
$req->bindValue(':id', $id, PDO::PARAM_INT);
$req->bindValue(':valide', $valide, PDO::PARAM_STR);
$req->execute();
$data = $req->fetch();
return ( (int) $data['count'] );
}


// Liste des categorie
public function getCat()
{
//  $this->pdo = DB::getInstance();
$req =  $this->pdo->prepare('SELECT `categorie`, `idcategorie` AS `id`
	FROM `' . __SQL . '_news_cat`
	ORDER BY  `' . __SQL . '_news_cat`.`categorie` ASC ');
$req->execute();
return $req->fetchAll();
}

// Comptage des news
public function countnews()
{
try
{
//  $this->pdo = DB::getInstance();
$req =  $this->pdo->prepare('SELECT COUNT(*) AS `count` FROM `' . __SQL . '_news`');
$req->execute();
$data = $req->fetch();
}
catch(PDOException $e)
{
 if ($e->getCode() == '42S02')
 {
 $this->install();
 return 0;
 }
 else
 {
 die ('Erreur interne: ' .$e->getMessage());
 }
}
 
return ( (int) $data['count'] );
}


// $start => début de la requète 
// $limit => Nombre d'enregistrement max
public function getnews($start = 0, $limit = 30)
{
//  $this->pdo = DB::getInstance();
$req =  $this->pdo->prepare("SELECT `" . __SQL . "_news`.`id`, `" . __SQL . "_news`.`titre`, `" . __SQL . "_news`.`content`, `date`, COUNT(`" . __SQL . "_news_commentaires`.`id`) AS `count` , `Cdate` AS `lastcomm`, `loginmember` AS `auteur`, `categorie`, `description`, `hit`
FROM `" . __SQL . "_news`
LEFT JOIN  `" . __SQL . "_member`
	ON `" . __SQL . "_news`.`id_auteur` = `" . __SQL . "_member`.`idmember`
LEFT JOIN  `" . __SQL . "_news_cat`
	ON `" . __SQL . "_news`.`categorieid` = `" . __SQL . "_news_cat`.`idcategorie`	
LEFT JOIN `" . __SQL . "_news_commentaires`
	ON `" . __SQL . "_news`.`id` = `" . __SQL . "_news_commentaires`.`id_news` 
		AND `" . __SQL . "_news_commentaires`.`valide` = '1'
GROUP BY `" . __SQL . "_news`.`id`
ORDER BY `" . __SQL . "_news`.`id`
DESC LIMIT :start, :limit");

$req->bindValue(':start', $start, PDO::PARAM_INT);
$req->bindValue(':limit', $limit, PDO::PARAM_INT);
$req->execute();
return $req->fetchAll();
}


// $start => début de la requète 
// $limit => Nombre d'enregistrement max
public function getNextNews($start = 0, $limit = 30)
{
//  $this->pdo = DB::getInstance();
$req =  $this->pdo->prepare("SELECT `" . __SQL . "_news`.`id`, `" . __SQL . "_news`.`titre`, `" . __SQL . "_news`.`content`, `date`, COUNT(`" . __SQL . "_news_commentaires`.`id`) AS `count` , `Cdate` AS `lastcomm`, `loginmember` AS `auteur`, `categorie`, `description`, `hit`
FROM `" . __SQL . "_news`
LEFT JOIN  `" . __SQL . "_member`
	ON `" . __SQL . "_news`.`id_auteur` = `" . __SQL . "_member`.`idmember`
LEFT JOIN  `" . __SQL . "_news_cat`
	ON `" . __SQL . "_news`.`categorieid` = `" . __SQL . "_news_cat`.`idcategorie`	
LEFT JOIN `" . __SQL . "_news_commentaires`
	ON `" . __SQL . "_news`.`id` = `" . __SQL . "_news_commentaires`.`id_news` 
		AND `" . __SQL . "_news_commentaires`.`valide` = '1'
WHERE `" . __SQL . "_news`.`id` < :plusgrandque
GROUP BY `" . __SQL . "_news`.`id`
ORDER BY `" . __SQL . "_news`.`id` DESC
LIMIT :start, :limit");

$req->bindValue(':plusgrandque', $start, PDO::PARAM_INT);

$req->bindValue(':start', 0, PDO::PARAM_INT);
$req->bindValue(':limit', $limit, PDO::PARAM_INT);
$req->execute();
return $req->fetchAll();
}

/*
Ajout a la BD
*/
// Incremente le hit
private function hits($id)
{
//  $this->pdo = DB::getInstance();
$req =  $this->pdo->prepare('SELECT COUNT( * ) AS `count` 
FROM  `' . __SQL . '_news_hit` 
WHERE  `id_news` = :id
AND  `ip` LIKE  :ip
AND  `time` > :time');
$req->bindValue(':id', $id, PDO::PARAM_INT);
$req->bindValue(':time', (time()-86400), PDO::PARAM_INT);
$req->bindValue(':ip', Securite::ipX(), PDO::PARAM_STR);
$req->execute();
$data = $req->fetchAll();
	// Si il n'y a pas d'entré
	if ($data[0]['count'] == 0)
	{
	$upd =  $this->pdo->prepare('UPDATE `' . __SQL . '_news` SET `hit` = `hit`+1 WHERE `id` = :id;');
	$upd->bindValue(':id', $id, PDO::PARAM_INT);
	$upd->execute();
	$ins =  $this->pdo->prepare('INSERT `' . __SQL . '_news_hit` SET
		`id_news` = :id_news,
		`ip` = :ip,
		`time` = :time');
	$ins->bindValue(':id_news', $id, PDO::PARAM_INT);
	$ins->bindValue(':time', time(), PDO::PARAM_INT);
	$ins->bindValue(':ip', Securite::ipX(), PDO::PARAM_STR);
	$ins->execute();
	}
}

// Ajout d'une news
public function addnews($id_auteur, $categorie, $titre, $content)
{
//  $this->pdo = DB::getInstance();
$req =  $this->pdo->prepare('INSERT INTO  `' . __SQL . '_news` SET
`id_auteur` = :auteur,
`categorieid` = :categorie,
`titre` = :titre,
`content` = :content,
`date` = :time
');
$req->bindValue(':auteur', $id_auteur, PDO::PARAM_INT);
$req->bindValue(':categorie', $categorie, PDO::PARAM_INT);
$req->bindValue(':time', time(), PDO::PARAM_INT);

$req->bindValue(':titre', $titre, PDO::PARAM_STR);
$req->bindValue(':content', $content, PDO::PARAM_STR);
$req->execute();
$this->makeflux();
return true;
}	

// Ajout d'un catégorie, retourne true
public function addCat($categorie, $description)
{
//  $this->pdo = DB::getInstance();
$req =  $this->pdo->prepare('INSERT INTO  `' . __SQL . '_news_cat` SET
`categorie` = :categorie,
`description` = :description
');
$req->bindValue(':categorie', $categorie, PDO::PARAM_STR);
$req->bindValue(':description', $description, PDO::PARAM_STR);
return $req->execute();
}

// Ajoute un commentaire sur une news.
public function addComm($pseudo, $content, $mail, $website, $idnews, $valide = "0")
{
//  $this->pdo = DB::getInstance();
$req =  $this->pdo->prepare('INSERT INTO ' . __SQL . '_news_commentaires SET 
`pseudo` = :pseudo,
`content` = :content,
`Cdate` = :time,
`id_news` = :id_news,
`ip` = :ip,
`mail` = :mail,
`website` = :website,
`valide` = :valide
');
$req->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
$req->bindValue(':content', $content, PDO::PARAM_STR);
$req->bindValue(':mail', $mail, PDO::PARAM_STR);
$req->bindValue(':website', $website, PDO::PARAM_STR);
$req->bindValue(':id_news', $idnews, PDO::PARAM_INT);
$req->bindValue(':time', time(), PDO::PARAM_INT);
$req->bindValue(':ip', Securite::ipX(), PDO::PARAM_STR);
$req->bindValue(':valide', $valide, PDO::PARAM_STR);
	if ($req->execute())
	{
	return true;
	}
	else
	{
	return false;
	}
}

public function makeflux()
{
$today= date("D, d M Y H:i:s +0100");
$xml = '<rss version="0.92" encoding="ISO-8859-1">
<channel>
<title>Crystal-Web News</title>
<link>' . __CW_PATH . '</link>
<description>Quand l\'informatique devient humain.</description>
<lastBuildDate>' . $today . '</lastBuildDate>
<docs>http://backend.userland.com/rss092</docs>
<language>fr</language>
<!-- generator="Crystal-Web.org/1.2.5" -->';  
// extraction des 30 dernières nouvelles
$data = $this->getNews(0, 30);
foreach ($data as $lig)
{
$titre=$lig["titre"];
$adresse=url('index.php?module=news&action=post&p=' . $lig['id'] . '&' . $lig['titre']);
$contenu=strip_tags($lig["content"]);
$date=$lig["date"];
$datephp=date("D, d M Y H:i:s +0100", $date);
$xml .= '<item>';
$xml .= '<title><![CDATA['.utf8_decode($titre).']]></title>';
$xml .= '<link>'.utf8_decode($adresse).'</link>';
$xml .= '<pubDate>'.$datephp.'</pubDate>'; 
$xml .= '<description>
<![CDATA[
'.utf8_decode($contenu).'
]]>
</description>
';
$xml .= '</item>
'; 
}//fin du while
$xml .= '</channel>
';
$xml .= '</rss>
';

$fp = fopen("./FluxRssNews.xml", 'w+');
fputs($fp, $xml);
fclose($fp);
}



private function install()
{
//  $this->pdo = DB::getInstance();
/*** Table maitresse ***/
$req =  $this->pdo->prepare("CREATE TABLE IF NOT EXISTS `". __SQL . "_news` (
	`id` int(11) NOT NULL auto_increment,
	`id_auteur` int(11) NOT NULL,
	`categorieid` int(11) NOT NULL,
	`titre` varchar(255) NOT NULL,
	`content` longtext NOT NULL,
	`date` int(11) NOT NULL,
	`hit` bigint(20) NOT NULL default '0',
	PRIMARY KEY  (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
");

	if ($req->execute())
	{
	/*** Table catégorie ***/
	$req =  $this->pdo->prepare("CREATE TABLE IF NOT EXISTS `". __SQL . "_news_cat` (
		`idcategorie` int(11) NOT NULL auto_increment,
		`categorie` varchar(50) NOT NULL,
		`description` varchar(255) NOT NULL,
		PRIMARY KEY  (`idcategorie`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
	");
		
		if ($req->execute())
		{
		/*** Table commentaire ***/
		$req =  $this->pdo->prepare("CREATE TABLE IF NOT EXISTS `". __SQL . "_news_commentaires` (
				`id` int(11) NOT NULL auto_increment,
				`pseudo` varchar(255) NOT NULL,
				`content` text NOT NULL,
				`Cdate` int(11) NOT NULL,
				`id_news` int(11) NOT NULL,
				`ip` varchar(255) NOT NULL,
				`mail` varchar(255) default NULL,
				`website` varchar(255) default NULL,
				`valide` enum('0','1','2') NOT NULL default '0' COMMENT '0 = non valide, 1 = valide, 2 = spam',
				PRIMARY KEY  (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
		");


			if ($req->execute())
			{
			/*** Table commentaire ***/
			$req =  $this->pdo->prepare("CREATE TABLE IF NOT EXISTS `". __SQL . "_news_hit` (
					`id` bigint(20) NOT NULL auto_increment,
					`id_news` int(11) NOT NULL,
					`ip` varchar(255) NOT NULL,
					`time` int(1) NOT NULL,
					PRIMARY KEY  (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
					");

				if ($req->execute())
				{
				/*** Table commentaire ***/
				$req =  $this->pdo->prepare("CREATE TABLE IF NOT EXISTS `". __SQL . "_member` (
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
				
				
					if ($req->execute())
					{
						// Ajout du menu de l'administration
						$lienAdminCache = new Cache('admin/menu');
						$lienAdmin = $lienAdminCache->getCache();
						$lienAdmin[] = array(
							'url' => 'admin_news',
							'name' => 'News');	
						$lienAdminCache->setCache($lienAdmin);
						
						// Enregistrement du module
						$moduleCache = new Cache('admin/module');
						$inCache = $moduleCache->getCache();
						
							/*** Info module ***/
							// Developpeur
							$inCache['news']['auteur']='Christophe BUFFET';						
							$inCache['news']['site_auteur']='http://crystal-web.org'; // L'adresse de mon site
							// Application
							$inCache['news']['app_name']='News';
							$inCache['news']['description']='News type blog, avec Flux RSS, commentaire avec &eacuteditoriel.';
							// Technique
							$inCache['news']['sql']=true;
							$inCache['news']['cache']=false;
							$inCache['news']['cookie']=false;
							$inCache['news']['compatibilite']=11.9;
							$inCache['news']['version']=1.0;
							$inCache['news']['copyright']='Copyright (&copy;) 2010-2011 Crystal Team';
						// Enregistrement
						$moduleCache->setCache($inCache);
						unset($moduleCache);
					}
				}
				else
				{
				die('Erreur interne: installation niveau 4');
				}	
				
			}
			else
			{
			die('Erreur interne: installation niveau 3');
			}
		
		}
		else
		{
		die('Erreur interne: installation niveau 2');
		}

	}
	else
	{
	die('Erreur interne: installation niveau 1');
	}
}

}
?>