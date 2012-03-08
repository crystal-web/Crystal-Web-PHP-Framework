<?php
/**
* @title Simple MVC systeme - Registre
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/

class DB extends PDO {

	private static $_instance;

	/* Constructeur : héritage public obligatoire par héritage de PDO */
	public function __construct( ) {
	
	}
	// End of PDO2::__construct() */

	/* Singleton */
	public static function getInstance() {
	
		if (!isset(self::$_instance)) {
			
			try {

				switch (strtolower(DB_type)){
					case 'mysql':
						$DB_connect='mysql:host='.DB_host.';port='.DB_port.';dbname='.DB_name;
					break;
					case 'pgsql':
						$DB_connect='pgsql:host='.DB_host.' port='.DB_port.' dbname='.DB_name;
					break;
					case 'sqlite':
						$DB_connect='sqlite:'.DB_host;
					break;	
					case 'oci':
						$DB_connect='OCI:'.DB_host;
					break;
				}			

				self::$_instance = new PDO($DB_connect, DB_user, DB_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
				self::$_instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				//self::$_instance->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);	
				
			
						
				
			} catch(PDOException $e){
				if(__DEV_MODE){
				die($e->getMessage()); 
				}else{
				die('Impossible de se connecter à la base de donnée'); 
				}
			}
		
		} 
		return self::$_instance; 
	}
	// End of PDO2::getInstance() */
}

class AvgStatement extends PDOStatement {
/*
$row = new AvgStatement;
$results = $db->query('SELECT symbol,planet FROM zodiac',PDO::FETCH_INTO, $row);
// Each time fetch() is called, $row is repopulated
while ($results->fetch()) {
    print "$row->symbol belongs to $row->planet (Average: {$row->avg()}) <br/>\n";
}
*/

    public function avg() {
        $sum = 0;
        $vars = get_object_vars($this);
        // Remove PDOStatement's built-in 'queryString' variable
        unset($vars['queryString']);
        foreach ($vars as $var => $value) {
            $sum += strlen($value);
        }
        return $sum / count($vars);
    }
}

?>