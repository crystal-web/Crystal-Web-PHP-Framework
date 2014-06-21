<?php
Class ForumModel extends Model {
/**
 * Error:
 * 	400: Ne trouve pas de catégorie
 *  401: Topic introuvable
 *  402: Sujet introuvable
 *  301: Topic verrouillé
 *  302: Post introuvable
 */

    public function install() {

        // Liste des catégories
        $this->query("CREATE TABLE IF NOT EXISTS `" . __SQL . "_ForumCat` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `order` int(11) NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");

        // Liste des sujet d'une catégorie
        $this->query("CREATE TABLE IF NOT EXISTS `" . __SQL . "_ForumSujet` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `cat_id` int(11) NOT NULL,
            `icone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `order` int(11) NOT NULL,
            `last_post_id` int(11) NOT NULL DEFAULT '0',
            `nb_topic` int(11) NOT NULL DEFAULT '0',
            `nb_post` int(11) NOT NULL DEFAULT '0',
            `auth_view` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
            `auth_post` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '2',
            `auth_topic` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '2',
            `groupid` int(3) NOT NULL,
            PRIMARY KEY (`id`),
            KEY `cat_id` (`cat_id`),
            KEY `last_post_id` (`last_post_id`),
            KEY `cat_id_2` (`cat_id`),
            KEY `last_post_id_2` (`last_post_id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");


        // Liste des toipics
        $this->query("CREATE TABLE IF NOT EXISTS `" . __SQL . "_ForumTopic` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `sujet_id` int(11) NOT NULL,
            `titre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `sous_titre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `auteur` int(11) NOT NULL,
            `hits` int(11) NOT NULL DEFAULT '0',
            `created_time` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `update_time` int(11) NOT NULL,
            `is_annonce` enum('n','y') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'n',
            `first_post_id` int(11) NOT NULL,
            `last_post_id` int(11) NOT NULL,
            `locked` enum('n','y') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'n',
            `solved` enum('n','y') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'n',
            `icone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `nb_post` int(11) NOT NULL DEFAULT '1',
            `haslook` enum('n','y') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'n' COMMENT 'to del',
            PRIMARY KEY (`id`),
            KEY `sujet_id` (`sujet_id`),
            KEY `first_post_id` (`first_post_id`),
            KEY `last_post_id` (`last_post_id`),
            KEY `auteur` (`auteur`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");

        // Premiere message et Réponses
        $this->query("CREATE TABLE IF NOT EXISTS `" . __SQL . "_ForumPost` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `topic_id` int(11) NOT NULL,
            `auteur` int(11) NOT NULL,
            `created_time` int(11) NOT NULL,
            `edited_time` int(11) NOT NULL DEFAULT '0',
            `message` text COLLATE utf8_unicode_ci NOT NULL,
            `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `goodhelp` enum('n','y') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'n',
            PRIMARY KEY (`id`),
            KEY `topic_id` (`topic_id`),
            KEY `auteur` (`auteur`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");

        // Liste des répondeurs
        $this->query("CREATE TABLE IF NOT EXISTS `" . __SQL . "_ForumRepondeur` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `title` varchar(256) NOT NULL,
            `message` text NOT NULL,
            `lockpost` enum('0','1') NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");

        // Listes des derniers poste actif et le groupe autorisé a voir
        $this->query("CREATE TABLE IF NOT EXISTS `" . __SQL . "_ForumFlux` (
            `loginmember` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `titre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `first_post_id` int(11) unsigned NOT NULL,
            `last_post_id` int(11) unsigned NOT NULL,
            `update_time` int(11) unsigned NOT NULL,
            `topic_id` int(11) unsigned NOT NULL,
            `nb_topic` int(11) unsigned DEFAULT NULL,
            `solved` char(1) COLLATE utf8_unicode_ci NOT NULL,
            `auth_view` varchar(255) COLLATE utf8_unicode_ci NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

        // Vue, pas vue via la DB
        $this->query("CREATE TABLE IF NOT EXISTS `" . __SQL . "_ForumView` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `hasposter` enum('n','y') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'n',
            `sujet_id` int(11) NOT NULL,
            `topic_id` int(11) NOT NULL,
            `post_id` int(11) NOT NULL,
            `member_id` int(11) NOT NULL,
            PRIMARY KEY (`id`),
            KEY `sujet_id` (`sujet_id`),
            KEY `topic_id` (`topic_id`),
            KEY `post_id` (`post_id`),
            KEY `member_id` (`member_id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");
    }
 
 	private $userGroup = array();
	

	
	/**
	 * Definit les groupes auquels appartient le client
	 * @param array(string) $userGroup
	 */
 	public function setUserGroup(array $userGroup) {
 		$this->userGroup = $userGroup;
	}
	
	/**
	 * Récupère les groupes auquels appartient le client
	 */
	public function getUserGroup() {
		if (!count($this->userGroup)) {
			throw new Exception("Set group before", 1);
		}
		return $this->userGroup;
	}


    /**
     * Creation d'un nouveau topic
     *
     * @param int $sujetId
     * @param string $title
     * @param string $subtitle
     * @param string $message
     * @param int $userId
     * @return int $topicId
     */
    public function nouveau($sujetId, $title, $subtitle, $message, $userId = 0) {
        $sujet = $this->getSujetById($sujetId);
        if (!$sujet) { throw new Exception("Sujet introuvable", 402); }

        // Le post
        $this->setTable('ForumPost');
        $data = new stdClass();
        $data->message = $message;
        $data->auteur = $userId;
        $data->created_time = time();
        $data->edited_time = time();
        $data->ip = Securite::ipX();

        if ($this->save($data)) {
            $lastPostId = $this->getLastInsertId();

            // Le topic
            $topic = new stdClass();
            $topic->sujet_id = $sujetId;
            $topic->titre = $title;
            $topic->sous_titre = $subtitle;
            $topic->auteur = $userId;
            $topic->nb_post = 1;

            // Bug de chainage php 5.3
            // time de creation
            $topic->update_time		= time();
            $topic->created_time	= $topic->update_time;

            // Last post id
            $sujet->last_post_id	= $lastPostId;
            $topic->first_post_id	= $lastPostId;
            $topic->last_post_id	= $lastPostId;
            $this->setTable('ForumTopic');

            if ($this->save($topic)) {
                $this->setTable('ForumPost');
                $post = new stdClass();
                $post->id = $lastPostId;
                $post->topic_id = $this->getLastInsertId();
                $this->save($post);


                $sujet->nb_post		= $this->getNbPostBySujetId($sujetId);
                $sujet->nb_topic	= $this->countTopicBySujetId($sujetId);

                $this->setTable('ForumSujet');
                $this->save($sujet);
                $this->query('CALL refresh_forumflux();');
                return $post->topic_id;
            }
        }

    }


    public function repondre($topicId, $message, $userId = 0, $locktopic = false) {
        $topic = $this->getTopicById($topicId);
        if (!$topic) { throw new Exception("Topic introuvable", 401); }
        if ($topic->locked == 'y') { throw new Exception("Topic vérrouillé", 301); }


        $sujet = $this->getSujetById($topic->sujet_id);
        if (!$sujet) { throw new Exception("Sujet introuvable", 402); }

        $sujet->id = $topic->sujet_id;
        $topic->id = $topicId;

        // Le post
        $this->setTable('ForumPost');
        $post = new stdClass();
        $post->message = $message;
        $post->topic_id = $topic->id;
        $post->auteur = $userId;
        $post->created_time = time();
        $post->ip = Securite::ipX();
        if ($this->save($post)) {
            $topic->last_post_id = $this->getLastInsertId();

            $nbPost = $this->getNbPostByTopicId($topic->id);
            // Le topic

            $sujet->last_post_id = $topic->last_post_id;
            $topic->nb_post = $nbPost;
            $topic->update_time = time();

            if ($locktopic) {
                $topic->locked = 'y';
            }
            $this->setTable('ForumTopic');
            $this->save($topic);

            $this->setTable('ForumSujet');
            $sujet->nb_post++;
            $this->save($sujet);

    //        $this->query('CALL refresh_forumflux();');
            return $sujet->last_post_id;
        }
    }


    /**
     * Récupère les catégories et sujets
     *
     * @param mixed $byCatId (default: false)
     * @param bool $getAll
     * @return array (stdClass) complexe 2 niveaux
     * @throws Exception
     */
    public function getCatList($byCatId = false, $getAll = false) {
        $this->setTable('ForumCat');
        $p = array(
            'fields' => '`id`, `name`, `description`, `order`',
            'order' => '`order` ASC',
        );
        if (is_numeric($byCatId) && $byCatId > 0) {
            $byCatId = (int) $byCatId;
            $p['conditions'] = 'id = ' . $byCatId;
        }

        $catList = $this->find($p);
        if (!$catList) {
            throw new Exception("Aucune cat&eacute;gorie", 400);
        }

        $data = array();
        for($s=0;$s<count($catList);$s++){
            $sujet = $this->getSujetListByCatId($catList[$s]->id);
            if ($sujet OR $getAll) {

                $catList[$s]->sujet = $sujet;
                $data[] = $catList[$s];
            }
        }

        unset($catList);
        return $data;
    }



    /**
     * Liste les topic en fonction du $sid
     *
     * @param int $sid
     * @param int $page
     * @param int $idmember si précisé, recherche les vus du topic
     * @return array(stdClass)
     */
    public function getTopicListBySujetId($sid, $page, $idmember = 0) {
        $this->setTable('ForumTopic');
        $page = $page-1;
        $start = ($page * 30);
        $idmember = (int) $idmember;
        $p = array(
            'fields' => '
				Forum.id AS topic_id, Forum.last_post_id AS last_post_id, Forum.sujet_id, Forum.titre, Forum.sous_titre, Forum.created_time,
				Forum.update_time, Forum.is_annonce, Forum.locked, Forum.solved, Forum.icone, Forum.nb_post,
				PA.name AS PAlogin, LPA.name AS LPAlogin, Forum.update_time,
				VW.topic_id AS Vtopic_id, VW.post_id AS Vpost_id, hasposter
			',
            'conditions' => 'Forum.sujet_id = ' . $sid,
            'limit' => $start . ', 30',
            'join' => array(
                __SQL . '_ForumPost AS FP' => 'FP.id = Forum.last_post_id',
                __SQL . '_ForumProfil AS PA' => 'PA.uid = Forum.auteur',
                __SQL . '_ForumProfil AS LPA' => 'LPA.uid = FP.auteur',
                __SQL .'_ForumView AS VW' => 'Forum.id = VW.topic_id AND VW.member_id = ' . $idmember
            ),
            'order' => '`Forum`.`is_annonce` DESC, FP.id DESC',
        );
        return $this->find($p);
    }

/**
 * Action
 */
 
 	
    public function updateView($uid, $tid, $pid, $hasPoster = false) {
        $this->setTable('ForumView');
		$prepare = array(
        	'fields' => 'id, post_id, hasposter',
            'conditions' => array(
                'member_id' => $uid, 
                'topic_id' => $tid,
            ));
        // Cherche si le topic est deja vue 
        $resp = $this->findFirst($prepare);
        if ($resp) {
			if ($resp->post_id != $pid) {
				$data = new stdClass();
				$data->id = $resp->id;
				$data->post_id  = $pid;
				$data->hasposter = ($hasPoster) ? $hasPoster : $resp->hasposter;
				$this->save($data);
			}
        } else {
           $data = new stdClass();
           $data->member_id = $uid; 
           $data->topic_id = $tid;
           $data->post_id  = $pid;
		   $data->hasposter = ($hasPoster) ? $hasPoster : 'n';
           $this->save($data);
        }
    }
	
	
 // ForumSujet
	// last_post_id	nb_topic	nb_post
 // ForumTopic
 	// nb_post first_post_id	last_post_id	locked
	public function getNbPostByTopicId($tid) {
		$this->setTable('ForumPost');
		return $this->count('topic_id = ' . $tid);
	}
 	
	
 	public function getNbTopicBySujetId($sid) {
 		$this->setTable('ForumTopic');
		return $this->count('sujet_id = ' . $sid);
 	}
 	
	
 	// SELECT SUM(nb_post) AS nb_post  FROM `iyc__ForumTopic` WHERE `sujet_id` = 11
 	public function getNbPostBySujetId($sid) {
 		$this->setTable('ForumTopic');
		$p= array(
			'fields' => 'SUM(nb_post) AS nb_post',
			'conditions' => array('sujet_id' => $sid)
			);
		//SELECT SUM(nb_post) FROM `iyc__ForumTopic` WHERE sujet_id = 11
		$r = $this->findFirst($p);
		$nb_post = (isset($r->nb_post)) ? $r->nb_post : 0;
		//$nb_post = ($nb_post - $this->getNbTopicBySujetId($sid));
		return $nb_post;
 	}
	
	

	
	
	public function attachTopic($sid) {
		$topic = $this->getTopicById($sid);
		if (!$topic) { throw new Exception("Topic introuvable", 401); }
		
		$topic->is_annonce = ($topic->is_annonce == 'y') ? 'n' : 'y'; 
		return $this->save($topic);
	}


	public function lockTopic($sid) {
		$topic = $this->getTopicById($sid);
		if (!$topic) { throw new Exception("Topic introuvable", 401); }
		
		$topic->locked = ($topic->locked == 'y') ? 'n' : 'y'; 
		return $this->save($topic);
	}

	
	public function move($tid, $sid) {
		$topicStart = $this->getTopicById($tid);
		if (!$topicStart) { throw new Exception("Topic introuvable", 401); }
		
		$sujetToMove = $this->getSujetById($sid);
		if (!$sujetToMove) { throw new Exception("Sujet introuvable", 402); }
		if ($sujetToMove->id == $topicStart->sujet_id) { throw new Exception("Impossible de déplacé A => A", 500); }
		
		// Sauve l'id du sujet
		$sujetStart = $topicStart->sujet_id;
		
		// Déplace le topic
        $this->setTable('ForumTopic');
		$topicStart->sujet_id = $sid;
		$this->save($topicStart);
		
		// Ensuite on recalcul 
		$start				= $this->getSujetById($sujetStart);
		$start->nb_topic	= $this->getNbTopicBySujetId($sujetStart);
		$start->nb_post		= $this->getNbPostBySujetId($sujetStart);
		
		$stop				= $this->getSujetById($sid);
		$stop->nb_topic		= $this->getNbTopicBySujetId($sid);
		$stop->nb_post		= $this->getNbPostBySujetId($sid);
		
		$this->setTable('ForumSujet');
		$this->save($start);
		$this->save($stop);
	}
	
	
 	public function delPost($pid) {
 		$post = $this->getPostById($pid);
		if (!$post) { throw new Exception("Post introuvable", 302); }
		
		$topic = $this->getTopicById($post->topic_id);
		if (!$topic) { throw new Exception("Topic introuvable", 401); }
		
		$sujet = $this->getSujetById($topic->sujet_id);
		if (!$sujet) { throw new Exception("Sujet introuvable", 402); }

		if ($topic->first_post_id == $pid) {
			$this->setTable('ForumPost');
			$this->primaryKey = 'topic_id';
			$this->delete($post->topic_id);
			
			
			$this->setTable('ForumTopic');
			$this->primaryKey = 'id';
			$this->delete($post->topic_id);
			
			try {
				$sujet->last_post_id = $this->getLastPostIdBySubjectId($topic->sujet_id);
			} catch (Exception $e) {
				$sujet->nb_post = 0;
			}
			
			
			$this->setTable('ForumTopic');
			$sujet->nb_topic = $this->count('sujet_id = ' . $topic->sujet_id);
			$sujet->nb_post = ($sujet->nb_post - $topic->nb_post);
			$this->setTable('ForumSujet');
			$this->save($sujet);
		} else {
			$this->setTable('ForumPost');
			$this->primaryKey = 'id';
			// Supprime le post
			$this->delete($pid);
		
			// Le derniere post
			// Nombre de post
			$nbPost = $this->count('topic_id = ' . $topic->id);
			$topic->nb_post = $nbPost;
			$topic->last_post_id = $this->getLastPostIdByTopicId($topic->id);
			$topic->update_time = $this->getLastUpdateTimeByTopicId($topic->id);
			$this->setTable('ForumTopic');
			$this->save($topic); 
			
			// Sujet
			$sujet->last_post_id = $this->getLastPostIdBySubjectId($topic->sujet_id);
			$sujet->nb_post = $this->getNbPostBySujetId($topic->sujet_id);
			$this->setTable('ForumSujet');
			$this->save($sujet);
		}
		
		
		$this->setTable('ForumPost');
		
		$arr = array(
			'fields' => 'id',
			'conditions' => array('topic_id' => 8760),
			'order' => 'id DESC',
			'limit' => '1'
			);
		$this->findFirst($arr);
 	}

	
 	public function editCat($cid, $name, $description) {
 		$this->setTable('ForumCat');
		if (strlen($name) < 2){throw new Exception("Erreur: Titre de la cat&eacute;gorie trop court", 1);}
		
		$data = new stdClass();
		$data->id = $cid;
		$data->name = $name;
		$data->description = $description;
		return $this->save($data);
 	}
	
	public function editSujetPerm($sid, $auth_view, $auth_topic, $auth_post) {
		$this->setTable('ForumSujet');
		$data = new stdClass();
		$data->id = $sid;
		$data->auth_view = $auth_view;
		$data->auth_topic = $auth_topic;
		$data->auth_post = $auth_post;
		return $this->save($data);
	}
	
 	public function editSujet($sid, $cid, $icone, $name, $description) {
 		$this->setTable('ForumSujet');
		if (strlen($name) < 2){throw new Exception("Erreur: Titre du sujet est trop court", 1);}
 		$data = new stdClass();
 		$data->id = $sid;
 		$data->cat_id = $cid;
 		$data->icone = $icone;
		$data->name = $name;
		$data->description = $description;
		return $this->save($data);
 	}
	
 	public function editTopic($tid, $title, $subtitle, $content) {
 		$topic = $this->getTopicById($tid);
		if (!$topic) { throw new Exception("Erreur: Topic introuvable", 401); }
		
		$this->setTable('ForumTopic');
			$topic->id = $tid;
			$topic->titre = $title;
			$topic->sous_titre = $subtitle;
		if ($this->save($topic)) {
			return $this->editPost($topic->first_post_id, $content);
		}
 	}
	
	
 	public function editPost($pid, $content) {
 		$post = $this->getPostById($pid);
		if (!$post) { throw new Exception("Erreur: Post introuvable", 302); }
		
		$this->setTable('ForumPost');
			$post->id = $pid;
			$post->edited_time = time();
			$post->ip = Securite::ipX();
			$post->message = $content;
		return $this->save($post);
 	}



	/**
	 * Probleme avec les tables a corrigé
	 */
 	public function last($page, $userid = false) {
 		
		$conditions = ($userid) ? 'Forum.auteur = ' . $userid . ' AND ' : '';
		// Pour la recherche des groupes valable
		$group = "'" . implode("' OR `LS`.`auth_view` LIKE '", $this->getUserGroup()) . "'";
		
		// Si $group > 2 alors, une liaison du group est possible, sinon, c'est 0
		$conditions .= (strlen($group) > 2) ? '(`LS`.`auth_view` LIKE ' . $group . ' OR `LS`.`auth_view` LIKE \'0\')' : "`LS`.`auth_view` LIKE '0'";


 		$page = (int) $page;
		$start = ($page-1) * 30;
		
		$this->setTable('ForumTopic');
		$t = array(
			'fields' => 'Forum.id AS Tid, LS.id AS Sid, 
						LA.loginmember AS Rlogin, TA.loginmember AS Alogin, 
						FC.name AS Cname, FC.description AS Cdescription, 
						LS.name AS Sname, LS.description AS Sdescription, 
						Forum.nb_post AS nb_post, locked, is_annonce, solved, 
						titre, sous_titre, update_time, cat_id',
			'join' => array(
				__SQL . '_ForumSujet AS LS' => 'LS.id = Forum.sujet_id', 
				__SQL . '_ForumCat AS FC' => 'FC.id = LS.cat_id',
				__SQL . '_ForumPost AS LP' => 'LP.id = Forum.last_post_id',
				__SQL . '_Member AS TA' => 'TA.idmember = Forum.auteur',
				__SQL . '_Member AS LA' => 'LA.idmember = LP.auteur'
				),
			'group' => 'Forum.id',
			'order' => 'update_time DESC',
			'conditions' =>  $conditions,
			'limit' => $start . ', 30'
			);
		return $this->find($t);
 	}



	
	
/**
 * Count
 */

	public function countTopic($userid = false) {
		$conditions = ($userid) ? 'Forum.auteur = ' . $userid . ' AND ' : '';
		// Pour la recherche des groupes valable
		$group = "'" . implode("' OR `FS`.`auth_view` LIKE '", $this->getUserGroup()) . "'";
		
		// Si $group > 2 alors, une liaison du group est possible, sinon, c'est 0
		$conditions .= (strlen($group) > 2) ? '(`FS`.`auth_view` LIKE ' . $group . ' OR `FS`.`auth_view` LIKE \'0\')' : "`Forum`.`auth_view` LIKE '0'";
		
		$this->setTable('ForumTopic');
		
		$data = array(
			'fields' => 'COUNT(*) AS count',
			'join' => array(__SQL . '_ForumSujet AS FS' => 'FS.id = Forum.sujet_id'),
			'conditions' => $conditions
			);
		$res = $this->findFirst($data);
		return ($res) ? $res->count : 0;;
	}
	
	
	public function countTopicBySujetId($sid) {
		$this->setTable('ForumTopic');
		$sid = (int) $sid;
		return $this->count('`sujet_id` = ' . $sid);
	}
	
	
	public function countPostByTopicId($tid) {
		$this->setTable('ForumPost');
		$tid = (int) $tid;
		
		$p = array(
			'fields' => 'COUNT(*) AS nb',
			'conditions' => '`Forum`.`topic_id` = ' . $tid
			);
		
		$c = $this->findFirst($p);
		return (isset($c->nb)) ? $c->nb : 0;
	}
	
/**
 * Unique
 */
 	

	/**
	 * Retourne la categorie et le sujet via l'id du sujet
	 * @param int $sid 
	 * @return stdClass
	 */
	public function getCatBySujetId($sid) {
		$this->setTable('ForumSujet');
		$sid = (int) $sid;
		
		// Pour la recherche des groupes valable
		$group = "'" . implode("' OR `auth_view` LIKE '", $this->getUserGroup()) . "'";
		
		// Si $group > 2 alors, une liaison du group est possible, sinon, c'est 0
		$conditions =  (strlen($group) > 2) ? '(auth_view LIKE ' . $group . ' OR auth_view LIKE \'0\')' : "ForumSujet.auth_view LIKE '0'";
		$p = array(
			'fields' => '
				`FC`.`id` AS `Cid`, `FC`.`name` AS `Cname`, `FC`.`description` AS `Cdescription`, 
				`Forum`.`id` AS `Sid`,`Forum`.`name` AS `Sname`, `Forum`.`description` AS `Sdescription`,
				`Forum`.`auth_view`, `Forum`.`auth_post`, `Forum`.`auth_topic`	
				',
			'order' => '`Forum`.`order` ASC',
			'join' => array(__SQL . '_ForumCat AS FC' => 'FC.id = Forum.cat_id'),
			'conditions' => '`Forum`.`id` = ' . $sid . ' AND ' . $conditions
			);
		
		return $this->findFirst($p);
	}
	
	
	public function getPostById($id) {
		$this->setTable('ForumPost');
		$id = (int) $id;
		$p = array(
			'fields' => '`id`, `topic_id`, `auteur`, `created_time`, `edited_time`, `message`, `ip`, `goodhelp`',
			// 'order' => '`order` ASC',
			'conditions' => 'id = ' . $id
			);
		return $this->findFirst($p);
	}
	
	
	public function getPostAndAuthorById($id) {
		$this->setTable('ForumPost');
		$id = (int) $id;
		$p = array(
			'fields' => '
				`Forum`.`id`, `Forum`.`topic_id`, `Forum`.`auteur`, `Forum`.`created_time`, 
				`Forum`.`edited_time`, `Forum`.`message`, `Forum`.`ip`, `Forum`.`goodhelp`,
				`Me`.`user`
				',
			'join' => array(__SQL . '_Auth AS Me' => 'Me.id = Forum.auteur'),
			'conditions' => '`Forum`.`id` = ' . $id
			);
		return $this->findFirst($p);
	}


/** RECUPERATION **/


	/**
	 * Retourne le topic aillant pour $id 
	 * 
	 * @param int $id identifiant du topic
	 * @return stdClass 
	 */
	public function getTopicById($id) {
		$this->setTable('ForumTopic');
		$id = (int) $id;
		$p = array(
			'fields' => '`id`, `sujet_id`, `titre`, `sous_titre`, `auteur`, `created_time`, 
			`update_time`, `is_annonce`, `first_post_id`, `last_post_id`, `locked`, `solved`, `icone`, `nb_post`',
			'conditions' => 'id = ' . $id,
			'limit' => '1'
			);
		return $this->findFirst($p);
	}
	

	/**
	 * Retourne le sujet aillant pour $id 
	 * 
	 * @param int $id identifiant du sujet
	 * @return stdClass 
	 */
	public function getSujetById($id) {
		$this->setTable('ForumSujet');
		$id = (int) $id;
		$p = array(
			'fields' => '`id`, `cat_id`, `icone`, `name`, `description`,
			`order`, `last_post_id`, `nb_topic`, `nb_post`, `auth_view`, `auth_post`, `auth_topic`',
			'conditions' => 'id = ' . $id,
			'limit' => '1'
			);
		return $this->findFirst($p);
	}
	
	
	/**
	 * Retourne la catégorie aillant pour $id 
	 * 
	 * @param int $id identifiant de la categorie
	 * @return stdClass 
	 */
	public function getCatById($id) {
		$this->setTable('ForumCat');
		$id = (int) $id;
		$p = array(
			'fields' => '`id`, `name`, `description`, `order`',
			'order' => '`order` ASC',
			'conditions' => 'id = ' . $id,
			'limit' => '1'
			);
		return $this->findFirst($p);
	}

	
	/**
	 * Retourne le dernier sujet
	 * 
	 * @return stdClass
	 */
	public function getLastSujet() {
		$this->setTable('ForumSujet');
		$arr = array(
			'fields' => '`id`, `order`',
			'order' => '`order` DESC',
			'limit' => '1'
			);
		return $this->findFirst($arr);
	}
	
	
	/**
	 * Retourne la derniere catégorie
	 * 
	 * @return stdClass
	 */
	public function getLastCat() {
		$this->setTable('ForumCat');
		$arr = array(
			'fields' => '`id`, `order`',
			'order' => '`order` DESC',
			'limit' => '1'
			);
		return $this->findFirst($arr);
	}
	

	/**
	 * Retourne l'id du dernier post en fonction du sujet
	 * 
	 * @param int $sid identifiant du sujet
	 * @return int identifiant du post
	 */
 	public function getLastPostIdBySubjectId($sid) {
 		$this->setTable('ForumTopic');
		$postId = $this->findFirst(
			array(
				'fields' => 'last_post_id',
				'conditions' => 'sujet_id = ' . $sid,
				'order' => '`last_post_id` DESC',
				'limit' => 1
				)
			);
		if (!$postId) { throw new Exception("Post introuvable", 302); }
		return (isset($postId->last_post_id)) ? $postId->last_post_id : 0;
		 	
 	}
	
	
	/**
	 * Retourne l'id du dernier post en fonction du topic
	 * 
	 * @param int $tid identifiant du topic
	 * @return int identifiant du post
	 */
 	public function getLastPostIdByTopicId($tid) {
		$this->setTable('ForumPost');
		$postId = $this->findFirst(
			array(
				'fields' => 'id',
				'conditions' => 'topic_id = ' . $tid,
				'order' => '`id` DESC',
				'limit' => 1
				)
			);
		if (!$postId) { throw new Exception("Post introuvable", 302); }
		return (isset($postId->id)) ? $postId->id : 0;
 	}
	
	
	/**
	 * Retourne le temps en seconde de la derniere mise a jour du $tic
	 * 
	 * @param int $tid identifiant du topic
	 * @return int temps en seconde
	 */
	public function getLastUpdateTimeByTopicId($tid) {
		$this->setTable('ForumPost');
		$p = array(
			'fields' => 'created_time, edited_time',
			'condtions' => 'topic_id = ' . $tid,
			'order' => '`created_time` DESC, `edited_time` DESC',
			'limit' => '1'
			);
		$data = $this->findFirst($p);
		return ($data->created_time < $data->edited_time) ? $data->edited_time : $data->created_time;
	}
	
	
	
	/** LISTE **/

	
	

	
	
	/**
	 * Liste les topic en fonction du $sid 
	 * 
	 * @param int $sid
	 * @param int $page
	 * @param int $idmember si précisé, recherche les vus du topic
	 * @deprecated utilisé $this->getTopicListBySujetId
	 * @return array(stdClass)
	 */
	public function getSujetListBySujetId($sid, $page, $idmember = 0) {
		return $this->getTopicListBySujetId($sid, $page, $idmember);
	}
	
	



	/**
	 * Retourne les sujets d'une catégorie
	 * 
	 * @param int $cid
	 * @return stdClass
	 */
    public function getSujetListByCatId($cid) {
	// Pour la recherche des groupes valable
	$group = "'" . implode("' OR `auth_view` LIKE '", $this->getUserGroup()) . "'";
		
		// Si $group > 2 alors, une liaison du group est possible, sinon, c'est 0
	$conditions =  (strlen($group) > 2) ? '(auth_view LIKE ' . $group . ' OR auth_view LIKE \'0\')' : "ForumSujet.auth_view LIKE '0'";
 
		$this->setTable('ForumSujet');
		$p = array(
			'fields' => '`Forum`.`id`, `Forum`.`cat_id`,
						`Forum`.`icone`, `Forum`.`name`, `Forum`.`description`, `Forum`.`order`,
						`Forum`.`last_post_id`, `Forum`.`nb_topic`, `Forum`.`nb_post`,
						`Forum`.`auth_view`, `Forum`.`auth_post`, `Forum`.`auth_topic`,
						
						FT.id AS Tid, FT.titre AS Ttitre, FT.sous_titre AS Tsous_titre, TA.name AS Tauteur,
						FT.created_time AS Tcreated_time, FT.update_time AS Tupdate_time, FT.is_annonce AS Tis_annonce, 
						FT.first_post_id AS Tfirst_post_id, FT.last_post_id AS Tlast_post_id, FT.locked AS Tlocked,
						FT.solved AS Tsolved, FT.icone AS Ticone, FT.nb_post AS Tnb_post,
						LP.name AS LPpost
						',
			'join' => array(
				__SQL . '_ForumPost AS FP' => 'Forum.last_post_id = FP.id',
				__SQL . '_ForumTopic AS FT' => 'FP.topic_id = FT.id',
                // Topic auteur
                __SQL . '_ForumProfil AS TA' => 'FT.auteur = TA.uid',
				/* Last Post */
				__SQL . '_ForumProfil AS LP' => 'FP.auteur = LP.uid'
				),
			'conditions' => '`Forum`.`cat_id` = ' . $cid . ' AND ' . $conditions,
			'order' => '`Forum`.`order` ASC',
			);
			
		return $this->find($p);
	}

	
	/**
	 * Retourne les post d'un topic
	 * 
	 * @param int $tid identifiant du topic
	 * @param int $page
	 * @return array(stdClass)
	 */
	public function getPostListByTopicId($tid, $page) {
		$this->setTable('ForumTopic');
		$page = $page-1;
		$start = ($page * 10); 
		$p = array(
			'fields' => 'FP.id, sujet_id, titre, sous_titre, FP.created_time,' .
					'FP.edited_time, is_annonce, first_post_id,' .
					'last_post_id, locked, solved, icone, nb_post,' .
					'FP.message,' .
                    'PA.name AS Pauteur, PA.sign AS Psign',
//					'FP.auteur, loginmember, groupmember,' .
//					'website, location, job, leisure, sign, sex, birthday',
			'conditions' => 'Forum.id = ' . $tid,
			'join' => array(
				__SQL . '_ForumPost AS FP' => 'FP.topic_id = Forum.id',
				__SQL . '_ForumProfil AS PA' => 'PA.uid = FP.auteur',
//				__SQL . '_MemberInfo AS PAI' => 'PAI.thismember = FP.auteur'
				),
			'order' => 'FP.id ASC',
			'limit' => $start . ', 10',
			);
		return $this->find($p);
	}
	
	
	
	
	
	
	public function addSujet($id, $icone, $name, $description, $auth_view, $auth_post, $auth_topic) {
		if (strlen($name) < 2) { throw new Exception("Erreur: Titre du sujet trop court", 1); }
		$sujetOrder = $this->getLastCat();
		$sujetOrder = (isset($sujetOrder->order)) ? $sujetOrder->order+1 : 1;
		
		$this->setTable('ForumSujet');
		$data = new stdClass();
		$data->cat_id = $id;
		$data->icone = $icone;
		$data->name = $name;
		$data->description = $description;
		$data->auth_view = $auth_view;
		$data->auth_post = $auth_post;
		$data->auth_topic = $auth_topic;
		$data->order = $sujetOrder;
		return $this->save($data);
	}




	
	public function addCat($catname, $catdescription) {
		if (strlen($catname) < 2) {
			throw new Exception("Titre trop court", 1);
		}
		$catOrder = $this->getLastCat();
		$catOrder = (isset($catOrder->order)) ? $catOrder->order+1 : 1;
		
		$this->setTable('ForumCat');
		$data				= new stdClass();
		$data->name			= $catname;
		$data->description	= $catdescription;
		$data->order		= $catOrder;
		return $this->save($data);
	}
	

	
	
	
	/** REPONDEUR **/
	
 
 
	/**
	 * retourne un tableau contenant la liste des réponse auto
	 * 
	 * @return array stdClass
	 */
 	public function getListRepondeur() {
		$this->setTable('ForumRepondeur');
		$p	= array(
				'fields' => 'id, title, message, lockpost',
				);
		return $this->find($p);
 	}
	
	
	/**
	 * Retourne la reponse auto dont l'id est en paramettre
	 * 
	 * @param int $rip
	 * @return stdClass 
	 */
 	public function getRepondeurById($rip) {
 		$rip = (int) $rip;
		$this->setTable('ForumRepondeur');
		$p	= array(
				'fields' => 'id, title, message, lockpost',
				'conditions' => 'id = ' . $rip
				);
		return $this->findFirst($p);
 	}
	
	
	/**
	 * Modifie la reponse auto selon les paramettres
	 * 
	 * @param int $id
	 * @param string $title
	 * @param string $message
	 * @param int $lockpost
	 * @return boolean 
	 */
	public function editRepondeurId($id, $title, $message, $lockpost) {
		$this->setTable('ForumRepondeur');
		$data = new stdClass();
		$data->id = (int) $id;
		$data->title = $title;
		$data->message = $message;
		$data->lockpost = (int) $lockpost;
		return $this->save($data);
	}
	
	
	/**
	 * Ajout d'une reponse auto selon les paramettres
	 * 
	 * @param string $title
	 * @param string $message
	 * @param int $lockpost
	 * @return boolean 
	 */
	public function addRepondeur($title, $message, $lockpost) {
		$this->setTable('ForumRepondeur');
		$data = new stdClass();
		$data->title = $title;
		$data->message = $message;
		$data->lockpost = (int) $lockpost;
		return $this->save($data);
	}
	
	
	/**
	 * Supprime la réponse auto dont l'id est en paramettre
	 * 
	 * @param int $rip
	 * @return boolean
	 */
	public function delRepondeur($rip) {
		$this->setTable('ForumRepondeur');
		$rip = (int) $rip;
		return $this->delete($rip);
	}



	/** BETA **/
	
	
	
	public function rsyncBySujetId($sujetId) {
		$sujetIs = $this->getSujetById($sujetId);
		
		$this->setTable('ForumTopic');
		$topic = $this->find(array('conditions' => 'sujet_id = ' . $sujetId));
		$sujetIs->last_post_id = 0;
		$sujetIs->nb_post = 0;
		$sujetIs->nb_topic = 0;
		
			for($p=0;$p<count($topic);$p++) {
				$this->setTable('ForumPost');
				$fpc = $this->findFirst(array(
					'fields' => 'COUNT(*) AS nb_post',
					'conditions' => 'topic_id = ' .  $topic[$p]->id
					));

					
				// Nb de post dans sujet
				$sujetIs->nb_post +=  $fpc->nb_post;
				
				$currenttopic = new stdClass();
				$currenttopic->id = $topic[$p]->id;
				$currenttopic->nb_post = $fpc->nb_post;
				$currenttopic->last_post_id = $this->getLastPostIdByTopicId($topic[$p]->id);
				if ($currenttopic->last_post_id > $sujetIs->last_post_id) {
					$sujetIs->last_post_id = $currenttopic->last_post_id;
				}
				
				$this->setTable('ForumTopic');
				$this->save($currenttopic);
			}
		

		$sujetIs->nb_topic = count($topic);
		$this->setTable('ForumSujet');
		$this->save($sujetIs);
	}
}