<?php
Class SysUser extends Session {
    public function setUser($user){
        $this->write('sysuser', $user);
    }

    public function isPowerUser() {
        return ($this->read('sysuser')) ? true : false;
    }

    public function getUser(){
        return $this->read('sysuser');
    }
}