<?php
Class SupportModel extends Model {
    private $discution;
    public function install() {
        $this->query("CREATE TABLE IF NOT EXISTS `" . __SQL . "_SupportMessage` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `status` enum('pending','finish','spam') NOT NULL,
            `toid` int(11) NOT NULL DEFAULT '0',
            `type` enum('msg','resp') NOT NULL,
            `passcode` varchar(100) NOT NULL DEFAULT '',
            `object` varchar(255) NOT NULL DEFAULT '',
            `name` varchar(255) NOT NULL,
            `mail` varchar(255) NOT NULL,
            `ip` varchar(255) NOT NULL,
            `message` text NOT NULL,
            `time` int(11) NOT NULL DEFAULT '0',
            `lastmessage` int(11) NOT NULL DEFAULT '0',
            `staff` enum('n','y') NOT NULL DEFAULT 'n',
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    }

    private function getUniqid(){
        $md5 = md5(uniqid('', true));
        return substr($md5, 0, 8 ) . '-' .
            substr($md5, 8, 4) . '-' .
            substr($md5, 12, 4) . '-' .
            substr($md5, 16, 4) . '-' .
            substr($md5, 20, 12);
    }

    public function addMessage($object, $name, $mail, $message) {
        $this->setTable('SupportMessage');
        $data = new stdClass();
        $data->object = $object;
        $data->name = $name;
        $data->mail = $mail;
        $data->message = $message;
        $data->lastmessage = $data->time = time();
        $data->type = 'msg';
        $data->ip = Securite::ipX();
        $data->passcode = $this->getUniqid();
        if ($this->save($data)){
            return array('id' => $this->getLastInsertId(), 'passcode' => $data->passcode);
        }
    }

    public function addReponse($toid, $name, $mail, $message, $isStaff = false) {
        if ($this->discution) {
            $toid = (int) $toid;
            $this->setTable('SupportMessage');
            $data = new stdClass();
            $data->toid = $toid;
            $data->name = $name;
            $data->mail = $mail;
            $data->message = $message;
            $data->time = time();
            $data->type = 'resp';
            $data->ip = Securite::ipX();
            $data->passcode = '';
            $data->staff = ($isStaff) ? 'y' : 'n';

            $this->discution->lastmessage = time();
            $this->save($this->discution);
            if ($this->save($data)){

                $getListMail = array(
                    'group' => 'mail',
                    'conditions' => 'id = ' . $toid . ' OR toid = ' . $toid
                );
                $resp = $this->find($getListMail);
                for($i = 0;$i<count($resp);$i++) {
                    $mail = new Mail(
                        $resp[$i]->mail,
                        '[Minetraxx Radio][support] R&eacute;ponse re&ccedil;u',
                        "Bonjour, une nouvelle r&eacute;ponse vient d'&ecirc;tre post&eacute; sur la discution
                        \"<a href=\"'.Router::url('support/id:'.$this->discution->id.'/passcode:' . $this->discution->passcode).'\">" . $this->discution->object . '</a>" ' . PHP_EOL  . PHP_EOL .
                        Router::url('support/id:'.$this->discution->id.'/passcode:' . $this->discution->passcode)
                    );
                    $mail->sendMail();
                }
                return true;
            }

        }
    }

    /**
     * Recherche une discution
     * @param $id
     * @param $passcode
     * @return stdClass
     */
    public function getDiscution($id, $passcode){
        $this->setTable('SupportMessage');
        $id = (int) $id;
        $this->discution = $this->findFirst(array('conditions' => array('id' => $id, 'passcode' => $passcode)));
        if ($this->discution) {
            return $this->discution;
        }
    }

    /**
     * Recupere la discution, au préalable, il faut appelé  $this->getDiscution($id, $passcode)
     * @param $page
     * @return bool|stdClass
     */
    public function getListMessage($page) {
        $this->setTable('SupportMessage');
        $page = (int) $page;
        if (!$this->discution) {
            return false;
        }
        $st = (($page-1) * 30);
        return $this->find(array(
            'fields' => 'message, mail, name, time, staff',
            'conditions' => array('toid' => $this->discution->id, 'type' => 'resp'),
            'limit' => $st.', 30'));
    }


    public function setStatus($status){
        $this->setTable('SupportMessage');
        if (!$this->discution or ($status != 'spam' && $status != 'pending' && $status != 'finish')) {
            return false;
        }

        $this->discution->status = $status;
        return $this->save($this->discution);
    }

    /**
     * Recupère la liste des discution ouverte selon leur status
     * @param $page
     * @param string $status
     * @param bool $withRespon
     * @return array
     */
    public function getListDiscusion($page, $status = 'pending', $withRespon = true){
        $this->setTable('SupportMessage');
        $page = (int) $page;
        $st = (($page-1) * 50);
        $withRespon = ($withRespon) ? '' : " AND `lastmessage` = `time`";
        $query = array(
            'fields' => 'id, name, object, time, passcode',
            'conditions' => 'status LIKE \''.$status.'\' AND type LIKE \'msg\' ' . $withRespon,
            'limit' => $st.', 50'
        );
        return array('list' => $this->find($query), 'count' => $this->count($query['conditions']));
    }


}