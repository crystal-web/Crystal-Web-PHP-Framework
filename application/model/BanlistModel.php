<?php
Class BanlistModel extends Model {
    public function getListLogin($loginmember) {
        $prepare = array(
            'fields' => 'nick, adminnick, banfrom, banto, reason, status',
            'conditions' => array('nick' => $loginmember),
            'order' => 'banfrom DESC'
        );
        return $this->find($prepare);
    }
}
