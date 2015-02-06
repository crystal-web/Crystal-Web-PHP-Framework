<?php
class Bootstrap {

    static public function panel($title, $body, $footer = false, $color = "default", $addClass = '', $riborn = false) {
        $w = Template::getInstance();
        $w->color = $color;
        $w->addClass = $addClass;
        $w->riborn = $riborn;
        $w->title = $title;
        $w->body = $body;
        $w->footer = $footer;
        return $w->show('_templates/panel', true);
    }

    /**
     * @param $data array($dt => array($dl) ou String
     * @param bool $horizontal
     */
    static public function dl($data, $horizontal = false) {
        $w = Template::getInstance();
        $w->data = $data;
        $w->horizontal = $horizontal;
        return $w->show('_templates/dl', true);
    }

    /**
     * @param array $list array( $url => $title ) si false == divider
     */
    static public function menuContextuel($list) {
        $w = Template::getInstance();
        $w->list = $list;
        return $w->show('_templates/menu-contextuel', true);
    }

    static public function callout($title, $body, $class = "info") {
        $w = Template::getInstance();
        $w->title = $title;
        $w->body = $body;
        $w->class = $class;
        return $w->show('_templates/callout', true);
    }

    /**
     * @param array $data array( "slugId" => array('title' => $title, 'body' => $body) )
     */
    static public function tabs($data) {
        $w = Template::getInstance();
        $w->list = $data;
        return $w->show('_templates/tabs', true);
    }


    static public function breadcrumb($data){
        $w = Template::getInstance();
        $w->data = $data;
        return $w->show('_templates/breadcrumb', true);
    }


    static public function thumbnails($arrayThumbnail, $espace = 4, $fancybox = false){
        $w = Template::getInstance();
        $w->block = $arrayThumbnail;
        $w->addClass = '';
        $w->fancybox = $fancybox;
        $w->espace = (is_numeric($espace) && $espace <= 16) ? $espace : 4;

        if ($fancybox) {
            $page = Page::getInstance();
            $page->setHeaderJs('https://apis.google.com/js/platform.js');

            $page->setHeaderCss("/assets/plugins/fancybox/source/jquery.fancybox.css?v=2.1.2");
            $page->setHeaderCss("/assets/plugins/fancybox/source/jquery.fancybox.css?v=2.1.3");
            $page->setHeaderCss("/assets/plugins/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=2.1.5");

            $page->setHeaderJs("/assets/plugins/fancybox/source/jquery.fancybox.js?v=2.1.3");
            $page->setHeaderJs("/assets/plugins/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=2.1.5");
            $page->setHeader('<script type="application/javascript">jQuery(".fancybox").fancybox({ \'transitionIn\' : \'none\', \'transitionOut\' : \'none\' });</script>');
        }
        return $w->show('_templates/thumbnails', true);
    }

    static public function thumbnail($picture, $footer = false, $pictureGrand = false /* FancyBox */){
        $w = Template::getInstance();
        $w->picture = $picture;
        $w->pictureGrand = $pictureGrand;
        $w->footer = $footer;
        return $w->show('_templates/thumbnail', true);
    }

    static public function accordeon($accordion){
        $w = Template::getInstance();
        $w->accordion = $accordion;
        return $w->show('_templates/accordeon', true);
    }

    static public function filedrop($destinationDir=null, $whitelist = false){
        Log::setLog("Destination dir: " . $destinationDir, 'bootstrap');
        if (is_null($destinationDir)){
            return filedrop(__APP_PATH . DS . 'cache' . DS . 'FileDrop', $whitelist);
        }
        $w = Template::getInstance();

        if (isset($_FILES['file'])) {
            Log::setLog("Detecte file drop", 'bootstrap');
            try {
                $files = new Upload($_FILES['file'], $whitelist, $addMimeTypeDirAndDate = true);
                $destinationDir = $destinationDir . DS . $files->getMimeType() . DS . date('Y-m-d');
                mkdir($destinationDir, 0777, true);
                if ($files->moveToDir($destinationDir)) {
                    $fichier = array(
                        'path' => $destinationDir,
                        'name' => $files->getFileName(),
                        'size' => $files->getFileSize(),
                        'mimetype' => $files->getMimeType(),
                        'ext' => $files->getFileExtention(),
                        'md5' => $files->getMd5(),
                        'error' => $files->existe(true)
                    );


                    self::set($fichier);

                    $page = Page::getInstance();
                    $page->setLayout('empty');
                    echo json_encode($fichier);
                    return;
                }
            } catch(Exception $e) {
                $page = Page::getInstance();
                $page->setLayout('empty');
                self::set(array('error' => true, 'message' => $e->getMessage() . ' ' . $e->getCode()));
                echo json_encode(array('error' => true, 'message' => $e->getMessage() . ' ' . $e->getCode()));
                return;
            }

        }
        return $w->show('_templates/filedrop', true);
    }

    static private $data = null;
    static public function set($data){
        self::$data = $data;
    }

    static public function get() {
        return self::$data;
    }
}




class Upload {
    const KB = 1024;
    const MB = 1048576;
    const GB = 1073741824;
    const TB = 1099511627776;

    private $files;
        /*
        [name] => MaBelleImage.jpg
        [type] => image/jpg
        [tmp_name] => chemin_complet_du_fichier_uploadé
        [error] => 0
        [size] => 1000
        [dst_name] => false
        */
    private $extBlacklist = array(
        # HTML may contain cookie-stealing JavaScript and web bugs
        'html', 'htm', 'js', 'jsb', 'mhtml', 'mht',
        # PHP scripts may execute arbitrary code on the server
        'php', 'phtml', 'php3', 'php4', 'php5', 'phps',
        # Other types that may be interpreted by some servers
        'shtml', 'jhtml', 'pl', 'py', 'cgi',
        # May contain harmful executables for Windows victims
        'exe', 'scr', 'dll', 'msi', 'vbs', 'bat', 'com', 'pif', 'cmd', 'vxd', 'cpl'
    );

    private $MimeTypeBlacklist = array(
        # HTML may contain cookie-stealing JavaScript and web bugs
        'text/html', 'text/javascript', 'text/x-javascript', 'application/x-shellscript',
        # PHP scripts may execute arbitrary code on the server
        'application/x-php', 'text/x-php',
        # Other types that may be interpreted by some servers
        'text/x-python', 'text/x-perl', 'text/x-bash', 'text/x-sh', 'text/x-csh', 'text/x-shellscript',
        # Client-side hazards on Internet Explorer
        'text/scriptlet', 'application/x-msdownload',
        # Windows metafile, client-side vulnerability on some systems
        'application/x-msmetafile',
        # A ZIP file may be a valid Java archive containing an applet which exploits the
        # same-origin policy to steal cookies
        'application/zip', 'application/x-opc+zip', 'application/msword', 'application/vnd.ms-powerpoint', 'application/vnd.msexcel',
    );

    public function __construct($files, $whitelist = false) {
        if (!isset($files['name'])) {
            throw new Exception('$files is not $_FILES["name"]');
        }

        switch ($this->files['error']){
            case 1: // UPLOAD_ERR_INI_SIZE
                throw new Exception ("Le fichier dépasse la limite autorisée par le serveur (fichier php.ini) !", 1);
                break;
            case 2: // UPLOAD_ERR_FORM_SIZE
                throw new Exception ("Le fichier dépasse la limite autorisée dans le formulaire HTML !", 2);
                break;
            case 3: // UPLOAD_ERR_PARTIAL
                throw new Exception ("L'envoi du fichier a été interrompu pendant le transfert !", 3);
                break;
            case 4: // UPLOAD_ERR_NO_FILE
                throw new Exception ("Le fichier que vous avez envoyé a une taille nulle !", 4);
                break;
        }

        $this->files = $files;

        if ($this->isBlacklisted($whitelist)) {
            throw new Exception('Fichier blacklister', 6);
        }

        /*
        Log::setLog(
            " File: " . $this->getFileName() .
            " Size:  " . $this->getFileSize() .
            " MD5: " . $this->getMd5() .
            " MimeType: " . $this->getMimeType(),
        'Upload');//*/

        if (strlen($this->files['tmp_name']) == 0) {
            throw new Exception('Pas de fichier', 5);
        }
    }

    public function isBlacklisted($whitelist = false) {
        if (array_search($this->getMimeType(), $this->MimeTypeBlacklist) !== false) {
            return true;
        } elseif (array_search($this->getFileExtention(), $this->extBlacklist) !== false) {
            return true;
        }

        if ($whitelist) {
            if (array_search($this->getFileExtention(), $whitelist) !== false) {
                return false;
            }
            return true;
        }
        return false;
    }

    public function getFileExtention() {
        $_ext = trim( strtolower( strrchr($this->files['name'], '.') ) , '.');
        Log::setLog('File extention is ' . $_ext, 'Upload');
        return $_ext;
    }

    public function getFileName(){
        return preg_replace("/[^a-z0-9\.]/", "", strtolower($this->files['name']));
    }

    public function getFileSize() {
        if (!file_exists($this->files['tmp_name'])) {
            return '0 B';
        }

        $bytes = sprintf('%u', filesize($this->files['tmp_name']));
        if ($bytes > 0) {
            $unit = intval(log($bytes, 1024));
            $units = array('B', 'KB', 'MB', 'GB');
            if (array_key_exists($unit, $units) === true) {
                return sprintf('%d %s', $bytes / pow(1024, $unit), $units[$unit]);
            }
        }

    return $bytes;
    }

    public function getMd5() {
        if (!file_exists($this->files['tmp_name'])){
            return false;
        }
        return md5_file($this->files['tmp_name']);
    }

    public function getMimeType() {
        $_mime = 'null';
        if (!file_exists($this->files['tmp_name'])){
            return $_mime;
        }
        if (function_exists('finfo_open')) {
            Log::setLog('Recherche du mime-type par finfo', 'Upload');
            $finfo = finfo_open(FILEINFO_MIME_TYPE); // Retourne le type mime à l'extension mimetype
            $_mime = finfo_file($finfo, $this->files['tmp_name']);
            Log::setLog('Mime-type est ' . $_mime, 'Upload');
            finfo_close($finfo);
        } elseif (function_exists('mime_content_type')) {
            Log::setLog('Recherche du mime-type par mime_content_type', 'Upload');
            $_mime = mime_content_type($this->files['tmp_name']);
            Log::setLog('Mime-type est ' . $_mime, 'Upload');
        } else {
            $this->mime = $this->file['type'];
            Log::setLog('/!\ Serveur insuffisament s&eacute;ris&eacute; : Requ&egrave;te Mime-Type non trait&eacute;', 'Upload');
        }

        return $_mime;
    }

    public function moveToDir($destinationDir) {
        if (!is_dir($destinationDir)){
            Log::setLog("Dir not found: " . $destinationDir, 'Upload');
            if (is_writable($destinationDir)) {
                if (!mkdir($destinationDir, 0777, true)){
                    return false;
                }
                return $this->moveToDir($destinationDir);
            } else {
                return false;
            }
        }

        $realPath = realpath($destinationDir);
        if (is_writable($realPath)) {
            $this->files['dst_name'] = $realPath . DS . $this->getFileName();
            if (!@rename($this->files['tmp_name'], $this->files['dst_name'])){
                throw new Exception('Permission denied: ' . $realPath);
            }
            $this->files['tmp_name'] = $this->files['dst_name'];
            return true;
        }
    }

    public function rename($newName, $extentionAuto = false) {
        if (!empty($newName)) {
            $newName = ($extentionAuto) ? $newName . '.' . $this->getFileExtention() : $newName;
            Log::setLog("Rename file from " . $this->files['name'] . " to " . $newName);
            $this->files['name'] = preg_replace("/[^a-z0-9\.]/", "", strtolower($newName));
        }
    }

    public function existe($returnString = false) {
        if (file_exists($this->files['dst_name'])) {
            return ($returnString) ? "true" : true;
        }

        return ($returnString) ? "false" : false;
    }

}
/**
$data = array(
    "dt" => "dl",
    "dt1" => array("alpha", "beta", "teta")
);
echo Bootstrap::dl($data, $horizontal = true);
echo Bootstrap::menuContextuel(array(
    "nord" => "Nord",
    "est" => "Est",
    false,
    "ouest" => "Ouest",
    false,
    "sud" => "Sud"));
echo Bootstrap::tabs(array(
    "nord" => array('title' => 'Nord', 'body' => "Le nord c'est froid"),
    "est" => array('title' => 'Est', 'body' => "L'est c'est beau"),
    "ouest" => array('title' => 'Ouest', 'body' => "L'ouest c'est chaud a ecrire"),
    "sud" => array('title' => 'Sud', 'body' => "Les vacances "),
    "azer" => array(
        'dropdown' => array(
            "cle" => array(
                'title' => 'Menu 1',
                'body' => "Body 1"
            ),
            "cle2" => array(
                'title' => 'Zut, il y a plus de PQ',
                'body' => 'Va donc chez Lidl en acheter'
            )
        ),
        'title' => 'Sud',
        'body' => "Les vacances "
    ),
));

echo Bootstrap::thumbnails(
    array(
        Bootstrap::thumbnail('http://lorempixel.com/300/200/abstract/?1', false, $fancybox = "http://lorempixel.com/600/400/abstract/?1"),
        Bootstrap::thumbnail('http://lorempixel.com/300/200/abstract/?2', array('title' => 'Crystal-Web', 'body' => 'Une passion avant tout'), "http://lorempixel.com/600/400/abstract/?2"),
        Bootstrap::thumbnail('http://lorempixel.com/300/200/abstract/?3', array('title' => 'Nice job', 'body' => 'You are beautiful'), "http://lorempixel.com/300/200/abstract/?3")
    ),
    4,
    true);


echo Bootstrap::accordeon(array(
    array('title' => 'Test 1', 'body' => 'Rien 1'),
    array('title' => 'Test 2', 'body' => 'Rien 2'),
    array('title' => 'Test 3', 'body' => 'Rien 3')
));

$extentions = array('png','jpeg','jpg','gif');
$respon = Bootstrap::filedrop(__SITE_PATH . DS . 'assets' . DS . 'uploads', $extentions);
// No print after this
if (Bootstrap::get()) {
    $return = (object) Bootstrap::get();
    // SAVE DB
}
// */