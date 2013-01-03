<?php
Class LogModel extends Model{

	/**
	 * 
	 * Enregistrer un log
	 * @param string $tag
	 * @param string $msg
	 * @param int $uid
	 * @param int $warningLevel
	 */
	public function setLog($tag, $msg, $uid = 0, $warningLevel = 0)
	{
		$data = new stdClass();
		// Importance du log defaut:0
		$data->level = (int) $warningLevel;
		// Identifiant du client defaut:0
		$data->uid = $uid;
		// tag identifiant
		$data->tag = $tag;
		// Message
		$data->msg = $msg;
		
		$this->save($data);
	}	
	
	
	/**
	 * 
	 * Retourne un array() comptenant le nombre de page et le resultat de la requete
	 * @param int $page
	 * @param int $item
	 * @param string $tag
	 */
	public function getLog($page = 1, $item = 30, $tag = 'index'/* pour la recherche */)
	{
		$go = ceil(($page-1) * $item);
		$st =  $go . ', ' . $item;
		$prepare = array(
			'order' => 'id DESC',
			'join' => array(__SQL . '_Member AS Member' => 'Member.idmember = Log.uid'),
			'limit' => $st,
			);
			
		if ($tag != 'index')
		{
			$prepare['conditions'] = array('tag' => $tag);
			$nbPage = ceil($this->findCount(array('tag' => $tag)) /  $item);
		}
		else
		{
			$nbPage = ceil($this->count() /  $item);
		}
			
		
		return array('page' => $nbPage, 'query' => $this->find($prepare));
	}
	
	
	
	
	/**
	 * 
	 * Retourne un array() comptenant le nombre de page et le resultat de la requete
	 * @param int $page
	 * @param int $item
	 * @param string $tag
	 */
	public function getUidLog($uid, $page = 1, $item = 30, $tag = 'index'/* pour la recherche */)
	{
		$go = ceil(($page-1) * $item);
		$st =  $go . ', ' . $item;
		$prepare = array(
			'order' => 'id DESC',
			'join' => array(__SQL . '_Member AS Member' => 'Member.idmember = Log.uid'),
			'limit' => $st,
			);
		$nbPage = 0;
		if (intval($uid))
		{
			$prepare['conditions'] = array('uid' => $uid);
			$nbPage = ceil($this->findCount(array('uid' => $uid)) /  $item);
		}
		else
		{
			$member = loadModel('Member');
			$hasMember = $member->searchMemberByLogin(clean($uid, 'slug'));
			
			if ($hasMember)
			{
				$prepare['conditions'] = array('uid' => $hasMember->idmember);
				$nbPage = ceil($this->findCount(array('uid' => $hasMember->idmember)) /  $item);
			}
			
			
		}
			
		

		return array('page' => $nbPage, 'query' => $this->find($prepare));
	}
	
	public function getTag()
	{
		return $this->find(
			array(
				'fields' => 'tag, COUNT( tag ) AS count',
				'group' => 'tag'
				)
			);
	}
	

}