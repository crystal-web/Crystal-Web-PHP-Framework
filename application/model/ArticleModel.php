<?php
Class ArticleModel extends Model {
    public function install() {
        $this->query('CREATE TABLE IF NOT EXISTS `'.__SQL.'_Article` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `uid` int(11) NOT NULL,
		  `comment` int(11) NOT NULL,
		  `time` int(11) NOT NULL,
		  `picture` varchar(255) NOT NULL,
		  `title` varchar(100) NOT NULL,
		  `content` text NOT NULL,
		  PRIMARY KEY (`id`),
		  KEY `uid` (`uid`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;');
        $this->query('CREATE TABLE IF NOT EXISTS `'.__SQL.'_ArticleComment` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `bid` int(11) NOT NULL,
		  `user` varchar(16) NOT NULL,
		  `comment` varchar(160) NOT NULL,
		  PRIMARY KEY (`id`),
		  KEY `bid` (`bid`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
		');
    }

    public function getArticleList($showFull = false, $page = 1, $limit = 30) {
        $limit = (int) $limit;
        $page = (int) $page;
        $page--;

        $pre = array(
            'fields' => 'id, uid, name, comment, time, title',
            'limit' => $page . ', ' . $limit,
            'order' => 'id DESC'
        );

        if ($showFull) {
            $pre['fields'] = 'id, uid, name, comment, time, picture, title, content';
        }

        return $this->find($pre);
    }

    public function getLastArticle($nb=1){
        $nb = (int) $nb;
        $pre = array(
            'fields' => 'id, uid, name, comment, time, picture, title, content',
            'limit' => $nb,
            'order' => 'id DESC'
        );
        return $this->find($pre);
    }

    /**
     * Retourne les $nb articles du plus récent ou plus vieux
     *
     * @param int $nb nombre d'article
     * @return stdClass
     */
    public function getPost($nb = 2) {
        $this->setTable('Article');
        $nb = (int) $nb;

        return $this->find(array(
            'fields' => 'Article.id, uid, name, comment, time, picture, title, content',

            'limit' => $nb,
            'order' => 'id DESC'
        ));
    }


    /**
     * Retourne l'article aillant le $post_id si il existe, sinon retourne false
     *
     * @param $post_id identifiant de l'article
     * @return stdClass
     */
    public function getPostById($post_id) {
        $this->setTable('Article');
        return $this->findFirst(array(
            'fields' => 'Article.id, uid, name, comment, time, picture, title, content',

            'conditions' => 'Article.id = ' . $post_id
        ));
    }


    /**
     * Ajout d'un article
     *
     * @param $uid identifiant du posteur
     * @param $title titre de l'article
     * @param $content article
     * @param $picture nom du fichier image
     * @return stdClass
     */
    public function post($uid, $uname, $title, $content, $picture){
        $this->setTable('Article');
        $data = new stdClass();
        $data->uid = (int) $uid;
        $data->name = $uname;
        $data->title = $title;
        $data->content = $content;
        $data->picture = $picture;
        $data->time = time();
        if ($this->save($data)) {
            $data->id = $this->getLastInsertId();
            return $data;
        }
    }

    public function countArticle($cid = false){
        $this->setTable("Article");
        if ($cid) {

        }

        return $this->count();
    }


    /**
     * Edition de l'article $pid
     *
     * @param $pid identifiant de l'article
     * @param $title titre de l'article
     * @param $content article
     * @param $picture nom du fichier image
     * @return stdClass
     */
    public function edit($pid, $title, $content, $picture){
        $this->setTable('Article');
        $data = new stdClass();
        $data->id = $pid;
        $data->title = $title;
        $data->content = $content;
        $data->picture = $picture;
        $data->time = time();
        if ($this->save($data)) {
            return $data;
        }
    }

    /**
     * @param $page_id Article/Post id
     * @param $username
     * @param $mail
     * @param $comment
     */
    public function postComment($page_id, $username, $mail, $comment, $actived = false) {
        $this->setTable('ArticleComment');
        $data = new stdClass();
        $data->bid = (int) $page_id;
        $data->user = $username;
        $data->mail = $mail;
        $data->comment = $comment;
        $data->actived = ($actived == false) ? 0 : 1;
        $data->time = time();
        $data->ip = Securite::ipX();
        $resp = $this->save($data);
        if ($resp) {
            $post = $this->getPostById($page_id);
            $data = new stdClass();
            $data->id = $post->id = $page_id;
            $data->comment = $this->countComment(true, $page_id);
            $this->setTable('Article');
            $this->save($data);
        }
        return $resp;
    }

    public function getComment($page_id, $page = 1, $ifActived = true) {
        $this->setTable('ArticleComment');
        $ifActived = ($ifActived) ? '1' : '0';
        $start = (($page-1) * 30);
        $p = array(
            'conditions' => array('bid' => $page_id, 'actived' => $ifActived),
            'limit' => $start . ', 30',
            'order' => 'id DESC'
        );
        return $this->find($p);
    }

    public function countComment($ifActived = true, $inPost = false) {
        $this->setTable('ArticleComment');
        $ifActived = ($ifActived) ? 1 : 0;
        $cond = array('actived' => $ifActived);
        if ($inPost) {
            $cond['bid'] = $inPost;
        }

        return $this->count($cond);
    }

    public function getListComment($ifActived = true) {
        $this->setTable('ArticleComment');
        $ifActived = ($ifActived) ? '1' : '0';
        return $this->find(array('conditions' => array('actived' => $ifActived)));
    }


    /**
     * Validation des commentaire
     *
     * @param $cid Commentaire id
     */
    public function validateComment($cid) {
        $this->setTable('ArticleComment');
        // On recupère le commentaire
        $p = array('conditions' => array('id' => $cid));
        $commentaire = $this->findFirst($p);
        // Si on le trouve
        if ($commentaire) {
            // On l'active
            $commentaire->actived = 1;
            $this->save($commentaire);

            // Ensuite, il faut recompté le nombre de commentaire de l'article
            // On charge l'article lui même pour vité de posté, alors que nous souhaité edité
            $article = $this->getPostById($commentaire->bid);
            // Il faut que notre article existe
            if ($article) {
                // Comme l'article contient une jointure, il faut crée une nouvelle variable
                $data = new stdClass();
                // On set l'id, pour faire en sort que la mise a jour opére
                $data->id = $article->id;
                // On compte les commentaires actif
                $data->comment = $this->countComment(true, $commentaire->bid);
                // On re set la table
                $this->setTable('Article');
                // On fais la mise a jour de l'article
                $this->save($data);
            }
        }
    }

    public function deleteComment($cid) {
        $this->setTable('ArticleComment');
        // On recupère le commentaire
        $p = array('conditions' => array('id' => $cid));
        $commentaire = $this->findFirst($p);
        // Si on le trouve
        if ($commentaire) {
            // On l'efface
            $this->delete($cid);

            // Ensuite, il faut recompté le nombre de commentaire de l'article
            // On charge l'article lui même pour vité de posté, alors que nous souhaité edité
            $article = $this->getPostById($commentaire->bid);
            // Il faut que notre article existe
            if ($article) {
                // Comme l'article contient une jointure, il faut crée une nouvelle variable
                $data = new stdClass();
                // On set l'id, pour faire en sort que la mise a jour opére
                $data->id = $article->id;
                // On compte les commentaires actif
                $data->comment = $this->countComment(true, $commentaire->bid);
                // On re set la table
                $this->setTable('Article');
                // On fais la mise a jour de l'article
                $this->save($data);
            }
        }
    }
}
