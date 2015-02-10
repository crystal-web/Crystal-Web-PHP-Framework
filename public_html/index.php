<?php
session_start ();
// provoque une latence mais accèlère l'affichge
// ob_start("ob_gzhandler");

// Developer mode ?
define ( '__DEV_MODE', 1 );

/***************************************/
/* DOT NOT EDIT AFTER THIS LINE PLEASE */
/***************************************/

// Default define
define ( '__START_MICROTIME', -microtime(true));
// Directory separator
define ( 'DS', DIRECTORY_SEPARATOR );
// SSL ou pas
define ( '__HTTP', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http');
// Adresse du site
define ('__CW_PATH', __HTTP . '://' . $_SERVER['SERVER_NAME']);
// define the site path
define ( '__SITE_PATH', realpath ( dirname ( __FILE__ ) ) );
// define the public folder
define ( '__PUBLIC_PATH', dirname ( __SITE_PATH ) . DS . 'public_html' );
// define the application path
define ( '__APP_PATH', dirname ( __SITE_PATH ) . DS . 'application' );
// define the absolute path
define ( '__ABS_PATH', dirname ( __SITE_PATH ) );
// Timestamp de la requete
define ( '__REQUEST_TIME', (isSet($_SERVER['REQUEST_TIME']))  ? $_SERVER['REQUEST_TIME'] : time());
// Page actuel
define ( '__PAGE', $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
// Test if is ajax request
$isAjax = false;
if( isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $isAjax = true;
}
define('__ISAJAX', $isAjax);
// Init SERVER_NAME for cli usage
$_SERVER['SERVER_NAME'] = (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : NULL;

/* TODO Probably outdated */ 
define ( '__LOADER', 'browser' );

/**
 * Enregistrement des exceptions
 */
set_exception_handler(function ($exception) {
   // Logs anonymous 
    $logs = function ($fileName, $line, $max = 1000) {
        if (!file_exists($fileName)){
            $file = fopen($fileName, 'w') or die("Can't open file: " . $fileName . '<br>Please chmod "cache" directory');
            fclose($file);
        }
        // Remove Empty Spaces
        $file = array_filter(array_map("trim", file($fileName)));
        
        // Make Sure you always have maximum number of lines
        $file = array_slice($file, 0, $max);
        
        // Remove any extra line 
        count($file) >= $max and array_shift($file);
        
        // Add new Line
        array_push($file, $line);
        
        // Save Result
        file_put_contents($fileName, implode(PHP_EOL, array_filter($file)));
    };
    $logs(__APP_PATH . DS . 'cache' . DS . 'logs.txt', date('Y-m-d H:i') . ' code::' . $exception->getCode() . ' ' . $exception->getFile() . '::' . $exception->getLine() .' ' . $exception->getMessage());
   // file_put_contents($file, , FILE_APPEND);
});

/**
 * Enregistrement des erreurs
 */
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    if (Crystal::Web()->noError === true) {
        return;
    }
    
    // On définit le type de l'erreur
    switch ($errno) {
        case E_USER_ERROR :
            $type = "Fatal:";
            break;
        case E_USER_WARNING :
            $type = "Erreur:";
            break;
        case E_USER_NOTICE :
            $type = "Warning:";
            break;
        case E_ERROR :
            $type = "Fatal";
            break;
        case E_WARNING :
            $type = "Erreur:";
            break;
        case E_NOTICE :
            $type = "Warning:";
            break;
        default :
            $type = "Inconnu:";
            break;
    }
        
    // On définit l'erreur.
    $erreur = "Type : " . $type . "
    Message d'erreur : [" . $errno . "]" . $errstr . "
    Ligne : " . $errline . "
    Fichier : " . $errfile;
        
    /* Pour passer les valeurs des différents tableaux, nous utilisons la fonction serialize()
    Le rapport d'erreur contient le type de l'erreur, la date, l'ip, et les tableaux. */
        
    $info = date ( "d/m/Y H:i:s", time () ) . " : GET:" . print_r ( $_GET, true ) . "POST:" . print_r ( $_POST, true ) . "SERVER:" . print_r ( $_SERVER, true ) . "COOKIE:" . (isset ( $_COOKIE ) ? print_r ( $_COOKIE, true ) : "Undefined") . "SESSION:" . (isset ( $_SESSION ) ? print_r ( $_SESSION, true ) : "Undefined");
    //"LOG:" . print_r(Log::console(), true);

    $error_array ['date'] = time ();
    $error_array ['more'] = $info;
    $error_array ['type'] = $type;
    $error_array ['msg'] = "[" . $errno . "] " . $errstr;
    $error_array ['errline'] = $errline;
    $error_array ['errfile'] = $errfile;
    
    // Lecture du cache
    $fileName = __APP_PATH . DS . 'cache' . DS . __SQL . '_erreur_alerte.cache';
    if (!file_exists($fileName)){
        $file = fopen($fileName, 'w') or die("Can't open file: " . $fileName . '<br>Please chmod "cache" directory');
        fclose($file);
    }
    $error_cache = unserialize(file_get_contents($fileName));
    $error_cache [md5( $erreur )] = $error_array;
    
    // Ecriture du cache
    file_put_contents($fileName, serialize($error_cache));
    
    if (__DEV_MODE) {
        echo ("<div class=\"well\">
            <p><strong>Type :</strong> ".$error_array ['type']." " . $error_array ['msg'] . "</p>
            <p><strong>Ligne :</strong> ".$error_array ['errline']." ".$error_array ['errfile']."</p>
        </div>");
    }
});

// Every all in includes file
if ($handle = opendir(__SITE_PATH . DS . 'includes')) {
    while (false !== ($entry = readdir($handle))) {
        if (preg_match('#.php$#', $entry)) {
            require_once __SITE_PATH . DS . 'includes' . DS . $entry;
        }
    }
    closedir($handle);
} else {
    die('Can\'t access to includes folder in public path');
}

// Report pour les erreurs
$err = (__DEV_MODE) ? error_reporting ( - 1 ) : error_reporting ( 0 );
//  Function library
require_once __APP_PATH . DS . 'function' . DS . 'function.inc.php';
// Router systeme obligatoirement AVANT le reste ?
require_once __APP_PATH . DS . 'framework' . DS . 'Router.php';

// Every all in framework file
if ($handle = opendir(__APP_PATH . DS . 'framework')) { 
    /* This is the correct way to loop over the directory. */
    while (false !== ($entry = readdir($handle))) {
        if (preg_match('#.php$#', $entry)) { 
            require_once __APP_PATH . DS . 'framework' . DS . $entry;
        }
    }
    closedir($handle);
} else {
    die('Can\'t access to framework folder in application path');
}

class Crystal {
    /**
    * @access private
    * @static
    */
    private static $_instance = null;
    
    private $_functions = array();
    private $_vars = array();
    
    public function Crystal() {
        header('X-Powered-By: Crystal-Web.org version:' . $this->version . '/' . $this->branch);
        
        // Patch $_SERVER for CLI usage
        $_SERVER['SERVER_NAME'] = (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : '127.0.0.1';
        $_SERVER['REQUEST_URI'] = (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : '/';
        $_SERVER['HTTP_USER_AGENT'] = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : 'console';
        $_SERVER['SERVER_NAME'] = (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : NULL;
    }
    
    /**
    * Méthode qui crée l'unique instance de la classe
    * si elle n'existe pas encore puis la retourne.
    *
    * @param void
    * @return Page
    */
    public static function Web() {
        if(is_null(self::$_instance)) {
            self::$_instance = new Crystal();
        }
        return self::$_instance;
    }
    
    /**
     * Global setter
     * @param $name var name
     * @param $data var value
     */
    function __set($name,$data) {
        if(is_callable($data)) {$this->_functions[$name] = $data;}
        else {$this->_vars[$name] = $data;}
    }
    
    /**
     * Global getter
     * @param $name var name
     */
    function __get($name) {
        if(isset($this->_vars[$name])) {return $this->_vars[$name];}
    }
    
    /**
     * Call method Hack
     * @param $method name
     * @param $$args arguments
     */
    function __call($method,$args) {
        if(isset($this->_functions[$method])) {call_user_func_array($this->_functions[$method],$args);}
        else {
            throw new Exception("Crystal::Web Error Processing Request Call " . $method, 1);
        }
    }
    
    public function run() {
        // Lancement de l'application enjoy
        try {
            new Dispatcher ( );
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }
}

Crystal::Web()->addToRouter = function($redir, $url) {
    $req = Request::getInstance();
    $c = new Cache('router');
    $router = $c->getCache();
    $router[$req->getController()][] = array($redir, $url);
    $c->setCache($router);
};

Crystal::Web()->search = function($method) {
    switch($method) {
        case 'get':
            echo 'GET';
        break;
        case 'put':
            
        break;
        case 'post':
            
        break;
        case 'delete':
            
        break;
    }
};

// TODO Add CSS Injection and link to WatchWorks
Crystal::Web()->getDiagnosticsTools = function(){
    $request = Request::getInstance();
    $model = new cwModel();
    $isMobile = is_mobile();
    $html = new Html();

    $memori = function ($size) {
        $unit=array('b','kb','mb','gb','tb','pb');
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    };
    $rqt = (isset($_SERVER['REQUEST_TIME_FLOAT'])) ? $_SERVER['REQUEST_TIME_FLOAT'] : $_SERVER['REQUEST_TIME'];

    $html->comment('Script tools')
        ->script(array('type' => 'application/javascript'),
            'jQuery(document).on("click", \'#getModelQuery\', function(){' .
            'bootbox.alert(jQuery(\'#queryInfo\').html());' .
            '});' .
            'jQuery(document).on("click", \'#getLogs\', function(){' .
            'bootbox.alert(jQuery(\'#logsInfo\').html());' .
            '});')->end()
        ->comment('End of script tools');

//<span class="label label-important"><i class="icon-signal"></i></span>
    $html->comment('Text tools')
        ->div(array('class' => 'row-fluid margin-bottom-10 margin-top-10'))
        ->div(array('style' => 'text-align:center'))
        ->span(array('class' => 'badge label-important'))
        ->i(array('class' => 'fa fa-cogs', 'title' => 'Developement tools:'))->end()
        ->end()->span(' ')->end()

        ->span(array('id' => 'getLogs'), 'Logs: ('.count(Log::getLog()).')')->end()
        ->span(array('class' => 'separator'), ' ')->end()

        ->span(array('class' => 'badge label-important'))
        ->i(array('class' => 'fa fa-info-circle'))->end()
        ->end()->span(' ')->end()
        ->span(array('id' => 'getModelQuery'), 'Requ&ecirc;te: ' . $model->getNbQuery() . ' ('.$memori($model::getMemoryUsage()).')')->end()
        ->span(array('class' => 'separator'), ' ')->end()

        ->span(array('class' => 'badge label-important'))
        ->i(array('class' => 'fa fa-fighter-jet'))->end()
        ->end()->span(' ')->end()
        ->span('G&eacute;n&eacute;ration en ' . round(__START_MICROTIME+microtime(true), 3) . ' sec')->end()
        ->span(array('class' => 'separator'), ' ')->end()

        ->span(array('class' => 'badge label-important'))
        ->i(array('class' => 'fa fa-retweet'))->end()
        ->end()->span(' ')->end()
        ->span('M&eacute;moire: ' . $memori(memory_get_usage()))->end()
        ->span(array('class' => 'separator'), ' ')->end()

        ->span(array('class' => 'badge label-important'))
        ->i(array('class' => 'fa fa-folder-open'))->end()
        ->end()->span(' ')->end()
        ->span(exec("find ../ -type f -name '*.php' -exec wc -l {} \; | awk '{sum+=$1}END{print sum}'") . ' lignes de code ')->end();

    if (time()-$rqt > 1) {
        $html->span(array('class' => 'separator'), ' ')->end()
            ->span(array('class' => 'badge label-important'))
            ->i(array('class' => 'fa fa-cloud-upload'))->end()
            ->end()->span(' ')->end()
            ->span(
                array(
                    'class' => 'color-red',
                    'title' => 'La connexion est trop lente ou la page est trop lourde'
                ), 'Envois de la page au client: ' . (time()-$rqt))->end();
    } else {
        $html->span(array('class' => 'separator'), ' ')->end()
            ->span(array('class' => 'badge label-important'))
            ->i(array('class' => 'fa fa-cloud-upload'))->end()
            ->end()->span(' ')->end()
            ->span('Envois de la page au client: ' . (time()-$rqt))->end();
    }

    $html->end()
        ->end()
        ->comment('End of text tools');

    $html->comment('Query info')
        ->div(array('id' => 'queryInfo', 'style' => 'display: none'))
        ->strong('Tyde d\'affichage: ')->end()
        ->span($isMobile['statut'])->end()
        ->br()
        ->strong('Controller: ')->end()
        ->span($request->getController() . '.' . $request->getAction())->end()
        ->br();

    $rules = NULL;
    if ($request->params) {
        $html->strong('Params: ')->end();
        foreach($request->params as $key => $value) {
            if (!is_numeric($key)) {
                $html->strong($key . ':')->end()
                    ->span('&nbsp;' . $value . '&nbsp;&nbsp;')->end();
            }
        }
        $html->br();
    }

    $html->table(array('class' => 'zebra-striped bordered-table condensed-table'));
    $classIk = true;
    foreach ($model->getLog() as $key => $value) {
        $html->tr();
        if ($value['type'] != 'INFO' OR preg_match('# \*#', $value['type'])) {
            $html->td(array('class' => 'grd-orangered color-white ' . strtolower($value['type']), 'style' => 'text-shadow: black 0.1em 0.1em 0.2em'), $value['type'] . '::' . $value['message'])->end();
        } else {
            $html->td($value['message'])->end();
        }
        $html->end();
    }
    $html->end();
    $html->end()
        ->comment('End of query info');

    $html->comment('Logs list')
        ->div(array('id' => 'logsInfo', 'style' => 'display: none'))
        ->div(Log::console())->end()
        ->end()
        ->comment('End of logs list');

    echo $html;
};

// Hash string for password
try { define ('magicword', getMagik()); }
// Hash exception
catch (Exception $e) { die($e->getMessage()); }

/* Truc de dingue ;-) */
/*ob_start(function($buffer) {
    $search = array(
        '/\>[^\S ]+/s',     // 1. strip whitespaces after tags, except space
        '/[^\S ]+\</s',     // 2. strip whitespaces before tags, except space
        '/(\s)+/s',         // 3. shorten multiple whitespace sequences
        '/<!--.*?-->+/s',   // 4. Sorry for the JS developer
    );
    $replace = array(
        '>',    // 1
        '<',    // 2
        '\\1',  // 3
        ''      // 4
    );
    $buffer = preg_replace($search, $replace, $buffer);
    return $buffer;
});//*/

Crystal::Web()->run();
