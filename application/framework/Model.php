<?php
Class Model {
	
	static $connections = array(); 
	public $count=0;
	public $conf = 'default';
	public $table = false; 
	public $pdo; 
	public $primaryKey = 'id'; 
	public $id; 
	public $errors;
	public $form; 
	public $validate = array();
	public $sql;				//Contient la requête
	
	public function __construct()
	{
	$this->pdo = DB::getInstance();
		
		// Nom de la table
		if($this->table === false){
			$this->tableAs = preg_replace('#Model#', '', get_class($this));
			$this->table = __SQL.'_'.$this->tableAs; 
		}
		$this->errors = array();
		
	}


	/**
	* Permet de valider des données
	* @param $data données à valider 
	**/
	function validates($data, $thisvalidaterules=''){
		$errors = array(); 
		$thisValidateRules = empty($thisValidateRules) ? $this->validate : $thisValidateRules;
		foreach($thisValidateRules as $k=>$v){
				if(!isset($data->$k))
				{
					$errors[$k] = $v['message']; 
				}
				else
				{
					if($v['rule'] == 'noEmpty')
					{
						if(empty($data->$k))
						{
							$errors[$k] = $v['message']; 
						}
					}
					elseif($v['rule'] == 'notEmpty')
					{
						if(empty($data->$k))
						{
							$errors[$k] = $v['message']; 
						}
					}
					elseif($v['rule'] == 'isMail')
					{
						if (!filter_var($data->$k, FILTER_VALIDATE_EMAIL))
						{
						$errors[$k] = $v['message'];
						}
					}
					elseif(!preg_match('#'.$v['rule'].'#',$data->$k))
					{
						$errors[$k] = $v['message'];
					}
				}
				
				if(isSet($v['callback']))
				{
					if (!call_user_func($v['callback'], $data->$k))
					{
					$errors[$k] = $v['message'];
					}
				}
		}
		$this->errors = $errors; 

		if(empty($errors)){
			return true;
		}
		return false;
	}

	
	

	/**
	* Permet de récupérer plusieurs enregistrements
	* @param $req Tableau contenant les éléments de la requête
	**/
	public function find($req = array(), $fetch=PDO::FETCH_OBJ){
	try{	
		$sql = 'SELECT ';

			if(isset($req['fields'])){
				if(is_array($req['fields'])){
					$sql .= implode(', ',$$req['fields']);
				}else{
					$sql .= $req['fields']; 
				}
			}else{
				$sql.='*';
			}

			$sql .= ' FROM '.$this->table.' as '.$this->tableAs.' ';

			// Liaison
			if(isset($req['join'])){
				foreach($req['join'] as $k=>$v){
					$sql .= 'LEFT JOIN '.$k.' ON '.$v.' '; 
				}
			}

			// Construction de la condition
			if(isset($req['conditions'])){
				$sql .= 'WHERE ';
				if(!is_array($req['conditions'])){
					$sql .= $req['conditions']; 
				}else{
					$cond = array(); 
					
					foreach($req['conditions'] as $k=>$v)
					{
						if(!is_numeric($v)){
							if (is_array($v)){	debug($v);}
							$v = '"'.mysql_escape_string($v).'"'; 
						}
						
						$cond[] = "$k=$v";
					}
					$sql .= implode(' AND ',$cond);
				}

			}

			if(isset($req['group'])){
				$sql .= ' GROUP BY '.$req['group'];
			}
			
			if(isset($req['order'])){
				$sql .= ' ORDER BY '.$req['order'];
			}

			if(isset($req['limit'])){
				$sql .= ' LIMIT '.$req['limit'];
			}
			$this->sql = $sql;
			$pre = $this->pdo->prepare($sql); 
			$this->count++;
		
			$pre->execute(); 
		}
	catch (PDOException $e)
		{
			if($e->getCode() == '42S02')
			{
				if (method_exists($this, 'install'))
				{
				$this->install();
				}
			}
		}
		return $pre->fetchAll($fetch);
	}

	/**
	* Alias permettant de retrouver le premier enregistrement
	**/
	public function findFirst($req){
		return current($this->find($req)); 
	}

	/**
	* Récupère le nombre d'enregistrement
	**/
	public function findCount($conditions){
		$res = $this->findFirst(array(
			'fields' => 'COUNT('.$this->primaryKey.') as count',
			'conditions' => $conditions
			));
		
		return $res->count;  
	}

	/**
	* Permet de récupérer un tableau indexé par primaryKey et avec name pour valeur
	**/
	function findList($req = array()){
		if(!isset($req['fields'])){
			$req['fields'] = $this->primaryKey.',name';
		}
		$d = $this->find($req); 
		$r = array(); 
		foreach($d as $k=>$v){
			$r[current($v)] = next($v); 
		}
		return $r; 
	}

	/**
	* Permet de supprimer un enregistrement
	* @param $id ID de l'enregistrement à supprimer
	**/	
	public function delete($id){
		$sql = "DELETE FROM `{$this->table}` WHERE `{$this->table}`.`{$this->primaryKey}` = $id";
		$this->sql = $sql;
		$this->count++;
		$this->pdo->query($sql); 
	}


	/**
	* Permet de sauvegarder des données
	* @param $data Données à enregistrer
	**/
	public function save($data){
	$this->install();
		$key = $this->primaryKey;
		$fields =  array();
		$d = array(); 
		foreach($data as $k=>$v)
		{
			if($k!=$this->primaryKey){
				// Incrementation par UPDATE formule($data->key = 'key+1';)
				if (preg_match('#^'.$k.'+\+[0-9]+#',$v))
				{
				$fields[] = "$k=$v";
				}
				else
				{
				$fields[] = "$k=:$k";
				$d[":$k"] = $v; 
				}
			}elseif(!empty($v)){
				$d[":$k"] = $v; 
			}
		}
		if(isset($data->$key) && !empty($data->$key)){
			$sql = 'UPDATE '.$this->table.' SET '.implode(',',$fields).' WHERE '.$key.'=:'.$key;
			$this->id = $data->$key; 
			$action = 'update';
		} else{
			$sql = 'INSERT INTO '.$this->table.' SET '.implode(',',$fields);
			$action = 'insert'; 
		}
		$this->sql = $sql;
		
		$pre = $this->pdo->prepare($sql);
		//debug($sql);
		$this->count++;
		$bool = $pre->execute($d);
		if($action == 'insert'){
			$this->id = $this->pdo->lastInsertId(); 
		}
		
		return ($bool);
	}
	
	/*
	* Pour les accros de PDO
	*/
	public function query($sql){
		$this->count++;
		$this->sql = $sql;

		$pre = $this->pdo->prepare($sql); 
		//debug($sql);
		return $pre->execute(); 
	}
	
	
	/*
	* Construit l'arbre des table
	* Avec un objetct ou un array
	*/
	public function debug($query){
	// Cast pour forcé le type a tableau
	$req = (array) $query;

	$countLigne = 0;
		if (isSet($req[0]))
		{
		$countLigne = count($req);
		$query = (array) $req[0];
		$listCle = array_keys($query);
		$nbDeCle = count($listCle);	
		}
		else
		{
		$query = (array) $req;
		$listCle = array_keys($query);
		$nbDeCle = count($listCle);
		}
	
	/***************************************
	*	Le nom des colonnes
	***************************************/
	
	
	$html = '<script>
	$(function() {
		$("table#'.$this->table.'").tablesorter({ sortList: [[1,0]] });
	});
</script>
<div style="overflow: auto;
width: 940px;height:250px;">
<table class="zebra-striped" id="'.$this->table.'" >
	<thead><tr>';
		for($c=0; $c<$nbDeCle; $c++)
		{
		$html.='<th>'.$listCle[$c].'</th>';
		}
	$html.='</tr></thead>';
	
	/***************************************
	*	Le contenu des colonnes
	***************************************/
	$html .= '<tbody>';

		if ($countLigne==0)
		{
		$html .= '<tr>';
			foreach($query AS $k=>$v)
			{
			$html.='<th>'.$v.'</th>';
			}
		$html.='</tr>';
		}
		else
		{
			for($i=0; $i<$countLigne; $i++)
			{
				$html .= '<tr>';
					foreach($req[$i] AS $v)
					{
					$html.='<td>'.$v.'</td>';
					}
				$html.='</tr>';
			}
		}
		
		
	$html.='</tbody>';
	
	return $html.'</table></div>';
	}

}
