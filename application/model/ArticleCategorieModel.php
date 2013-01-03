<?php 
Class ArticleCategorie extends Model {
	
	public function install()
	{
		$this->query("
			CREATE TABLE  `".__SQL."_ArticleCategorie` (
				`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
				`cname` VARCHAR( 64 ) NOT NULL ,
				`description` VARCHAR( 255 ) NOT NULL ,
				PRIMARY KEY (  `id` )
				) ENGINE = MYISAM ;
				");
	}
	
	
	
}
?>