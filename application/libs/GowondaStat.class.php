<?php
Class GowondaApiClass {

    private $fullUrl;
    private $timeout;

    private $pageInCache;
    private $position               = -1;
    private $votes                  = -1;
    private $out                    = -1;

    public function __construct($fullUrl, $timeout = 3) {
        $this->fullUrl  = $fullUrl;
        $this->timeout  = (int) $timeout;
    }

    /**
     * Charge le contenu de la page de présentation et en retourne le contenu.
     * Si la page est déjà chargé, retourne le cache local
     */
    private function loadPage() {
        if (!$this->pageInCache) {
            $this->pageInCache = $this->file_get_contents_curl($this->fullUrl, $this->timeout);
        }

        return $this->pageInCache;
    }


    /**
     * Return the current position
     * @return int
     */
    public function getPosition(){
        if($this->position!=-1) {
            return $this->position;
        }

        $matches = array();
        if(!$code = $this->loadPage()){
            throw new Exception('GowondaApiClass::getPosition() ' . PHP_EOL . 'Impossible de récupérer la page, RPG-Paradize est probablement en maintenance ou surchargé', 300);
            return 0;
        }
        if(!preg_match('#<span class="classement">([0-9]+)<span class=\'classement_exposant#', $code, $matches)){
            throw new Exception('GowondaApiClass::getPosition() ' . PHP_EOL . 'Impossible de récupérer la position', 1);
            return 0;
        }

        return $this->position = $matches[1];
    }

    /**
     * Return total votes
     * @return int
     */
    public function getVotes(){
        if($this->votes!=-1) {
            return $this->votes;
        }

        $matches = array();
        if(!$code = $this->loadPage()){
            throw new Exception('GowondaApiClass::getVotes() ' . PHP_EOL . 'Impossible de récupérer la page, RPG-Paradize est probablement en maintenance ou surchargé', 300);
            return 0;
        }

        if(!preg_match('#Votes : <span class="classement">([0-9]+)</span>#', $code, $matches)){
            throw new Exception('GowondaApiClass::getVotes() ' . PHP_EOL . 'Impossible de récupérer le nombre de vote', 1);
            return 0;
        }

        return $this->votes = $matches[1];
    }

    /**
     * Return total out
     * @return int
     */
    public function getOut(){
        if($this->out!=-1)
            return $this->out;

        $matches = array();
        if(!$code = $this->loadPage()){
            throw new Exception('GowondaApiClass::getOut() ' . PHP_EOL . 'Impossible de récupérer la page, RPG-Paradize est probablement en maintenance ou surchargé', 300);
            return 0;
        }

        if(!preg_match('#Sorties : <span class="classement">([0-9]+)</span>#', $code, $matches)){
            throw new Exception('GowondaApiClass::getOut() ' . PHP_EOL . 'Impossible de récupérer le nombre de out', 1);
            return 0;
        }

        return $this->out = $matches[1];
    }


    /**
     * Best method to get page content
     */
    private function file_get_contents_curl($url, $timeout = 3) {
        if (!function_exists('curl_init')) {
            throw new Exception('GowondaApiClass::file_get_contents_curl(' . $url . ', ' . $timeout.') ' . PHP_EOL . 'cURL is not installed, please contact your administrator.');
        }

        $ch = curl_init($url);
        if (false === $ch) {
            return false;
        }
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        $useragent = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15';
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("User-Agent: " . $useragent));
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $data = curl_exec($ch);
        if (!$data) {
            throw new Exception('GowondaApiClass::file_get_contents_curl(' . $url . ', ' . $timeout.') ' . PHP_EOL . curl_error($ch), curl_errno($ch));
        }
        curl_close($ch);
        return $data;
    }
}

/**
 * Example
 *
try {
    $top = new GowondaApiClass('http://www.gowonda.com/serveur-aion-3686-Evolution---4.3.htm', 1);
    echo '<pre>' . PHP_EOL .
        'Position: ' . $top->getPosition() . PHP_EOL .
        'Total vote: ' . $top->getVotes() . PHP_EOL .
        'Total out: ' . $top->getOut() . PHP_EOL .
        '</pre>';
} catch (Exception $e) {
    die($e->getMessage());
}
 */