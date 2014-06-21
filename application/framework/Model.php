<?php
/*##################################################
 *                                 Model.php
 *                            -------------------
 *   begin                : 2012-03-08
 *   copyright            : (C) 2012 DevPHP
 *   email                : developpeur@crystal-web.org
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

/* Optimisation http://sqlpro.developpez.com/cours/optimiser/#L9
 **/
 
/**
 * Change logs
 * 
 * + Création d'une class AnonymousModel pour les connexion externe. (ne retiens pas la connexion)
 * + Séparation des méthodes
 * 
 */

 
 class methodModel  {
	public $errors=array();
	public static $useBenchmark = true;
		private $benchmark = array();
		protected static $count = 0;
		private static $memoryusage = 0;
		private $lastRequest;
		
	
	public $table = false;		// Table sur laquel les requetes s'effectue
	public $tableAs;			// Alias de la table $table AS $tableAs 
	
	public $primaryKey = 'id';
	
	/**
	 * Permet de valider des données
	 * 
	 * @param $data données  à valider
	 * @param array $validaterules variable des régles
	 * @return array
	 */
	function validates($data, $validaterules = 'validate') {
		$errors = array ();
		$thisValidateRules = isSet ( $this->$validaterules ) ? $this->$validaterules : $this->validate;
		
		foreach ( $thisValidateRules as $k => $v ) {
			if (! isset ( $data->$k )) {
				$errors [$k] = $v ['message'];
			} else {
				if ($v ['rule'] == 'noEmpty') {
					if (empty ( $data->$k )) {
						$errors [$k] = $v ['message'];
					}
				} elseif ($v ['rule'] == 'notEmpty') {
					if (empty ( $data->$k )) {
						$errors [$k] = $v ['message'];
					}
				} elseif ($v ['rule'] == 'isMail') {
					$mail = library ( 'mailjetable' );
					$explode = explode ( '@', strtolower ( $data->$k ) );
					
					if (isSet($explode [1])) {
						if (( bool ) array_search ( $explode [1], $mail )) {
							$errors [$k] = $v ['message'];
						}
					}
					
					if (! filter_var ( $data->$k, FILTER_VALIDATE_EMAIL )) {
						$errors [$k] = $v ['message'];
					}
				
				} elseif (! preg_match ( '#' . $v ['rule'] . '#', $data->$k )) {
					$errors [$k] = $v ['message'];
				}
			}
			
			if (isSet ( $v ['callback'] )) {
				if (! call_user_func ( $v ['callback'], $data->$k )) {
					$errors [$k] = $v ['message'];
				}
			}
		}
		$this->errors = $errors;
		
		if (empty ( $errors )) { 
			return true;
		}
		return false;
	}
	
	/**
	 * Permet de récupérer plusieurs enregistrements
	 * 
	 * @param $req Tableau  contenant les éléments de la requête
	 * @return stdClass 
	 */
	public function find($req = array(), $fetch = PDO::FETCH_OBJ) {
		$this->benchmarkStart();
		try {
			$qc = defined('MYSQLND_QC_DISABLE_SWITCH') ? MYSQLND_QC_DISABLE_SWITCH : 'qc=on'; 
			$sql = '/*'.$qc.'*/SELECT ';
			
			if (isset ( $req ['fields'] )) {
				if (is_array ( $req ['fields'] )) {
					$sql .= implode ( ', ', $req ['fields'] );
				} else {
					$sql .= $req ['fields'];
				}
			} else {
				$sql .= '*';
			}
			
			$sql .= ' FROM `' . $this->table . '` as `' . $this->tableAs . '` ';
			
			// Liaison simple
			if (isset ( $req ['join'] )) {
				foreach ( $req ['join'] as $k => $v ) {
					$sql .= 'LEFT JOIN ' . $k . ' ON ' . $v . ' ';
				}
			}
			
			// Liaison a gauche (non null)
			if (isset ( $req ['leftouter'] )) {
				foreach ( $req ['leftouter'] as $k => $v ) {
					$sql .= 'LEFT OUTER JOIN ' . $k . ' ON ' . $v . ' ';
				}
			}
			
			// Liaison a droite
			if (isset ( $req ['rightouter'] )) {
				foreach ( $req ['rightouter'] as $k => $v ) {
					$sql .= 'RIGHT OUTER JOIN ' . $k . ' ON ' . $v . ' ';
				}
			}		
		
			if (isset ( $req ['inner'] )) {
				foreach ( $req ['inner'] as $k => $v ) {
					$sql .= 'INNER JOIN ' . $k . ' ON ' . $v . ' ';
				}
			}
			
			// Construction de la condition
			if (isset ( $req ['conditions'] ) or isset ( $req['like']) ) {
				$req['conditions'] = (isSet($req['like'])) ? $req['like'] : $req['conditions'];
				$sql .= 'WHERE ';
				if (! is_array ( $req ['conditions'] )) {
					$sql .= $req ['conditions'];
				} else {
					$cond = array ();
					foreach ( $req ['conditions'] as $k => $v ) {
						if (!is_numeric( $v )) {
							if (is_array ( $v )) {
								debug ( $v );
							}
							$v = $this->pdo->quote($v);
							//$v = '"' . mysql_real_escape_string ( $v ) . '"';
							$cond[] = '`'.$k.'` LIKE ' .$v;
						} else {
							$v = (int) $v;
							$cond[] = '`'.$k.'`=' .$v;
						}
					}
					$sql .= implode ( ' AND ', $cond );
				}
			}
			
			if (isset ( $req ['group'] )) {
				$sql .= ' GROUP BY ' . $req ['group'];
			}
			
			if (isset ( $req ['order'] )) {
				$sql .= ' ORDER BY ' . $req ['order'];
			}
			
			if (isset ( $req ['limit'] )) {
				$sql .= ' LIMIT ' . $req ['limit'];
			}
			
			$this->setLastRequest($sql);
			$pre = $this->pdo->prepare ( $sql );
			
			$pre->execute();
		} catch ( Exception $e ) {
			$this->setLogSql('Find::Exception::' . $e->getMessage(), 'info');
			if ($e->getCode () == '42S02') {
				if (method_exists ( $this, 'install' )) {
					$this->install();
				}
			}
		}
		$this->setLastRequest($sql);
		$this->benchmarkStop();
		return $pre->fetchAll ( $fetch );
	}
	
	/**
	 * Alias permettant de retrouver le premier enregistrement
	 * 
	 * @return stdClass
	 */
	public function findFirst($req = array()) {
		return current ( $this->find ( $req ) );
	}

	/**
	 * Permet de compté les entré d'une table
	 * 
	 * @param $conditions si défini, le résultat est celui de la requete
	 * @return int 
	 */
	public function count($conditions = false) {
		if ( !$conditions ) {
			$res = $this->findFirst ( array ('fields' => 'COUNT(' . $this->primaryKey . ') as count') );
		} else {
			$res = $this->findFirst ( array (
				'fields' => 'COUNT(' . $this->primaryKey . ') as count',
				'conditions' => $conditions
				) );
		}
		return ($res) ? $res->count : 0;
	}
	
	/**
	 * Permet de récupérer un tableau indexé par primaryKey et avec name pour
	 * valeur
	 */
	public function findList($req = array()) {
		if (! isset ( $req ['fields'] )) {
			$req ['fields'] = $this->primaryKey . ',name';
		}
		$d = $this->find ( $req );
		$r = array ();
		foreach ( $d as $k => $v ) {
			$r [current ( $v )] = next ( $v );
		}
		return $r;
	}
	
	/**
	 * Permet de supprimer un enregistrement
	 * 
	 * @param $id ID  de l'enregistrement à supprimer
	 * @return PDOStatement
	 */
	public function delete($id) {
		$this->benchmarkStart();		
		// Comme on ne eu pas faire DELETE cle textuelle, on ajout des "
		$id = is_int($id) ? $id : '"'.$id.'"';
		$sql = "DELETE FROM `{$this->table}` WHERE `{$this->table}`.`{$this->primaryKey}` = $id";
		
		$this->setLastRequest($sql);
		$this->benchmarkStop();
		return $this->pdo->query ( $sql );
	}
	
	/**
	 * Permet de sauvegarder des données
	 * 
	 * @param $data Données  à enregistrer
	 * @return boolean
	 */
	public function save($data) {
		if (!is_object($data)) {
			throw new ErrorException('Model: $data n\'est pas un objet', 107, 0, __FILE__, __LINE__);
		}
		$this->benchmarkStart();
		try {
			$key = $this->primaryKey;
			$fields = array ();
			$d = array ();
			foreach ( $data as $k => $v ) {
				if ($k != $this->primaryKey) {
					// Incrementation par UPDATE formule($data->key = 'key+1';)
					if (preg_match ( '#^' . $k . '+\+[0-9]+#', $v )) {
						$fields [] = "`$k`=$v";
					} else {
						$fields [] = "`$k`=:$k";
						$d [":$k"] = $v;
					}
				} elseif (! empty ( $v )) {
					$d [":$k"] = $v;
				}
			}
			if (isset ( $data->$key ) && ! empty ( $data->$key )) {
				$sql = 'UPDATE ' . $this->table . ' SET ' . implode ( ',', $fields ) . ' WHERE ' . $key . '=:' . $key;
				$this->id = $data->$key;
				$action = 'update';
			} else {
				$sql = 'INSERT INTO ' . $this->table . ' SET ' . implode ( ',', $fields );
				$action = 'insert';
			}
			$this->setLastRequest($sql);
			$pre = $this->pdo->prepare ( $sql );
			$bool = $pre->execute ( $d );

		$this->benchmarkStop();
		} catch ( Exception $e ) {
			$this->lastError = $e->getMessage() . ' for ' . get_class ( $this ) . ' in ' . $this->table;
			$this->setLogSql('Save::Exception::' . $e->getMessage(), 'alert');
			if ($e->getCode() == '42S02') {
				if (method_exists ( $this, 'install' )) {
					$this->install ();
					return $this->save( $data );
				}
			}
		}
		return isSet($bool) ? $bool : false;
	}
	
    /*
     * Pour les accros de PDO
     */
    public function query($sql, $fetch = false) {
	$this->benchmarkStart();
	$result = false;
    	try {
	        $this->setLastRequest($sql);
	        $pre = $this->pdo->prepare ( $sql );
	        // debug($sql);
	        if ($fetch) {
	            $pre->execute();
	            $result = $pre->fetchAll ( PDO::FETCH_OBJ );
	        } else { $result = $pre->execute(); }
    	} catch(PDOException $e) {
    		$this->setLogSql('Query::Exception::' . $e->getMessage(), 'alert');
    	}
	$this->benchmarkStop();
	return $result;
    }
	
	/**
	 * 
	 * Alias pour getLastInsertId()
	 * Retourne le dernier ID apres un insert
	 * @deprecated
	 */
	public function getLastId() {
		return $this->getLastInsertId();
	}
	
	/**
	 * 
	 * Retourne le dernier ID apres un insert
	 */
	public function getLastInsertId() {
		$this->setLogSql('getLastId::is::' . $this->pdo->lastInsertId(), 'info');
		return $this->pdo->lastInsertId();
	}
	


	/**
	 * Mesure des latences dans le but d'accèlèrè le procéssus d'accès à la DB
	 */
	protected function benchmarkStart(){
		if (self::$useBenchmark) {
			$this->benchmark['startMicrotime'] = microtime(true);
			$this->benchmark['startMemory'] = memory_get_usage();
		}
	}

	protected function benchmarkStop() {
		if (self::$useBenchmark) { // benchmark
			$memori = function ($size) {
				if ($size < 0) {
					return 0;
				}
				$unit=array('b','kb','mb','gb','tb','pb');
				return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
			};
			self::$count++;
			
			$mem = memory_get_usage()-$this->benchmark['startMemory'];
			self::$memoryusage += $mem;
			$alert = ($mem > 10240) ? 'alert' : 'info'; 
			$this->setLogSql( get_class( $this ) . ' '.round(microtime(true)-$this->benchmark['startMicrotime'], 5) . ' sec ' . $memori($mem) .': ' . $this->getLastRequest(), $alert); 
		} else {
			$this->setLogSql($sql, 'info');
		}
	}
	
	public static function getMemoryUsage() {
		return self::$memoryusage;
	}//*/
	
	/**
	 *	Dernière action SQL
	 */
	protected function setLastRequest($lastRequest) {
		$this->lastRequest = $lastRequest;
	}
	
	public function getLastRequest() {
		return $this->lastRequest;
	}//*/
	
	public function setTableAlias($alias) {
		$this->tableAs = (strlen($alias) > 0) ? $alias : 'CW';
		$this->setLogSql('Table alias is ' . $this->tableAs);
	}
	
	public function getTableAlias() {
		return $this->tableAs;
	}
	
	public function setTable($table, $noPrefix = false) {
		$table = (strlen($table) > 0) ? $table : 'CW';
		$this->table = ($noPrefix) ? $table : __SQL . '_' . $table;
		$this->setLogSql('Table is ' . $this->table);
	}
	
	public function getTable() {
		return $this->table;
	}
}

class Model extends methodModel{

	protected $pdo;
	public static $currentConnection;
	private static $log;
	
	public function __construct() {
		if (!isset(self::$currentConnection)) {

			$this->connect();
		} else { 
	
			$this->pdo = self::$currentConnection;
		}//*/
		
		if ($this->table === false) {
			$this->setTableAlias(preg_replace ( '#Model#', '', get_class ( $this ) ));
			$this->setTable($this->getTableAlias());
		}
	}
	
	public function __destruct() {
		$this->pdo = null;
	}
	
	/**
	 * Connection a la base de donnée
	 */
	public function connect($db_driver=NULL, $db_hostname=NULL, $db_username=NULL, $db_password=NULL, $db_database=NULL, $db_prefix = null) {
	$this->benchmarkStart();
		
		//	Si nous n'avons aucun paramettres,
		//	On utilise ceux de base
		if (empty($db_driver)) {
			$db_driver=DB_DRIVER;
			$db_hostname=DB_HOSTNAME;
			$db_username=DB_USERNAME;
			$db_password=DB_PASSWORD;
			$db_database=DB_DATABASE;
		}
		
		// Recherche la connexion a utilisé
		switch (strtolower($db_driver)){
			case 'mysql':
				$connection = 'mysql:host='.$db_hostname.';port=3306;dbname='.$db_database;
			break;
			case 'pgsql':
				$connection = 'pgsql:host='.$db_hostname.' port=4444 dbname='.$db_database;
			break;
			case 'sqlite':
				$connection = 'sqlite:'.$db_hostname;
			break;	
			case 'oci':
				$connection = 'OCI:'.$db_hostname;
			break;
			default:
				die('Model::Driver not found');
			break;
		}
		
		try {
			$this->pdo = new PDO($connection, $db_username, $db_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->setLastRequest('Connection to: ' . $connection);
			
			// Sauve la connexion avec les paramettres
			self::$currentConnection = $this->pdo;
		} catch(PDOException $e){
		
			switch ($e->getCode()) {
				case '1040':
					die('D&eacute;sol&eacute;, nous sommes victime de notre succ&egrave;s. Trop de connexion sont d&eacute;j&agrave; ouvertes. Réessayez plus tard.');
				break;
				case '1044':
					$contenu = 'Impossible de se connecter &agrave; la base de donn&eacute;e';
					if (DB_DATABASE == 'password' || DB_DATABASE == 'databasename' || DB_USERNAME == 'username') {
						$contenu .= '<br><small>Il doit y avoir un fichier init.php dans ' . __SITE_PATH . DS . 'includes, que vous n\'avez pas modifier</small>';
					}
					die($contenu);
				break;
				case '1045':
					$contenu = 'Model::Impossible de se connecter &agrave; la base de donn&eacute;e';
					if (DB_DATABASE == 'password' || DB_DATABASE == 'databasename' || DB_USERNAME == 'username') {
						$contenu .= '<br><small>Il doit y avoir un fichier init.php dans ' . __SITE_PATH . DS . 'includes, que vous n\'avez pas modifier</small>';
					}
					if (__DEV_MODE) {
						throw new Exception("Syntax Error: " . $e->getMessage(), $e->getCode());
					}
					die($contenu);
				break;
				case '2A000':
					if (__DEV_MODE) {
						throw new Exception("Syntax Error: " . $e->getMessage(), $e->getCode());
					}
				break;
				default:
					throw new Exception ($e->getMessage() .' ' . get_class ( $this ), $e->getCode()); 
				break;
			}
		}
	$this->benchmarkStop();
	}//*/

	/**
	 *	Systeme de log interne
	 */
	public function setLogSql($logToSet, $type='info') {
		self::$log[] = array('message' => $logToSet, 'type' => strtoupper($type));
	}

	public function getLog() {
		return self::$log; 
	}
}


class AnonymousModel extends methodModel{

	protected $pdo;
	private static $log;
	private $currentConnection;
	
	public function __construct() {
		if ($this->table === false) {
			$this->setTableAlias(preg_replace ( '#Model#', '', get_class ( $this ) ));
			$this->setTable($this->getTableAlias());
		}
	}
	
	public function __destruct() {
		$this->pdo = null;
	}
	
	/**
	 * Connection a la base de donnée
	 */
	public function connect($db_driver=NULL, $db_hostname=NULL, $db_username=NULL, $db_password=NULL, $db_database=NULL, $db_prefix = null) {
	$this->benchmarkStart();
		
		//	Si nous n'avons aucun paramettres,
		//	On utilise ceux de base
		if (empty($db_driver)) {
			$db_driver=DB_DRIVER;
			$db_hostname=DB_HOSTNAME;
			$db_username=DB_USERNAME;
			$db_password=DB_PASSWORD;
			$db_database=DB_DATABASE;
		}
		
		// Recherche la connexion a utilisé
		switch (strtolower($db_driver)){
			case 'mysql':
				$connection = 'mysql:host='.$db_hostname.';port=3306;dbname='.$db_database;
			break;
			case 'pgsql':
				$connection = 'pgsql:host='.$db_hostname.' port=4444 dbname='.$db_database;
			break;
			case 'sqlite':
				$connection = 'sqlite:'.$db_hostname;
			break;	
			case 'oci':
				$connection = 'OCI:'.$db_hostname;
			break;
		}
			if ($connection == $this->currentConnection) {
				$this->benchmarkStop();
				return;
			}
			
		try {
			$this->pdo = new PDO($connection, $db_username, $db_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->setLastRequest('Connection to: ' . $connection);
			$this->currentConnection = $connection;
		} catch(PDOException $e){
		
			switch ($e->getCode()) {
				case '1040':
					die('D&eacute;sol&eacute;, nous sommes victime de notre succ&egrave;s. Trop de connexion sont d&eacute;j&agrave; ouverte. R&eacute;&eacute;say&eacute; plus tard.');
				break;
				case '1044':
					$contenu = 'Impossible de se connecter &agrave; la base de donn&eacute;e';
					if (DB_DATABASE == 'password' || DB_DATABASE == 'databasename' || DB_USERNAME == 'username') {
						$contenu .= '<br><small>Il doit y avoir un fichier init.php dans ' . __SITE_PATH . DS . 'includes, que vous n\'avez pas modifier</small>';
					}
					die($contenu);
				break;
				case '1045':
					$contenu = 'Impossible de se connecter &agrave; la base de donn&eacute;e';
					if (DB_DATABASE == 'password' || DB_DATABASE == 'databasename' || DB_USERNAME == 'username') {
						$contenu .= '<br><small>Il doit y avoir un fichier init.php dans ' . __SITE_PATH . DS . 'includes, que vous n\'avez pas modifier</small>';
					}
					die($contenu);
				break;
				case '2A000':
					if (__DEV_MODE) {
						throw new Exception("Syntax Error: " . $e->getMessage(), $e->getCode());
					}
				break;
				default:
					throw new Exception ($e->getMessage() .' ' . get_class ( $this ), $e->getCode()); 
				break;
			}
		}
	$this->benchmarkStop();
	}//*/
	
	/**
	 *	Systeme de log interne
	 */
	public function setLogSql($logToSet, $type='info') {
		self::$log[] = array('message' => $logToSet, 'type' => strtoupper($type));
	}

	public function getLog() {
		return self::$log; 
	}
}


/*
 * Requete SQL avec des chainage de type factory
 *
 *
 **/
class SQLFluent extends Model {
	public $param=array();

	/**
	 * Permet de définir les champs/colonnes désirer
	 * 
	 * @param $where_fields
	 * @return SQLFluent
	 */
	public function select($where_fields) {
		$this->param['fields'] = $where_fields;
		return $this;
	}

	/**
	 * Permet de faire de définir la table et son prefix
	 * 
	 * @param string $where_Table Le nom de la table
	 * @param boolean $prefixed définir si $where_Table est préfixé ou pas 
	 * @return SQLFluent
	 */
	public function from($where_Table, $prefixed = false) {
		if ($prefixed) $where_Table = __SQL . '_' . $where_Table;
		
		$this->table = $where_Table;
		return $this;
	}

	/**
	 * Permet de définir la conditions
	 * 
	 * @return SQLFluent
	 */
	public function where($conditions) {
		$this->param['conditions'] = $conditions . ' ';
		return $this;
	}

	/**
	 * Permet de définir l'ordre
	 * 
	 * @return SQLFluent
	 */
	public function order($orderedby) {
		$this->param['order'] = $orderedby;
		return $this;
	}

	/**
	 * Permet de définir la limit
	 * 
	 * @return SQLFluent
	 */
	public function limit($limit) {
		$this->param['limit'] = $limit;
		return $this;
	}
	
	/**
	 * Permet de faire une jointure en fluent
	 * 
	 * @return SQLFluent
	 */
	public function join($table, $conditions) {
		$this->param['join'][$table] = $conditions;
		return $this;
	}
	
	/**
	 * Execute la requete préparé
	 * 
	 * @param boolean $getAll
	 * @return stdClass 
	 */
	public function execute($getAll=true) {
		if ($getAll) {
			$result = $this->find($this->param);
		} else {
			$result = $this->findFirst($this->param);
		}
	return $result;
	}
}



class cwModel extends SQLFluent{
	/**
	 * Renvois le nombre de requete
	 * 
	 * @return int nombre de requete
	 */
	public function getNbQuery() {
		return self::$count;
		//return count(self::$sqlList);
	}
}