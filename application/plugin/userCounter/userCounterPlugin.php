<?php
Class userCounterPlugin extends PluginManager{
    private $memory;

    public function onEnabled() {
        if (class_exists('memcache')){
            $this->memory = new Memcache;
            $this->memory->connect('127.0.0.1', 11211);
            $whoisIsIn = $this->memory->get(__SQL . DB_DATABASE . '_whoisIsIn');
            if (!$whoisIsIn){$whoisIsIn = array();}
            $whoisIsIn[Securite::ipX()] = time();
            $down = time()-(60*5);
            foreach($whoisIsIn AS $ip => $time) {
                if ($time < $down) {
                    unset($whoisIsIn[$ip]);
                }
            }
            $this->memory->set(__SQL . DB_DATABASE . '_whoisIsIn', $whoisIsIn, MEMCACHE_COMPRESSED, 300);
        }
    }

    public function onGetCountOnlineUser(){
        if (class_exists('memcache')){
            $this->memory = new Memcache;
            $this->memory->connect('127.0.0.1', 11211);
            $whoisIsIn = $this->memory->get(__SQL . DB_DATABASE . '_whoisIsIn');
            $nb = count($whoisIsIn);
            echo ($nb>1) ? $nb . ' visiteurs en ligne' : $nb . ' visiteur en ligne';
        }
    }

}