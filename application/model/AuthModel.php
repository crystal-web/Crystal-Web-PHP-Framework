<?php
class AuthModel extends Model {

    public function install() {
        $this->query("CREATE TABLE IF NOT EXISTS `".__SQL."_Auth` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `username` varchar(255) NOT NULL,
            `password` varchar(255) NOT NULL,
            `rank` enum('Membre','Moderateur','Administrateur') NOT NULL DEFAULT 'Membre',
            `ip` varchar(40) NOT NULL DEFAULT '127.0.0.1',
            `lastlogin` bigint(20) DEFAULT '0',
            `email` varchar(255) DEFAULT 'your@email.com',
            PRIMARY KEY (`id`),
            UNIQUE KEY `username` (`username`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;");

        $this->query("CREATE TABLE IF NOT EXISTS `".__SQL."_AuthForgotQuery` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `uid` int(11) NOT NULL,
            `hash` varchar(32) NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;");
    }



    private function makePasswordAuthme($password, $salt = false){
        $salt = ($salt) ? $salt : randCar(16);
        return '$SHA$' . $salt . '$' . hash('sha256',hash('sha256', $password).$salt);
    }

    function checkPassword($user, $password, $aes = true, $setCookie = false) {
        if ($aes) {
            $session    = Session::getInstance();
            $user       = AesCtr::decrypt($user, $session->getToken(), 256);
            $password   = AesCtr::decrypt($password, $session->getToken(), 256);
        }

        $user = trim($user);
        $password = trim($password);

        $user           = truncatestr(clean($user, 'slug'), 16, false, '');
        //$password       = $this->makePassword($password);

        $p = array('fields' => '`id`, `username`, `password` AS passscript, `email` AS mail, `rank`', 'conditions' => array('username' => $user));
        $isOk = $this->findFirst($p);

        if ($isOk) {
            $parts = explode('$',$isOk->passscript);
            $salt = $parts[2];

            return ($isOk->passscript == $this->makePassword($password, $salt)) ? $isOk : false;
        }
    }


    private function makePassword($password, $salt = false){
        $salt = ($salt) ? $salt : randCar(16);
        return '$SHA$' . $salt . '$' . hash('sha256',hash('sha256', $password).$salt);
    }

    public function changePassword($uid, $password){
        $this->setTable('Auth');
        $resp = $this->findFirst(array(
            'fields' => 'password, id',
            'conditions' => array('id'=> $uid)
        ));

        $password = $this->makePasswordAuthme($password);
        $resp->password = $password;
        return $this->save($resp);
    }


    public function getUserById($uid) {
        $this->setTable('Auth');
        $uid = (int) $uid;
        $pre = array('id' => $uid);
        return $this->findFirst(array(
            'fields' => 'username, id, rank',
            'conditions' => $pre
        ));
    }

    public function updateUid($uid, $name, $rank) {
        $data = new stdClass();
        $data->id = (int) $uid;

        $this->setTable('Auth');
        $data->name = $name;
        $data->rank = $rank;
        $this->save($data);
    }

    public function getGroupList(){
        $this->setTable('Auth');
        return $this->findFirst(array(
            'fields' => 'rank',
            'group' => 'rank',
            'order' => 'rank DESC'
        ));
    }

    public function search($username, $strict = false){
        if ($strict) {
            $search = "'" . $username . "'";
        } else {
            $search = "'%" . $username . "%'";
        }

        $this->setTable('Auth');
        return $this->find(array(
                'fields' => 'username, id, rank',
                'conditions' => 'username LIKE ' . $search
            )
        );
    }

    public function searchByMail($email, $strict = false){
        $search = ($strict) ? "'".$email."'" : "'"%".$email."%"'";

        $this->setTable('Auth');
        return $this->find(array(
                'fields' => 'username, id, rank',
                'conditions' => 'email LIKE ' . $search
            )
        );
    }

    public function addUser($login, $clearpassword, $mail, $group = false) {
        $data = new stdClass();
        $data->username = $login;
        $data->password  = $this->makePassword($clearpassword);
        $data->email = $mail;
        if ($group) {
            $data->rank = $group;
        }
        $this->setTable('Auth');
        return $this->save($data);
    }

    public function getHashQuery($uid)  {
        $this->setTable('AuthForgotQuery');
        $userQuery = $this->findFirst(array('conditions' => 'uid = ' . $uid));
        if (!$userQuery) {
            return false;
        }

        return  $userQuery;
    }

    public function findHash($hash, $uid) {
        $this->setTable('AuthForgotQuery');
        $userQuery = $this->findFirst(array('conditions' => 'hash = "' . $hash . '" AND uid = '  . $uid));
        if (!$userQuery) {
            return false;
        }

        return  $userQuery;
    }

    public function delHash($id){
        $this->setTable('AuthForgotQuery');
        return $this->delete($id);
    }

    public function genHashQuery($uid){
        $this->setTable('AuthForgotQuery');

        $userQuery = $this->getHashQuery($uid);

        if (!$userQuery) {
            $md5 = md5(uniqid('', true));
            $hash = substr($md5, 0, 8 ) . '-' . substr($md5, 8, 4) . '-' . substr($md5, 12, 4) . '-' . substr($md5, 16, 4) . '-' . substr($md5, 20, 12);

            $userQuery = new stdClass();
            $userQuery->uid = $uid;
            $userQuery->hash = $hash;
            $this->save($userQuery);
        }
        return $userQuery->hash;
    }
}
