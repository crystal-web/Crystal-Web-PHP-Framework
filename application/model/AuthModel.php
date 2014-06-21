<?php
Class AuthModel extends Model {
    function checkPassword($user, $password, $aes = true, $setCookie = false) {
        if ($aes) {
            $session    = Session::getInstance();
            $user       = AesCtr::decrypt($user, $session->getToken(), 256);
            $password   = AesCtr::decrypt($password, $session->getToken(), 256);
        }

        if ($setCookie) {
            $cookie = AesCtr::encrypt(json_encode(array('user' => $user, 'password' => $password)), magicword, 256);
        }

        $user           = truncatestr(clean($user, 'slug'), 16, false, '');
        $password       = $this->makePassword($password);

        $p = array('fields' => '`id`, `user`, `mail`, `group`', 'conditions' => array('user' => $user, 'password' => $password));
        $isOk = $this->findFirst($p);
        if ($setCookie) {
            setcookie('oauth', $cookie, time() + (3600*60*24*30));
        }
        return $isOk;
    }

    function makePassword($password){
        return md5($password . magicword);
    }
}