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

abstract class Model extends PDO{
	
	
	
	//static $connections = array ();
	public static $count = 0;
	public $table = false;

	protected $pdo;
	public static $primaryConnection;
	
	public $lastError = false;
	public $primaryKey = 'id';
	public $id;
	public $errors;
	public $sql; // Contient la requête
	public static $sqlList = array();
	
	public function getAllRequest()
	{
		return self::$sqlList;
	}
	
	public function __construct() {
		if (!isset(self::$primaryConnection)) {
		$this->connect();
		}
		else
		{
		$this->pdo = self::$primaryConnection;
		}
		
		// Nom de la table
		if ($this->table === false) {
			$this->tableAs = preg_replace ( '#Model#', '', get_class ( $this ) );
			$this->setTable($this->tableAs);
		}
		$this->errors = array ();
	}
	
	public function setTable($table)
	{
		$this->table = __SQL . '_' . $table;
	}
	
	public function getTable()
	{
		return $this->table;
	}
	public function connect($db_driver=NULL, $db_hostname=NULL, $db_username=NULL, $db_password=NULL, $db_database=NULL, $db_prefix = null)
	{
	
		/***************************************
		*	Si nous n'avons aucun paramettres,
		*	On utilise ceux de base
		***************************************/
		if (empty($db_driver))
		{
		$db_driver=DB_DRIVER;
		$db_hostname=DB_HOSTNAME;
		$db_username=DB_USERNAME;
		$db_password=DB_PASSWORD;
		$db_database=DB_DATABASE;
		}
		else
		{
		$this->table = $this->tableAs;
		}
	
		
		switch (strtolower($db_driver)){
			case 'mysql':
				$DB_connect='mysql:host='.$db_hostname.';port=3306;dbname='.$db_database;
			break;
			case 'pgsql':
				$DB_connect='pgsql:host='.$db_hostname.' port=4444 dbname='.$db_database;
			break;
			case 'sqlite':
				$DB_connect='sqlite:'.$db_hostname;
			break;	
			case 'oci':
				$DB_connect='OCI:'.$db_hostname;
			break;
		}
		
		try {
			$pdoConnect = new PDO($DB_connect, $db_username, $db_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
			$pdoConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			if (!isset(self::$primaryConnection)) {
			self::$primaryConnection = $pdoConnect;
			$this->pdo = $pdoConnect;
			}
			else
			{
			$this->pdo = $pdoConnect;
			}
			
		} catch(PDOException $e){
		
			switch ($e->getCode())
			{
				case '1040':
					$page = new Page();
					$page->setLayout('alert');
					$contenu = 'D&eacute;sol&eacute;, nous sommes victime de notre succ&egrave;s. Trop de connexion sont d&eacute;j&agrave; ouverte. R&eacute;&eacute;say&eacute; plus tard.';
					require_once __APP_PATH . DS . 'layout' . DS . $page->getLayout() . '.phtml';
					die;
				break;
				case '1044':
					$page = new Page();
					$page->setLayout('alert');
					$contenu = 'Impossible de se connecter &agrave; la base de donn&eacute;e';
					if (DB_DATABASE == 'password' || DB_DATABASE == 'databasename' || DB_USERNAME == 'username')
					{
						$contenu .= '<br><small>Il doit y avoir un fichier init.php dans ' . __SITE_PATH . DS . 'includes, que vous n\'avez pas modifier</small>';
					}
					require_once __APP_PATH . DS . 'layout' . DS . $page->getLayout() . '.phtml';
					die;
				break;
				case '1045':
					$page = new Page();
					$page->setLayout('alert');
					$contenu = 'Impossible de se connecter &agrave; la base de donn&eacute;e';
					if (DB_DATABASE == 'password' || DB_DATABASE == 'databasename' || DB_USERNAME == 'username')
					{
						$contenu .= '<br><small>Il doit y avoir un fichier init.php dans ' . __SITE_PATH . DS . 'includes, que vous n\'avez pas modifier</small>';
					}
					require_once __APP_PATH . DS . 'layout' . DS . $page->getLayout() . '.phtml';
					die;
				break;
				case '2A000':
					if (__DEV_MODE)
					{
						throw new Exception("Syntax Error: " . $e->getMessage(), $e->getCode());
					}
				break;
				default:
					throw new Exception ($e->getMessage() .' ' . get_class ( $this ), $e->getCode()); 
				break;
			}
		}
	}
	
	
	
	/**
	*	Charge le model pour y effectuer des actions
	*/
	public function loadModel($name)
	{		
	$name = $name.'Model';
	// L'endroit ou le model est chargé
	$file = __APP_PATH . DS . 'model' . DS . $name . '.php';
		if (file_exists($file))
		{
		require_once $file;
			if (!isSet($this->$name))
			{
			return new $name();
			}
		}
		else
		{
		throw new Exception ('File model not found for ' . $name);
		}
	}
	
	/**
	 * Permet de valider des données
	 * 
	 * @param $data données
	 *       	 à valider
	 *       	
	 */
	function validates($data, $validaterules = 'validate')
	{
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
					
					if (isSet($explode [1]))
					{
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
	 * @param $req Tableau
	 *       	 contenant les éléments de la requête
	 *       	
	 */
	public function find($req = array(), $fetch = PDO::FETCH_OBJ)
	{
		try {
			$sql = 'SELECT ';
			
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
							$v = '"' . mysql_escape_string ( $v ) . '"';
							$cond[] = '`'.$k.'` LIKE ' .$v;
						}
						else
						{
							$v = (int) $v;
							$cond[] = '`'.$k.'`=' .$v;
						}
						
					}
					$sql .= implode ( ' AND ', $cond );
				}
			}
			/* Supprimer pour un probleme de securite, injection SQL
				 elseif (isset ( $req ['like'] )) {
				$sql .= 'WHERE ';
				if (! is_array ( $req ['like'] )) {
					$sql .= $req ['like'];
				} else {
					$cond = array ();
					
					foreach ( $req ['like'] as $k => $v ) {
						if (! is_numeric ( $v )) {
							if (is_array ( $v )) {
								debug ( $v );
							}
							$v = '"' . mysql_escape_string ( $v ) . '"';
						}
						
						$cond [] = "$k LIKE $v";
					}
					$sql .= implode ( ' AND ', $cond );
				}
			} */
			
			if (isset ( $req ['group'] )) {
				$sql .= ' GROUP BY ' . $req ['group'];
			}
			
			if (isset ( $req ['order'] )) {
				$sql .= ' ORDER BY ' . $req ['order'];
			}
			
			if (isset ( $req ['limit'] )) {
				$sql .= ' LIMIT ' . $req ['limit'];
			}
			self::$sqlList[] = $this->sql = $sql;
			$pre = $this->pdo->prepare ( $sql );
			self::$count++; 
//			debug($this->sql);
			$pre->execute();
		} catch ( Exception $e ) {
			if ($e->getCode () == '42S02') {
				if (method_exists ( $this, 'install' )) {
					$this->install();
				}
			}
		}
//		debug($this->sql);
		return $pre->fetchAll ( $fetch );
	}
	
	/**
	 * Alias permettant de retrouver le premier enregistrement
	 */
	public function findFirst($req = array())
	{
		return current ( $this->find ( $req ) );
	}
	
	/**
	 * Récupère le nombre d'enregistrement
	 */
	public function findCount($conditions)
	{
		$res = $this->findFirst ( array ('fields' => 'COUNT(' . $this->primaryKey . ') as count', 'conditions' => $conditions ) );
		
		return isset($res->count) ? $res->count : 0;
	}
	
	public function count($conditions = false)
	{
		if ( !$conditions )
		{
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
	function findList($req = array())
	{
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
	 * @param $id ID
	 *       	 de l'enregistrement à supprimer
	 *       	
	 */
	public function delete($id)
	{
		// Comme on ne eu pas faire DELETE cle textuelle, on ajout des "
		$id = is_int($id) ? $id : '"'.$id.'"';
		$sql = "DELETE FROM `{$this->table}` WHERE `{$this->table}`.`{$this->primaryKey}` = $id";
		self::$sqlList[] = $this->sql = $sql;
		self::$count++;
		return $this->pdo->query ( $sql );
	}
	
	/**
	 * Permet de sauvegarder des données
	 * 
	 * @param $data Données
	 *       	 à enregistrer
	 *       	
	 */
	public function save($data)
	{
		if (!is_object($data))
		{
			throw new ErrorException('Model: $data n\'est pas un objet', 107, 0, __FILE__, __LINE__);
		}
		
		try {
			$key = $this->primaryKey;
			$fields = array ();
			$d = array ();
			foreach ( $data as $k => $v ) {
				if ($k != $this->primaryKey) {
					// Incrementation par UPDATE formule($data->key = 'key+1';)
					if (preg_match ( '#^' . $k . '+\+[0-9]+#', $v )) {
						$fields [] = "$k=$v";
					} else {
						$fields [] = "$k=:$k";
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
			self::$sqlList[] = $this->sql = $sql;
			
			$pre = $this->pdo->prepare ( $sql );
			// debug($sql);
			self::$count++;
			$bool = $pre->execute ( $d );
			if ($action == 'insert') {
				$this->setLastInsertId();
			}
		
		} catch ( Exception $e ) {
			$this->lastError = $e->getMessage() . ' for ' . get_class ( $this ) . ' in ' . $this->table;
			
			if ($e->getCode() == '42S02') {
				
				if (method_exists ( $this, 'install' )) {
					$this->install ();
					return $this->save ( $data );
				}
			}
		}
		return isSet($bool) ? $bool : false;
	}
	
    /*
     * Pour les accros de PDO
     */
    public function query($sql, $fetch = false)
    {
    	try {
	        self::$count++;
	        self::$sqlList[] = $this->sql = $sql;
	        
	        $pre = $this->pdo->prepare ( $sql );
	        // debug($sql);
	        if ($fetch)
	        {
	            $pre->execute();
	            return $pre->fetchAll ( PDO::FETCH_OBJ );
	        } else { return $pre->execute(); }
    	} catch(PDOException $e) {
    		Log::setLog('Query call: ' . $e->getMessage(), get_class($this));
    	}
    }
	
	
	/**
	 * 
	 * Alias pour getLastInsertId()
	 * Retourne le dernier ID apres un insert
	 * @deprecated
	 */
	public function getLastId()
	{
		return $this->id;
	}
	
	/**
	 * 
	 * Retourne le dernier ID apres un insert
	 */
	public function getLastInsertId()
	{
		return $this->id;
	}
	
	/**
	 * 
	 * Enregistre le dernier ID apres un insert
	 */
	public function setLastInsertId()
	{
		$this->id = $this->pdo->lastInsertId();
	}
	
	/*
	 * Construit l'arbre des table Avec un objetct ou un array
	 */
	public function debug($query = NULL)
	{
		
		if (is_null($query))
		{
			debug(self::$sqlList);
			debug($this->sql);
			debug($this->lastError);
			return false;
		}
		
		// Cast pour forcé le type a tableau
		$req = ( array ) $query;
		
		$countLigne = 0;
		if (isSet ( $req [0] )) {
			$countLigne = count ( $req );
			$query = ( array ) $req [0];
			$listCle = array_keys ( $query );
			$nbDeCle = count ( $listCle );
		} else {
			$query = ( array ) $req;
			$listCle = array_keys ( $query );
			$nbDeCle = count ( $listCle );
		}
		
		/**
		 * *************************************
		 * Le nom des colonnes
		 * *************************************
		 */
		
		$html = '<script>
	$(function() {
		$("table#' . $this->table . '").tablesorter({ sortList: [[1,0]] });
	});
</script>
<div style="overflow: auto;
width: 940px;height:250px;">
<table class="zebra-striped" id="' . $this->table . '" >
	<thead><tr>';
		for($c = 0; $c < $nbDeCle; $c ++) {
			$html .= '<th>' . $listCle [$c] . '</th>';
		}
		$html .= '</tr></thead>';
		
		/**
		 * *************************************
		 * Le contenu des colonnes
		 * *************************************
		 */
		$html .= '<tbody>';
		
		if ($countLigne == 0) {
			$html .= '<tr>';
			foreach ( $query as $k => $v ) {
				$html .= '<th>' . $v . '</th>';
			}
			$html .= '</tr>';
		} else {
			for($i = 0; $i < $countLigne; $i ++) {
				$html .= '<tr>';
				foreach ( $req [$i] as $v ) {
					$html .= '<td>' . $v . '</td>';
				}
				$html .= '</tr>';
			}
		}
		
		$html .= '</tbody>';
		
		return $html . '</table></div>';
	}

}


class cwModel extends Model{
	function getNbQuery()
	{
		return self::$count;
	}
}
