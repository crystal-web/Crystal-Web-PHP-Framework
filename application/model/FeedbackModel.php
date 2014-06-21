<?php
class FeedbackModel extends Model {
    public function install() {
        $this->query("CREATE TABLE IF NOT EXISTS `" . __SQL . "_Feedback` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `cat` enum('bug','feature','question') NOT NULL DEFAULT 'question',
            `time` int(11) NOT NULL DEFAULT '0',
            `mail` varchar(255) NOT NULL,
            `ip` varchar(100) NOT NULL,
            `subject` varchar(60) NOT NULL,
            `description` text NOT NULL,
            `reply` int(11) NOT NULL DEFAULT '0',
            `spam` int(11) NOT NULL DEFAULT '0',
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");

        $this->query("CREATE TABLE IF NOT EXISTS `" . __SQL . "_FeedbackReply` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `pid` int(11) NOT NULL,
            `spam` int(11) NOT NULL,
            `ip` varchar(100) NOT NULL,
            `mail` varchar(255) NOT NULL,
            `description` text NOT NULL,
            `time` int(11) NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");

        $this->query("CREATE TABLE IF NOT EXISTS `" . __SQL . "_FeedbackSpam` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `pid` int(11) NOT NULL,
            `rid` int(11) NOT NULL,
            `ip` varchar(255) NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
    }

    public function replyTo($id, $description, $mail){
        $quest = $this->getId($id);
        if ($quest) {
            $this->setTable('FeedbackReply');
            $id = (int) $id;
            $data = new stdClass();
            $data->pid = $id;
            $data->description = $description;
            $data->mail = $mail;
            $data->time = time();
            $data->ip = Securite::ipX();
            $this->save($data);
            $this->setTable('Feedback');
            $quest->reply += 1;
            $this->save($quest);
            return $quest->reply;
        }
    }

    public function getId($id, $reply = false) {
        if ($reply){
            $this->setTable('FeedbackReply');
        }
        $pre = array(
            'conditions' => array('id' => $id),
            'limit' => '1'
        );
        $re = $this->findFirst($pre);
        $this->setTable('Feedback');
        return $re;
    }

    public function getList($cat, $page) {
        $cat = strtolower($cat);
        switch($cat){
            case 'bug':
            case 'feature':
            case 'question':
                break;
            default:
                throw new Exception('Cat&eacute;gorie incorrect');
                break;
        }
        $start = ( ($page-1) * 10 );
        $pre = array(
            'fields' => 'Feedback.id, Feedback.cat, Feedback.time, Feedback.mail, Feedback.ip, Feedback.subject, Feedback.description, Feedback.reply, Feedback.spam',
            'conditions' => array('cat' => $cat),
            'limit' => $start . ', 10',
            'order' => '`spam` ASC, `time` DESC '
        );
        $ret = $this->find($pre);
        for($i=0;$i<count($ret);$i++) {
            $ret[$i]->replylist = $this->getReply($ret[$i]->id);
        }
        return $ret;
    }

    public function getReply($pid, $page = 1, $nbReturn = 5) {
        $pid = (int) $pid;
        $page = (int) $page;
            $page--;
        $nbReturn = (int) $nbReturn;

        $this->setTable('FeedbackReply');
        $reply = $this->find(array(
            'conditions' => array(
                'pid' => $pid
            ),
            'limit' => ($page*$nbReturn) . ', ' . $nbReturn,
            'order' => '`spam` ASC, `id` DESC'
        ));

        $this->setTable('Feedback');
        return $reply;
    }

    public function countReply($pid) {
        $pid = (int) $pid;

        $this->setTable('FeedbackReply');
        $reply = $this->count(array('pid' => $pid));

        $this->setTable('Feedback');
        return $reply;
    }


    public function setRequest($cat, $subject, $description, $mail){
        $cat = strtolower($cat);
        switch($cat){
            case 'bug':
            case 'feature':
            case 'question':
            break;
            default:
                throw new Exception('Cat&eacute;gorie incorrect');
            break;
        }

        $data = new stdClass();
        $data->cat = $cat;
        $data->ip = Securite::ipX();
        $data->mail = $mail;
        $data->time = time();
        $data->subject = $subject;
        $data->description = $description;
        return $this->save($data);
    }

    /** SPAMMING METHOD START **/
    public function isSpamReply($id){
        $id = (int) $id;
        $isReply = $this->getId($id, /*is reply */ true);

        if ($isReply) {
            $this->setTable('FeedbackSpam');
            // On recherche si c'est un marquage ou l'inverse
            $pre = array( 'conditions' => array( 'rid' => $id, 'ip' => Securite::ipX() ) );
            $isMarqued = $this->findFirst($pre);
            if ($isMarqued) {
                $this->delete($isMarqued->id);
                $isReply->spam -= 1; // TODO en test
            } else {
                $data = new stdClass();
                $data->rid = $id;
                $data->ip = Securite::ipX();
                if ($this->save($data)) {
                    $isReply->spam += 1; // TODO en test
                }
            }

            $this->setTable('FeedbackReply');
            $this->save($isReply);

            return $isReply->spam;
        }
    }

    public function isSpamPost($id){
        $id = (int) $id;
        $isPost = $this->getId($id, false);
        if ($isPost) {
            $this->setTable('FeedbackSpam');
            // On recherche si c'est un marquage ou l'inverse
            $pre = array( 'conditions' => array( 'pid' => $id, 'ip' => Securite::ipX() ) );
            $isMarqued = $this->findFirst($pre);
            if ($isMarqued) {
                $this->delete($isMarqued->id);
                $isPost->spam -= 1; // TODO en test
            } else {
                $data = new stdClass();
                $data->pid = $id;
                $data->ip = Securite::ipX();
                if ($this->save($data)) {
                    $isPost->spam += 1; // TODO en test
                }
            }

            $this->setTable('Feedback');
            $this->save($isPost);
            return $isPost->spam;
        }
    }
    /** SPAMMING METHOD END **/
}