<?php 
Class pluginMyStatsModel extends Model {

    public function install(){
        $this->query("
            CREATE TABLE IF NOT EXISTS `" . __SQL . "_pluginMyStats_Connectes` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `ip` varchar(64) NOT NULL,
              `timestamp` int(11) NOT NULL,
              `page` varchar(50) NOT NULL,
              `uid` int(11) NOT NULL DEFAULT '0',
              PRIMARY KEY (`id`),
              UNIQUE KEY `ip` (`ip`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
            
            -- --------------------------------------------------------
            
            CREATE TABLE IF NOT EXISTS `" . __SQL . "_pluginMyStats_Provenance` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `ip` varchar(64) NOT NULL,
              `http_referer` text NOT NULL,
              `domain` varchar(64) NOT NULL,
              `heure` int(11) NOT NULL DEFAULT '0',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
            
            -- --------------------------------------------------------
            
            CREATE TABLE IF NOT EXISTS `" . __SQL . "_pluginMyStats_VisitesJour` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `visites` mediumint(9) NOT NULL,
              `date` date NOT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `date` (`date`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
            
            ALTER TABLE  `" . __SQL . "_pluginMyStats_VisitesJour` ADD  `top` INT( 11 ) NOT NULL DEFAULT  '0' AFTER  `visites`;
        ");
    }
    
    private $_VisitesJour;
    private $_Connectes;
    
    public function countTodayView() {
        $this->setTable('pluginMyStats_VisitesJour');
        $view = $this->findFirst(array(
            'fields' => 'id, visites, top, date',
            'conditions' => array('date' => date('Y-m-d'))
            ));
        return $view;
    }
 
    public function countTopPageView($top = 5) {
        // $retour_max = mysqli_query($connexion, 'SELECT visites, date FROM visites_jour ORDER BY visites DESC LIMIT 0, 1'); //On sélectionne l'entrée qui a le nombre visite le plus important
        $this->setTable('pluginMyStats_VisitesJour');
        $view = $this->find(array(
            'fields' => 'id, visites, top, date',
            'limit' => '0, ' . $top,
            'order' => 'visites DESC'
            ));
        return ($top == 1) ? current($view) : $view;
    }
    
    public function countTopConnectes($top = 5) {
        // $retour_max = mysqli_query($connexion, 'SELECT visites, date FROM visites_jour ORDER BY visites DESC LIMIT 0, 1'); //On sélectionne l'entrée qui a le nombre visite le plus important
        $this->setTable('pluginMyStats_VisitesJour');
        $view = $this->find(array(
            'fields' => 'id, visites, top, date',
            'limit' => '0, ' . $top,
            'order' => 'top DESC'
            ));
        return ($top == 1) ? current($view) : $view;
    }
    
    public function countAllView(){
        $this->setTable('pluginMyStats_VisitesJour');
        $find = $this->findFirst(array(
                'fields' => 'SUM(visites) AS `all`'
            ));
        return (isset($find->all)) ? $find->all : 0;
    }
    
    public function countAllConnectes(){
        $this->setTable('pluginMyStats_VisitesJour');
        $find = $this->findFirst(array(
                'fields' => 'SUM(top) AS `all`'
            ));
        return (isset($find->all)) ? $find->all : 0;
    }
    
    public function avgView() {
        $visites = $this->countAllView();
        $find = $this->findFirst(array(
                'fields' => 'COUNT(visites) AS  `day`'
            ));
        $day = (isset($find->day)) ? $find->day : 0;
        if ($day == 0) {
            return 0;
        }
        return ceil($visites/$day); //on fait la moyenne
        //*/ 
    }
    
    public function avgConnectes() {
        $visites = $this->countAllConnectes();
        $find = $this->findFirst(array(
                'fields' => 'COUNT(top) AS  `day`'
            ));
        $day = (isset($find->day)) ? $find->day : 0;
        if ($day == 0) {
            return 0;
        }
        return ceil($visites/$day); //on fait la moyenne
        //*/ 
    }    
    
    public function insertView(){
        $this->_VisitesJour = $this->countTodayView(true);
        if (!isset($this->_VisitesJour->visites)) {
            $this->_VisitesJour = new stdClass();
            $this->_VisitesJour->visites = 0;
        }
        $this->_VisitesJour->visites++;
        $this->_VisitesJour->date = date('Y-m-d');
        $this->save($this->_VisitesJour);
        $this->_trackConnectes();
    }
    
    public function connectes() {
        if (!$this->_Connectes) {
            $this->setTable('pluginMyStats_Connectes');   
            $find = $this->findFirst(array(
                    'fields' => 'COUNT(id) AS nb'
                ));
            $this->_Connectes = (isset($find->nb)) ? $find->nb : 0;
        }
        
        return $this->_Connectes;
    }
    
    public function getTopReferer(){
        $this->setTable('pluginMyStats_Provenance');
        return $this->find(array(
            'fields' => 'COUNT(domain) AS nb, domain',
            'group' => 'domain',
            'order' => 'nb DESC',
            'limit' => '0, 30'
            ));
    }
    
    private function _trackConnectes() {
        $this->setTable('pluginMyStats_Connectes');
        $page = preg_replace("#index.php#", '', substr($_SERVER['PHP_SELF'], 1));
        $ip = Securite::ipX();
        $session = Session::getInstance();
        $uid = ($session->isLogged()) ? $session->user('id') : 0;
        // 60 * 5 = nombre de secondes écoulées en 5 minutes
        // On commence par virer les entrées trop vieilles (+ de 5 minutes)
        $this->query("DELETE FROM " . __SQL . "_pluginMyStats_Connectes WHERE timestamp < " . (time() - (60 * 5)) );
        
        $findUser = $this->findFirst(array(
                'fields' => 'id',
                'conditions' => array(
                    'ip' => $ip
                    ),
            ));
        
        if (!$findUser) {
            $findUser = new stdClass();
            $findUser->ip = $ip; 
        }
        
        $findUser->page = $page;
        $findUser->timestamp = time();
        $findUser->uid = $uid;
        $this->save($findUser);
        
        /**
         * Compte les visiteurs en ligne
         */
        $this->_Connectes = $this->connectes();
        // Si il y a un nombre suppérieur a celui enregistré
        if ($this->_VisitesJour->top < $this->_Connectes) {
            // On enregistre dans la base de donnée
            $this->setTable('pluginMyStats_VisitesJour');
            $this->_VisitesJour->top = $this->_Connectes;
            $this->save($this->_VisitesJour);
            $this->setTable('pluginMyStats_Connectes');
        }
        
        $this->_provenance();
    }

    private function _provenance() {
        // strpos(strtolower($_SERVER['HTTP_REFERER']), $_SERVER['SERVER_NAME']) != 0 pour les sous site
        if (isset($_SERVER['HTTP_REFERER']) && strpos(strtolower($_SERVER['HTTP_REFERER']), $_SERVER['SERVER_NAME']) != 0 && isURL($_SERVER['HTTP_REFERER'])) {//Si le visiteur provient d'un autre site. 
            $heureAffichage = time()-30; //Le temps qu'il était il y a 30 secondes
            $this->setTable("pluginMyStats_Provenance");
            //On sélectionne toutes les entrées ayant l'IP du visiteur pour lesquelles l'heure enregistrée est plus grande que l'heure qu'il était il y a 5 minutes.
            $find = $this->findFirst(array(
                    'fields' => 'COUNT(id) as nb_in',
                    'conditions' => 
                        'ip = \'' . Securite::ipX() . '\' AND ' . 
                        'heure > ' . $heureAffichage
                ));
            if ($find->nb_in == 0){ // S'il n'y a aucune entrée qui a notre IP et qui a été enregistrée il y a 5 minutes
                $data = new stdClass();
                $data->ip = Securite::ipX();
                $data->http_referer = clean($_SERVER['HTTP_REFERER'], 'str');
                
                $http = parse_url($data->http_referer);
                $data->domain = $http['host'];
                $data->heure = time();
                $this->save($data);
            }
        }
    }
}