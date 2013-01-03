<?php
/**
 * 
 * @author devphp
 * 
 * Basé sur le travail de Baptiste "Talus" Clavié <talusch@gmail.com> http://www.talus-works.net/wall.html?type=php&file=includes/class/ri.php
 */
Class TreeHelperModel extends Model {
	
	private $_cache = array();
	
	
	public function getTree($upd = false) {
		
		
		// -- Pas de cache ? On le regénère...
		if (! isset ( $this->_cache [$this->table] ['tree'] ) || $upd) {
			
			$prepare = array ('order' => 'lft ASC' );
			
			$tree = $this->find( $prepare );
			
			$this->_cache [$this->table] ['tree'] = array ();
			
			foreach ( $tree as $noeud ) {
				$this->_cache [$this->table] ['tree'] [$noeud->id] = $noeud;
			}
		}

		return $this->_cache [$this->table] ['tree'];
	}
	
	

	
	
	/**
	 * Récupère la filière descendante d'un noeud.
	 *
	 * @param integer $id ID de la feuille
	 * @param integer $level si l'on souhaite un niveau uniquement
	 * @param $order champ et direction pour order
	 * @return array
	 * @static
	 */
	public function getChildren($idParent, $level = false, $order = false){
		
		if (!is_numeric($idParent))
		{
			throw new Exception('$idParent is not numeric ('.gettype($idParent).')');
		}
		$idParent = (int) $idParent;
		$parent = $this->findFirst(array('conditions' => array('id' => $idParent)));

		if (!$parent)
		{
			throw new Exception('Parent not found in getChildren()');
		}
		
		if (is_numeric($level))
		{
			$level = (int) $level;
			$prepare = array(
			'conditions' => '`lft` > '.$parent->lft.' AND `rght` < '.$parent->rght . ' AND `level` = ' . $level
			);
		}
		else
		{
			$prepare = array(
				'conditions' => '`lft` > '.$parent->lft.' AND `rght` < '.$parent->rght
				);
		}
		
		if ($order)
		{
			$prepare['order'] = $order;
		}
		
		
		return array(
			'parent' => $parent,
			'child' => $this->find($prepare)
			);
	}
	
	
	public function getParent($idChild){
		
		if (!is_numeric($idChild))
		{
			throw new Exception('$idParent is not numeric ('.gettype($idChild).')');
		}
		
		$child = $this->findFirst(array('conditions' => array('id' => $idChild)));
		
		if (!$child)
		{
			throw new Exception('Parent not found in getParent()');
		}

		
		$prepare = array(
			'conditions' => 'lft < ' . $child->lft . ' AND rght > ' . $child->rght,
			'order' => 'level ASC'
			);
		return array(
			'child' => $child,
			'parent' => $this->find($prepare)
			);
	}
	
	
	/**
	 * Ajoute une feuille dans l'arbre.
	 *
	 * @param array $data Données de la feuille à insérer
	 * @param integer parent ID du parent de la feuille à insérer.
	 * @return bool
	 */
	public function add($data, $parent = 0){

		if (!is_array($data)) {
			throw new Exception('$data is not array ('.gettype($data).')');
		}

		/*
		 * Si ce n'est pas une nouvelle catégorie, on selectionne la borne droite
		* et le level du forum parent : sinon, on sélectionne la borne la plus à
		* droite, plus un, pour insérer une nouvelle racine.
		*/
		if ($parent != 0) {
			$prepare = array(
				'conditions' => array(
					'id' => $parent
					)
				);
			$parentDump = $this->findFirst($prepare);
			
			if (!$parentDump)
			{
				return false;
			}
			
			$parent_data = new stdClass();
			$parent_data->rght = $parentDump->rght;
			$parent_data->level = $parentDump->level;
			
		} else {
	
			// Récupération des données
			$prepare = array(
					'fields' => '(COALESCE(MAX(rght), 0) + 1) as rght, -1 as level'
					);
			$parent_data = $this->findFirst($prepare);
			$res = null;
		}

		
		// Décalage des bornes droite & gauche
		$sql = 'UPDATE ' . $this->table . ' SET rght = rght + 2 WHERE rght >= ' . $parent_data->rght . ';';
		$this->query($sql);
	
		$sql = 'UPDATE ' . $this->table . ' SET lft = lft + 2 WHERE lft >= ' . $parent_data->rght . ';';
		$this->query($sql);

		
		// On insere les données naturel de la feuille
		// On regroup les valeurs de $data (pour la portabilité)
		// Mais avant, on supprime les eventuel champs qui fausserai les intervals
		unset($data['lft']);
		unset($data['rght']);
		unset($data['level']);
		unset($data['parent']);
		
		$data = array_merge($data, array(
		'lft' => $parent_data->rght,
		'rght' => $parent_data->rght + 1,
		'level' => $parent_data->level + 1,
		'parent' => $parent
		));
	
		// Récupération des clé
		$fields = implode(', ', array_keys($data));
		$values = array();
			foreach (array_keys($data) as $field)
			{
				// On inject dans $values le champs a transformer
				$values[] = ":{$field}";
			}
			
		// On impluse le tableau avec une virgule
		$values = implode(', ', $values);
		
		// On crée la requete
		$sql = 'INSERT INTO ' . $this->table . ' (' . $fields . ') VALUES (' . $values . ');';
		
		// On prepare la requete...
		// C'est presque fini...
		$res = $this->pdo->prepare($sql);
		
			foreach ($data as $field => $value)
			{
				$res->bindValue(":{$field}", $value);
			}
	
		// On execute la requete preparer
		$res->execute();
		$this->setLastInsertId();
		return $parent;
	}
	
	
	/**
	 * Supprime un élément de l'arbre et l'ensemble de c'est enfants
	 *
	 * @param integer $id ID de l'élément.
	 * @return bool
	 */
	public function remove($id){
		
		if (!is_numeric($id)) {
			throw new Exception('$id is not numeric ('.gettype($id).')');
		}
		
		$prepare = array('conditions' => array('id' => $id));
		$childToRemove = $this->findFirst($prepare);
		
		if (!$childToRemove)
		{
			throw new Exception('Child not found in remove()');
		}
	
		// On calcul l'importance de la suppréssion
		// Si c'est une feuille on obtiens 2
		// si c'est un noeud on prend le bord droit on supprime le bord gauche et on rajoute 1
		$diff = ($childToRemove->rght - $childToRemove->lft) + 1;

		$this->query('DELETE FROM '.$this->table.' WHERE lft = ' . $childToRemove->lft);
		$this->query('UPDATE '.$this->table.' SET    lft = lft - '.$diff.' WHERE lft >= ' . $childToRemove->lft);
		$this->query('UPDATE '.$this->table.' SET    rght = rght - '.$diff.' WHERE rght >= ' . $childToRemove->rght);
		return $this;
	}
	
	
	public function move($id, $toParentId)
	{
		// Recherche le champ a déplacé
		$prepare = array('conditions' => array('id' => $id));
		$riToMove = $this->findFirst($prepare);
		
		if (!$riToMove)
		{
			throw new Exception('$id to move not found in move()');
		}

		// Recherche le champ vers lequel on déplace
		$prepare = array('conditions' => array('id' => $toParentId));
		$riToDest = $this->findFirst($prepare);
		
		if (!$riToDest)
		{
			throw new Exception('$id destination not found in move()');
		}
		

		
		$this->remove($riToMove->id);
		$objToArray = get_object_vars($riToMove);
		unset($objToArray['id']);
		unset($objToArray['lft']);
		unset($objToArray['rght']);
		unset($objToArray['parent']);
		$this->add($objToArray, $toParentId);
		$this->query('UPDATE `'.$this->table.'` SET `id` = '.$riToMove->id.' WHERE id = ' . $this->getLastId());
		
		return $this;
	}

}
?>