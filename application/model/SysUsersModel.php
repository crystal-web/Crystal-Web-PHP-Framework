<?php
Class SysUsersModel extends Model {
    public function ctrlPwd($pwd){
        $secu = Session::getInstance();
        $pwd = md5(AesCtr::decrypt($pwd, $secu->getToken(), 256) . magicword);

        $p = array('fields' => 'id, username', 'conditions' => array('password' => $pwd));
        return $this->findFirst($p);
    }
}