<?php
Class MessengerModel extends Model {

	public function install()
	{

		$this->query("CREATE TABLE IF NOT EXISTS `".__SQL."_Messenger` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `uid` int(11) NOT NULL,
		  `rid` int(11) NOT NULL,
		  `title` varchar(256) NOT NULL,
		  `ctime` int(11) NOT NULL DEFAULT '0',
		  `ntime` int(11) NOT NULL DEFAULT '0',
		  `npost` int(11) NOT NULL DEFAULT '1',
		  `hasdel` char(1) NOT NULL DEFAULT 'o',
		  `important` int(1) NOT NULL DEFAULT '1',
		  `lastpost` int(11) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

		$this->query("CREATE TABLE IF NOT EXISTS `".__SQL."_MessengerCourrier` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `pid` int(11) NOT NULL,
			  `owner` int(11) NOT NULL,
			  `hasread` enum('n','y') NOT NULL DEFAULT 'n',
			  `hasdel` enum('n','y') NOT NULL DEFAULT 'n',
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

	$this->query("CREATE TABLE IF NOT EXISTS `".__SQL."_MessengerPost` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `pid` int(11) NOT NULL,
		  `oid` int(11) NOT NULL,
		  `time` int(11) NOT NULL,
		  `message` text NOT NULL,
		  `ip` varchar(256) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
		
	}
	
	public function countDiscution($owner)
	{
		return $this->findFirst (
			array (
				'fields' => 'COUNT( id ) as count',
				'conditions' => 'Messenger.uid	= ' . $owner . ' OR Messenger.rid = ' . $owner,
				)
			);
	}
	
	public function getList($uid)
	{
		$prepare = array(
			'join' => array(
				__SQL . '_MessengerPost AS Post' => 'Post.id = Messenger.lastpost',

				__SQL . '_Member AS Member' => 'Member.idmember = Messenger.uid',
				__SQL . '_MemberInfo AS MemberInfo' => 'Messenger.uid = MemberInfo.thismember'
				),
			'order' => 'Messenger.ntime DESC',
			'conditions' => 'Messenger.uid = ' . $uid . ' OR Messenger.rid = ' . $uid
			/*'conditions' => array(uid	rid
				__SQL . '_MessengerPost AS Post' => 'Post.pid = Messenger.id',
				),//*/
			);
		return $this->find($prepare);
	}
	
	
	/**
	 *  
	 * Retourne la discution pid, de l'user owner
	 * @param int $pid
	 * @param int $owner
	 */
	public function getPid($pid, $owner, $page)
	{
		$pid = (int) $pid;
		$owner = (int) $owner;
		
		$prepare = array(
			'join' => array(
				__SQL . '_MessengerPost AS MessengerPost' => 'MessengerPost.pid = Messenger.id',
				__SQL . '_Member AS Member' => 'Member.idmember = MessengerPost.oid',
				__SQL . '_MemberInfo AS MemberInfo' => 'Member.idmember = MemberInfo.thismember',
				),
				
			'conditions' => 'Messenger.id = ' . $pid . ' AND (Messenger.uid	= ' . $owner . ' OR Messenger.rid = ' . $owner,
			'order' => 'MessengerPost.time ASC',
			'limit' => ($page * 5) . ', 5'
			);
		return $this->find($prepare);
	}
	
	
	/**
	 * 
	 * 
	 * @param int $pid
	 * @param int $uid
	 * @param stdclass $data
	 */
	public function mailsend($uid, $rid, $data, $important = 1) {
		$this->table = __SQL . '_Messenger';
		$tosend = new stdClass ();
		$tosend->uid = ( int ) $uid; // UserID
		$tosend->rid = ( int ) $rid; // ResponUserID
		$tosend->title = $data->title; // Titre de la discution
		$tosend->ctime = time(); // Date creation
		$tosend->ntime = time(); // Date modif
		$tosend->npost = 0; // Nb de post
		$tosend->hasdel = 'n'; // Init hasDel a 0

		$tosend->important = ( int ) $important;
		
		if ($this->save ( $tosend )) { // On sauve le titre de la discution
			
			if ( $this->mailrespon($this->getLastId(), $uid, $data) ) { // On sauve le message
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	
	}
	
	
	/**
	 * 
	 * Envoie d'une reponse
	 * @param int $pid
	 * @param int $uid
	 * @param stdclass $data
	 */
	public function mailrespon($pid, $uid, $data)
	{
		$this->table = __SQL . '_MessengerPost'; // On change le nom de la table
		
		$post = new stdClass();
		$post->pid = $pid; // post id
		$post->oid = ( int ) $uid; // ownrID donc le posteur
		$post->time = time(); // La date
		$post->message = $data->message; // Le message 
		$post->ip = Securite::ipX(); // L'ip
	
		if ($this->save ( $post )) {
			$this->table = __SQL . '_Messenger'; // Réinitialise, pour éviter les problemes de porté
			
			$update = new stdClass();
			$update->lastpost = $this->getLastId(); // Le dernier message a l'ID
			$update->npost = 'npost+1';
			$update->id = $post->pid;  // On indique l'iddu titre de la discution
			$update->ntime = time(); // Date modif
			
			
			if ($this->save ( $update )) {
				return true;
			} else {
				return false;
			}
			
		} else {
			$this->table = __SQL . '_Messenger'; // Réinitialise, pour éviter les problemes de porté
			return false;
		}
	}
	
	
	public function search($uid, $query)
	{
		$prepare = array(
			'join' => array(
				__SQL . '_MessengerPost AS MessengerPost' => 'MessengerPost.pid = Messenger.id',
				__SQL . '_Member AS Member' => 'Member.idmember = Messenger.uid',
				__SQL . '_MemberInfo AS MemberInfo' => 'Messenger.uid = MemberInfo.thismember'
				),
			'order' => 'Messenger.ntime DESC',
			'conditions' => '( Messenger.title LIKE "%'.$query.'%" OR MessengerPost.message LIKE "%' . $query . '%" OR Member.loginmember LIKE "%' . $query . '%" )
							AND (Messenger.uid = ' . $uid . ' OR Messenger.rid = ' . $uid . ')',
			'group' => 'MessengerPost.pid'
			);
		return $this->find($prepare);
	}

}