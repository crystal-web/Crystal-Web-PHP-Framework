<?php
/**
 * @author Christophe BUFFET <developpeur@crystal-web.org>
 * @license Creative Commons By
 * @license http://creativecommons.org/licenses/by-nd/3.0/
 */
if (!defined('__APP_PATH')) {
    echo '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You don\'t have permission to access this file on this server.</p></body></html>'; die;
}

class Template {

    /**
     * @var Template
     * @access private
     * @static
     */
    private static $_instance = null;
    private static $_isMobile = false;

    /**
     * Méthode qui crée l'unique instance de la classe
     * si elle n'existe pas encore puis la retourne.
     *
     * @param void
     * @return Template
     */
    public static function getInstance() {
        if(is_null(self::$_instance)) {
            self::$_instance = new Template();
        }
        return self::$_instance;
    }

    public function isMobile($boolean){
        self::$_isMobile = (bool) $boolean;
    }

    /*
    * @Variables array
    * @access private
    */
    private $vars = array ();

    /*
    * @Variables string
    * @access private
    */
    private $path = NULL;


    /**
     * Indique le path vers le dossier des templates
     *
     * @param string $path
     */
    public function setPath($path /* path to view */) {
        // UPDATE pour la portabilité
        $this->path = preg_replace('#/#', DIRECTORY_SEPARATOR, $path);
    }

    /**
     *
     * @set undefined vars
     * @param string $index
     * @param mixed $value
     * @return void
     */
    public function __set($index, $value) {
        $this->vars [$index] = $value;
    }

    /**
     * Action par défaut lorsque template est appelé comme un stdClass
     *
     * @param string $index
     * @return mixed parsed
     */
    public function __get($index) {
        $index = clean($index, 'str');
        return isSet($this->vars[$index]) ? $this->vars[$index] : false;
    }

    /**
     * Indique quel template est utilisé, on y inclus l'ensemble des variables défini
     *
     * @return void
     */
    function show($name, $return = false) {
        $name = preg_replace('#/#', DIRECTORY_SEPARATOR, $name);

        $path = $this->path . DS . $name . '.php';

        if (self::$_isMobile && file_exists($this->path . DS . $name . '.mobi.php')) {
            $path = $this->path . DS . $name . '.mobi.php';
        }


        if (file_exists ( $path ) == false) {
            throw new Exception ( 'Template not found in ' . $path );
            return false;
        }

        // Load variables
        foreach ( $this->vars as $key => $value ) {
            if (is_array ( $value )) {
                $$key = $value;
            } elseif (is_object ( $value )) {
                $$key = $value;
            } else {
                $$key = stripslashes ( $value );
            }
        }

        if (isset($_GET['debug']) && $_GET['debug'] == 'isdev') {
            debug($this->vars);
        }

        ob_start();
        include ($path);

        $content = ob_get_contents();
        ob_end_clean();

        if ($return) {
            return $content;
        }
        echo $content;
    }

    /**
     * Permet de récupérer les variables déclaré
     *
     * @return array
     */
    public function getVars() {
        return $this->vars;
    }
}