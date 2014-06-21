<?php
class TrackModel extends Model {
    private $projectSlug;
    private $oProject;

    private $userId;

    public function install(){
        $this->query("CREATE TABLE IF NOT EXISTS `" . __SQL . "_TrackProjects` (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `codename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `info` longtext COLLATE utf8_unicode_ci NOT NULL,
            `next_tid` bigint(20) NOT NULL DEFAULT '1',
            `enable_wiki` tinyint(1) NOT NULL DEFAULT '0',
            `default_ticket_type_id` int(11) DEFAULT NULL,
            `default_ticket_sorting` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'priority.asc',
            `displayorder` bigint(20) NOT NULL DEFAULT '0',
            `private_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");


        $this->query("CREATE TABLE IF NOT EXISTS `" . __SQL . "_TrackPriorities` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");



        $this->query("CREATE TABLE IF NOT EXISTS `" . __SQL . "_TrackSeverities` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");


        $this->query("CREATE TABLE IF NOT EXISTS `" . __SQL . "_TrackStatuses` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `status` int(1) NOT NULL DEFAULT '0' COMMENT '1 status ',
            `changelog` int(1) NOT NULL DEFAULT '0' COMMENT '1 changelog',
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");

        $this->query("CREATE TABLE IF NOT EXISTS `" . __SQL . "_TrackTicketRelationships` (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            `ticket_id` bigint(20) NOT NULL,
            `related_ticket_id` bigint(20) NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");


        $this->query("CREATE TABLE IF NOT EXISTS `" . __SQL . "_TrackTickets` (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            `ticket_id` bigint(20) NOT NULL,
            `summary` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `body` longtext COLLATE utf8_unicode_ci NOT NULL,
            `user_id` bigint(20) NOT NULL,
            `project_id` bigint(20) NOT NULL,
            `milestone_id` bigint(20) NOT NULL DEFAULT '0',
            `version_id` bigint(20) NOT NULL,
            `component_id` bigint(20) NOT NULL,
            `type_id` bigint(20) NOT NULL,
            `status_id` bigint(20) NOT NULL DEFAULT '1',
            `priority_id` bigint(20) NOT NULL DEFAULT '3',
            `severity_id` bigint(20) NOT NULL,
            `assigned_to_id` bigint(20) NOT NULL,
            `is_closed` bigint(20) NOT NULL DEFAULT '0',
            `is_private` smallint(6) NOT NULL DEFAULT '0',
            `votes` bigint(20) DEFAULT '0',
            `tasks` longtext COLLATE utf8_unicode_ci,
            `extra` longtext COLLATE utf8_unicode_ci NOT NULL,
            `created_at` datetime NOT NULL,
            `updated_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");


        $this->query("CREATE TABLE IF NOT EXISTS `" . __SQL . "_TrackTimeline` (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            `project_id` bigint(20) NOT NULL,
            `owner_id` bigint(20) NOT NULL,
            `action` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `data` longtext COLLATE utf8_unicode_ci NOT NULL,
            `user_id` bigint(20) NOT NULL,
            `created_at` datetime NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");


        $this->query("CREATE TABLE IF NOT EXISTS `" . __SQL . "_TrakTypes` (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `bullet` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
            `changelog` smallint(6) NOT NULL DEFAULT '1',
            `template` longtext COLLATE utf8_unicode_ci NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");

    }

    /**
     * Attribue le slug du projet en cours et enregistre le projet en mémoire
     * @param $projectSlug
     * @return bool
     */
    public function setProject($projectSlug){
        $this->setTable('TrackProjects');
        $findProject = array(
            'conditions' => array(
                'slug' => $projectSlug
                )
            );
        $this->oProject = $this->findFirst($findProject);
        if ($this->oProject){
            $this->projectSlug = $projectSlug;
            return true;
        }
    }



    /**
     * Retourne le slug du projet en cours
     * @return string
     * @throws Exception
     */
    public function getProjectSlug(){
        if (is_null($this->projectSlug)) {
            throw new Exception('Set project slug before');
        }
        return $this->projectSlug;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function getProjectId(){
        if (is_null($this->projectSlug)) {
            throw new Exception('Set project slug before');
        }
        return $this->oProject->id;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getProjectName(){
        if (is_null($this->projectSlug)) {
            throw new Exception('Set project slug before');
        }
        return $this->oProject->name;
    }


    public function getProjectInfo(){
        if (is_null($this->projectSlug)) {
            throw new Exception('Set project slug before');
        }
        return $this->oProject->info;
    }

    /**
     * Si un utilisateur connecté affiche la page, on recherche aussi les infos de celui-ci
     * @param int $uid
     */
    public function setUserId($uid) {
        $this->userId = (int) $uid;
    }

    /**
     * Retourne la liste des projets
     * @param $page
     * @return stdClass
     */
    public function getProjectsList($page) {
        $page = (int) $page;
            $page--;
        $start = ($page * 50);

        $this->setTable('TrackProjects');
        $findProjects = array(
            'limit' => $start . ', 50'
        );
        $resp = $this->find($findProjects);
        for ($i=0;$i<count($resp);$i++) {
            //cwDebug($this->getCountTicket());
            $resp[$i]->ticketNbOpen = $this->getCountTicketOpen( $resp[$i]->id )->nb;
            $resp[$i]->ticketNb = $this->getCountTicket( $resp[$i]->id, false )->nb;
        }
        return $resp;
    }

    public function getCountTicketOpen($projetId, $open = true) {
        $this->setTable('TrackStatuses');
        return $this->findFirst(array(
            'fields' => 'COUNT(TT.id) AS nb',
            'join' => array(__SQL . '_TrackTickets AS TT' => 'TT.status_id = Track.id'),
            'conditions' => 'Track.status = 1 AND project_id = ' . $projetId
        ));
    }
    public function getCountTicket($projetId) {
        $this->setTable('TrackTickets');
        return $this->findFirst(array(
            'fields' => 'COUNT(Track.id) AS nb',
            'conditions' => 'project_id = ' . $projetId
        ));
    }

    public function getUserId(){
        return (is_null($this->userId)) ? 0 : $this->userId;
    }

/**
  _______ _      _        _
 |__   __(_)    | |      | |
    | |   _  ___| | _____| |_ ___
    | |  | |/ __| |/ / _ \ __/ __|
    | |  | | (__|   <  __/ |_\__ \
    |_|  |_|\___|_|\_\___|\__|___/
************************************/

    public function addTicket($type,
            $severity,
            $prioritie,
            $milestone,
            $assigned,
            $summary,
            $body){
        $data = new stdClass();
        $data->summary= $summary;
        $data->body = $body;
        $data->user_id = $this->getUserId();
        $data->project_id = $this->getProjectId();
        $data->milestone_id = (int) $milestone;
        $data->type_id = (int) $type;
        $data->priority_id = (int) $prioritie;
        $data->severity_id = (int) $severity;
        $data->assigned_to_id = (int) $assigned;
        $data->created_at = time();

        //! $data->version_id
        //! component_id
        //! $data->status_id
        //! is_closed
        //! is_private
        //! votes
        //! tasks
        //! extra
        //! updated_at
        $this->setTable('TrackTickets');
        return $this->save($data);
    }

    public function editTicket($tid,
                              $statuses,
                              $type,
                              $severity,
                              $prioritie,
                              $milestone,
                              $assigned,
                              $summary,
                              $body){
        if ($this->getTicket($tid)) {
            $data = new stdClass();
            $data->id = (int) $tid;
            $data->status_id = $statuses;
            $data->summary= $summary;
            $data->body = $body;
            $data->milestone_id = (int) $milestone;
            $data->type_id = (int) $type;
            $data->priority_id = (int) $prioritie;
            $data->severity_id = (int) $severity;
            $data->assigned_to_id = (int) $assigned;
            $data->created_at = time();
            //! $data->version_id
            //! component_id
            //! $data->status_id
            //! is_closed
            //! is_private
            //! votes
            //! tasks
            //! extra
            //! updated_at
            $this->setTable('TrackTickets');
            return $this->save($data);
        }
    }


    public function getTicketList($page){
        $this->setTable('TrackTickets');
        return $this->find(
            array(
                'fields' =>
                    'summary, Track.id,'  .
                    'TS.name AS severity_name, severity_id,' .
                    'TP.name AS priority_name, priority_id, ' .
                    'TSt.name AS status_name, TSt.status AS status_status, ' .
                    'TM.name AS milestone_name, milestone_id,' .
                    'TT.name AS type_name, type_id,' .
                    'A.user AS open_username,' .
                    'ASS.user AS assign_username' .
                    '',
                'conditions' => array(
                    'Track`.`project_id' => $this->getProjectId(),
                ),
                'join' => array(
                    __SQL . '_Auth AS A' => 'A.id = user_id',
                    __SQL . '_Auth AS ASS' => 'ASS.id = assigned_to_id',

                    __SQL . '_TrackSeverities AS TS' => 'TS.id = severity_id',
                    __SQL . '_TrackPriorities AS TP' => 'TP.id = priority_id',
                    __SQL . '_TrackStatuses AS TSt' => 'TSt.id = status_id',
                    __SQL . '_TrackMilestones AS TM' => 'TM.id = milestone_id',
                    __SQL . '_TrakTypes AS TT' => 'TT.id = type_id'
                ),
                'order' => 'Track.id DESC'
            )
        );
    }

    public function getTicket($tid){
        $this->setTable('TrackTickets');
        return $this->findFirst(
            array(
                'fields' =>
                    'summary, body, Track.id, created_at,'  .
                    'TS.name AS severity_name, severity_id,' .
                    'TP.name AS priority_name, priority_id,' .
                    'TSt.name AS status_name, TSt.status AS status_status, status_id,' .
                    'TT.name AS type_name, type_id,' .
                    'TM.name AS milestone_name, milestone_id,' .
                    'OPEN.user AS open_username, OPEN.id AS open_userid,' .
                    'ASS.user AS assign_username, ASS.id AS assign_userid' .
                    '',
                'conditions' => array(
                    'Track`.`project_id' => $this->getProjectId(),
                    'Track`.`id' => (int) $tid
                ),
                'join' => array(
                    __SQL . '_Auth AS OPEN' => 'OPEN.id = user_id',
                    __SQL . '_Auth AS ASS' => 'ASS.id = assigned_to_id',
                    __SQL . '_TrackSeverities AS TS' => 'TS.id = severity_id',
                    __SQL . '_TrackPriorities AS TP' => 'TP.id = priority_id',
                    __SQL . '_TrackStatuses AS TSt' => 'TSt.id = status_id',
                    __SQL . '_TrackMilestones AS TM' => 'TM.id = milestone_id',
                    __SQL . '_TrakTypes AS TT' => 'TT.id = type_id'
                ),
                'limit' => 1
            )
        );
    }


    public function addTicketComment($tid, $uid, $comment) {
        $this->setTable("TrackTicketsComment");
        $data           = new stdClass();
        $data->tid      = (int) $tid;
        $data->uid      = (int) $uid;
        $data->comment  = $comment;
        $data->time     = time();
        $this->save($data);
    }


    public function getTicketComment($tid) {
        $this->setTable("TrackTicketsComment");
        $tid = (int) $tid;
        return $this->find(
            array(
                'fields' => '`user`, `mail`, `group`, `uid`, `comment`, `time`',
                'limit' => '0, 50',
                'order' => "`time` DESC",
                'conditions' => array('tid' => $tid),
                'join' => array(
                    __SQL . '_Auth as A' => 'A.id = uid'
                )
            )
        );
    }



/**
  _______        _
 |__   __|      | |
    | | __ _ ___| | _____
    | |/ _` / __| |/ / __|
    | | (_| \__ \   <\__ \
    |_|\__,_|___/_|\_\___/
****************************/

    /**
     * Retourne la liste des tâches enregistré
     * @param $page
     * @return stdClass
     * @throws Exception
     */
    public function getTasksList($page){
        if (is_null($this->projectSlug)) {
            throw new Exception('Set project slug before');
        }

        $page = (int) $page;
        $page--;
        $start = ($page * 50);

        $this->setTable('TrackTasks');
        $findTasks= array(
            'fields' => '
                Track.id, milestone_id, version_id, Track.name, Track.rate,
                TL.id TLid, TL.state, TL.name TLname,
                (SELECT SUM( minutes ) FROM  `' . __SQL . '_TrackTasksTimer` WHERE task_id = TLid) AS minutesSum',
            'conditions' => array(
                'project_id' => $this->getProjectId()
            ),
            'join' => array(
                __SQL . '_TrackTasksList AS TL' => 'TL.task_id = Track.id'
            ),
            'limit' => $start . ', 50'
        );
        if (!is_null($this->userId)) {
            $findTasks['fields'] .= ', TT.minutes, TT.timer';
            $findTasks['join'][__SQL . '_TrackTasksTimer AS TT'] = 'TT.task_id = TL.id AND TT.user_id = ' . $this->userId;
        }
        return $this->find($findTasks);
    }

    /**
     * Marque une tâche comme open|close
     * @param $taskId
     * @return bool
     * @throws Exception
     */
    public function taskStatusToggle($taskGroupId, $taskId){
        if (is_null($this->projectSlug)) {
            throw new Exception('Set project slug before');
        }
        if (is_null($this->userId)) {
            throw new Exception('Set user id before call it');
        }
        if (!$this->isInTeam()) {
            throw new Exception('Not allowed');
        }

        $this->setTable('TrackTasksList');
        $pre = array(
            'fields' => 'id, state, task_id',
            'conditions' => array(
                'id' => $taskId
            )
        );
        $task = $this->findFirst($pre);
        if(isset($task)) {
            $task->state = ($task->state == 'close') ? 'open' : 'close';
            if ($this->save($task)){
                return $this->calculTaskRate($taskGroupId);
            }
            return false;
        }
    }

    /**
     * Calcul le ratio de complétion
     * @param $taskGroupId
     * @return float
     */
    private function calculTaskRate($taskGroupId){
        $resp = $this->query("
            SELECT
            (SELECT  count(state) FROM `" . __SQL . "_TrackTasksList` WHERE `task_id` = ".$taskGroupId." AND state LIKE 'close') AS close,
            (SELECT  count(state) FROM `" . __SQL . "_TrackTasksList` WHERE `task_id` = ".$taskGroupId." AND state LIKE 'open')  AS open
            ", true);
        if (isset($resp[0])) {
            $close = (isset($resp[0]->close)) ? $resp[0]->close : 0;
            $open = (isset($resp[0]->open)) ? $resp[0]->open : 0;
            $total = $close + $open;
            if ($total > 0){
                $rate = ($close * 100) / $total;
                $this->setTable('TrackTasks');
                $data = new stdClass();
                $data->id = $taskGroupId;
                $data->rate = $rate;
                $this->save($data);
                return $rate;
            }
        }
    }

    /**
     * Active ou desactive le compteur
     * Tout les compteurs actif (normalement 1) sont arrêté, avant d'activé (si besoin)
     * @param $taskId
     * @throws Exception
     */
    public function taskTimerToggle($taskGroupId, $taskId){
        if (is_null($this->projectSlug)) {
            throw new Exception('Set project slug before');
        }
        if (is_null($this->userId)) {
            throw new Exception('Set user id before call it');
        }

        $taskId = (int) $taskId;
        if ($taskId == 0){return 'task = 0';}
        $this->setTable('TrackTasksList');
        $findTask = array(
            'conditions' => array(
                'id' => $taskId,
                'task_id' => $taskGroupId
            )
        );
        $task = $this->findFirst($findTask);
        if (!$task){return 'not found';}

        $this->setTable('TrackTasksTimer');

        // On cherche la tache et l'utilisateur
        $findTaskTimer = array('conditions' =>
            array(
                'task_id' => $task->id,
                'user_id' => $this->userId
            ));
        $taskTimer = $this->findFirst($findTaskTimer);

        $this->clearTaskUserTimer();

        $this->setTable('TrackTasksTimer');
        // id task_id user_id minutes timer
        // Si on en a une tache est trouvé
        if (!$taskTimer){
            $taskTimer = new stdClass();
            $taskTimer->task_id = $taskId;
            $taskTimer->user_id = $this->userId;
            $taskTimer->timer = time();
            if ($this->save($taskTimer)) {
                return 0;
            }
        } else {
            // Si timer est a zero, le compteur est a l'arrêt
            if ($taskTimer->timer == 0) {
                $taskTimer->timer = time();
                if ($this->save($taskTimer)) {
                    return $taskTimer->minutes + ceil((time() - $taskTimer->timer) / 60);
                }
            } else {
                return $taskTimer->minutes + ceil((time() - $taskTimer->timer) / 60);
            }
        }

        return false;
    }

    /**
     * Retourne la somme du temps de travail des tâches
     * @param $taskId
     * @return bool
     */
    public function getTaskSumTimer($taskId){
        $this->setTable('TrackTasksTimer');
        $findTask = array(
            'fields' => 'SUM(minutes) AS minutes',
            'conditions' => array(
                'task_id' => $taskId
            )
        );
        $task = $this->findFirst($findTask);
        if (!$task){return false;}
        return $task->minutes;
    }

    /**
     * Reset tout les timer de l'utilisateur
     * @throws Exception
     */
    private function clearTaskUserTimer(){
        if (is_null($this->projectSlug)) {
            throw new Exception('Set project slug before');
        }
        if (is_null($this->userId)) {
            throw new Exception('Set user id before call it');
        }

        $this->setTable('TrackTasksTimer');

        // On cherche la tache et l'utilisateur
        $findTask = array('conditions' => 'user_id LIKE '. $this->userId . ' AND timer > 0');
        $tasks = $this->find($findTask);

        for ($i=0;$i<count($tasks);$i++) {
            $tasks[$i]->minutes = $tasks[$i]->minutes + ceil((time() - $tasks[$i]->timer) / 60) ;
            $tasks[$i]->timer = 0;
            $this->save($tasks[$i]);
        }
    }

    /**
     * @param $id
     * @return stdClass
     */
    private function getTaskGroup($id) {
        $id = (int) $id;
        $this->setTable('TrackTasks');
        $group = array('conditions' => array('id' => $id));
        return $this->findFirst($group);
    }

    /**
     * @param $taskName
     * @return array
     * @throws Exception
     */
    public function addTaskGroup($taskName){
        if (is_null($this->projectSlug)) {
            throw new Exception('Set project slug before');
        }
        if (is_null($this->userId)) {
            throw new Exception('Set user id before call it');
        }

        if (empty($taskName)) {
            throw new Exception('Task is empty');
        }

        $this->setTable('TrackTasks');
        $data = new stdClass();
        $data->project_id = $this->getProjectId();
        $data->milestone_id = 0;
        $data->version_id = 0;
        $data->name = $taskName;
        if ( $this->save($data) ) {
            return array('task' => $data->name, 'id' => $this->getLastInsertId());
        }
    }

    /**
     * @param $groupId
     * @param $taskName
     * @return bool
     * @throws Exception
     */
    public function addTask($groupId, $taskName){
        if (is_null($this->projectSlug)) {
            throw new Exception('Set project slug before');
        }
        if (is_null($this->userId)) {
            throw new Exception('Set user id before call it');
        }
        if (empty($taskName)) {
            throw new Exception('Task is empty');
        }

        $groupId = (int) $groupId;
        $group = $this->getTaskGroup($groupId);
        if (!$group) {
            throw new Exception('Task group not found');
        }

        $this->setTable('TrackTasksList');
        $data = new stdClass();
        $data->task_id = $groupId;
        $data->state = 'open';
        $data->name = $taskName;
        if( $this->save($data) ){
            return array(
                'task' => $data->name,
                'id' => $this->getLastInsertId()
            );
        }
    }


    /**
     * Edition d'une tâche
     * @param $taskGroupId
     * @param $taskId
     * @param $newValue
     * @return bool
     * @throws Exception
     */
    public function editTask($taskGroupId, $taskId, $newValue){
        if (is_null($this->projectSlug)) {
            throw new Exception('Set project slug before');
        }
        if (is_null($this->userId)) {
            throw new Exception('Set user id before call it');
        }
        if (!$this->isLeader()) {
            throw new Exception('Not a leader');
        }
        $this->setTable('TrackTasksList');

        $taskId = (int) $taskId;
        $taskGroupId = (int) $taskGroupId;
        $findTask = array(
            'conditions' => array(
                'id' => $taskId,
                'task_id' => $taskGroupId
            ),
            'limit' => '1'
        );
        $task = $this->findFirst($findTask);
        if (!$task) {return false;}

        $task->name = $newValue;
        return $this->save($task);
    }

    /**
     * Supprime une tâche
     * @param $taskGroupId
     * @param $taskId
     * @return bool
     * @throws Exception
     */
    public function delTask($taskGroupId, $taskId){
        if (is_null($this->projectSlug)) {
            throw new Exception('Set project slug before');
        }
        if (is_null($this->userId)) {
            throw new Exception('Set user id before call it');
        }
        if (!$this->isLeader()) {
            throw new Exception('Not a leader');
        }
        $this->setTable('TrackTasksList');

        $taskId = (int) $taskId;
        $taskGroupId = (int) $taskGroupId;
        $findTask = array(
            'conditions' => array(
                'id' => $taskId,
                'task_id' => $taskGroupId
            ),
            'limit' => '1'
        );
        $task = $this->findFirst($findTask);
        if (!$task) {return false;}
        if ($this->delete($taskId)){
            $this->setTable('TrackTasksTimer');
            $this->primaryKey = 'task_id';
            $this->delete($taskGroupId);
            $this->primaryKey = 'id';
        }
        return true;
    }

    /**
     * Supprime un objectif
     * @param $taskGoalsId
     * @return bool
     * @throws Exception
     */
    public function delTaskGoals($taskGoalsId) {
        if (is_null($this->projectSlug)) {
            throw new Exception('Set project slug before');
        }
        if (is_null($this->userId)) {
            throw new Exception('Set user id before call it');
        }
        if (!$this->isLeader()) {
            throw new Exception('Not a leader');
        }

        $this->setTable('TrackTasks');
        $findGoals = array(
            'conditions' => array(
                'project_id' => $this->oProject->id,
                'id' => $taskGoalsId
            )
        );
        $goals = $this->find($findGoals);
        $this->delete($taskGoalsId);

        if ($goals) {
            $this->setTable('TrackTasksList');
            $this->primaryKey = 'task_id';
            $this->delete($taskGoalsId);
        }
        return true;
    }


    public function editTaskGoals($taskGoalsId, $newValue){
        if (is_null($this->projectSlug)) {
            throw new Exception('Set project slug before');
        }
        if (is_null($this->userId)) {
            throw new Exception('Set user id before call it');
        }
        if (!$this->isLeader()) {
            throw new Exception('Not a leader');
        }

        $this->setTable('TrackTasks');
        $findGoals = array(
            'conditions' => array(
                'project_id' => $this->getProjectId(),
                'id' => $taskGoalsId
            )
        );
        $goals = $this->findFirst($findGoals);
        if ($goals) {
            $goals->name = $newValue;
            return $this->save($goals);
        }
    }


/**
  _______
 |__   __|
    | | ___  __ _ _ __ ___
    | |/ _ \/ _` | '_ ` _ \
    | |  __/ (_| | | | | | |
    |_|\___|\__,_|_| |_| |_|
 ****************************/

    /**
     * Recherche si l'utilisateur est dans la team du projet
     * @param bool $uid si défini test si $uid est dans la team
     * @return bool
     * @throws Exception
     */
    public function isInTeam($uid = false){
        if (is_null($this->projectSlug)) {
            throw new Exception('Set project slug before');
        }
        if (is_null($this->userId)) {
            throw new Exception('Set user id before call it');
        }
        $uid = (!$uid) ? $this->userId : $uid;
        $this->setTable('TrackTeam');
        $pre = array(
            'fields' => '`tag`', // Auth
            'conditions' => 'Track.id = ' . $this->oProject->team_id . ' AND user_id = ' . $uid,
            'join' => array(
                __SQL . '_TrackTeamList AS TL' => 'TL.team_id = Track.id',
            ),
            'limit' => '1'
        );
        return ($this->findFirst($pre)) ? true : false;
    }

    /**
     * Recherche si l'utilisateur est le leader du projet
     * @return bool
     * @throws Exception
     */
    public function isLeader(){
        if (is_null($this->projectSlug)) {
            throw new Exception('Set project slug before');
        }
        if (is_null($this->userId)) {
            throw new Exception('Set user id before call it');
        }
        return ($this->oProject->leader_id == $this->userId) ? true : false;
    }

    /**
     * Recupère les infos sur la team du projet
     * @return stdClass
     * @throws Exception
     */
    public function getTeam(){
        if (is_null($this->projectSlug)) {
            throw new Exception('Set project slug before');
        }
        $this->setTable('TrackTeam');
        $pre = array(
            'fields' =>
                '`Track`.`id`, `leader_id`, `tag`, `Track`.`name` AS `team_name`, `Track`.`slogan` AS `team_slogan`, `create_time`,' .  // TrackTeam
                '`TL`.`user_id` AS `user_id`, `join_date`,' .  // TrackTeamList
                '`user`, `group`', // Auth
            'conditions' => 'Track.id = ' . $this->oProject->team_id,
            'join' => array(
                __SQL . '_TrackTeamList AS TL' => 'TL.team_id = Track.id',
                __SQL . '_Auth AS A' => 'A.id = TL.user_id'
            ),
            'limit' => '1'
        );
        return $this->findFirst($pre);
    }

    /**
     * Récupere la liste des membres de la team du projet
     * @return stdClass
     * @throws Exception
     */
    public function getTeamList(){
        if (is_null($this->projectSlug)) {
            throw new Exception('Set project slug before');
        }
        $this->setTable('TrackTeam');
        $pre = array(
            'fields' =>
                '`leader_id`, `tag`, `Track`.`name` AS `team_name`, `create_time`,' .  // TrackTeam
                '`TL`.`id`, team_id, `TL`.`user_id` AS `user_id`, `join_date`,' .  // TrackTeamList
                '`user`, `group`', // Auth
            'conditions' => 'Track.id = ' . $this->oProject->team_id,
            'join' => array(
                __SQL . '_TrackTeamList AS TL' => 'TL.team_id = Track.id',
                __SQL . '_Auth AS A' => 'A.id = TL.user_id'
            )
        );
        return $this->find($pre);
    }



/**
  _______ _                _ _
 |__   __(_)              | (_)
    | |   _ _ __ ___   ___| |_ _ __   ___
    | |  | | '_ ` _ \ / _ \ | | '_ \ / _ \
    | |  | | | | | | |  __/ | | | | |  __/
    |_|  |_|_| |_| |_|\___|_|_|_| |_|\___|
 ******************************************/

    public function getTimeline(){
        if (is_null($this->projectSlug)) {
            throw new Exception('Set project slug before');
        }
        $this->setTable('TrackTimeline');
        $pre = array(
            'conditions' => array('project_id' => $this->getProjectId()),
            'join' => array(
                __SQL . '_Auth AS A' => 'A.id = user_id'
            ),
            'order' => 'Track.id DESC',
            'limit' => '100'
        );
        return $this->find($pre);
    }

}