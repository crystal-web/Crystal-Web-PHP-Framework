<?php
/**
 * @acl sysinfo.index				Liste des membres et interaction
sysinfo.rpc					Affichage des info de connexion U/D
sysinfo.who					Affichage de connexion ssh etc.
sysinfo.apphp				Affichage des infos PHP Apache
sysinfo.partion				Affichage des partions
 * @revision 2013-02-08
 * @include PHPmodule
 * @changelog Correction général
 */

class panelcontrolController extends Controller {

    public function index() {
        $page = Page::getInstance();
        $session = Session::getInstance();
        $template = Template::getInstance();
        $page->setPageTitle('Information serveur ');

        $acl = AccessControlList::getInstance();
        if (!$acl->isAllowed('panelcontrol')) {
            $c = new errorController();
            return $c->e403();
        }

        $page->setLayout('sd-admin');

        if (PHP_OS != 'Linux') {
            echo ('SysInfo fonctionne uniquement sur un serveur Linux');
            return;
        }

        $uptime = exec("uptime");

        /***************************************
         *	Information général
         ***************************************/
        $uname = explode(" ", exec("uname -a"), 5);

        $total_cpu = 0;
        $cpuinfo = file("/proc/cpuinfo");

        for ($i = 0; $i < count($cpuinfo); $i++) {
            $arr = explode(":", $cpuinfo[$i]);
            $item = (isSet($arr[0])) ? $arr[0]: NULL;
            $data = (isSet($arr[1])) ? $arr[1]: NULL;

            $item = chop($item);
            $data = chop($data);
            if ($item == "processor") {
                $total_cpu++;
                $cpu_info = $total_cpu;
            }
            if ($item == "vendor_id") { $cpu_info .= $data; }
            if ($item == "model name") { $cpu_info .= $data; }
            if ($item == "cpu MHz") {
                $cpu_info .= " " . floor($data);
                $found_cpu = "yes";
            }
            if ($item == "cache size") { $template->cache = $data; }
            if ($item == "bogomips") { $template->bogomips = $data; }
        }
        if($found_cpu != "yes") { $cpu_info .= " <b>inconnu</b>"; }
        $cpu_info .= " MHz Processor(s)\n";

        $template->cpu_info = $cpu_info;

        /***************************************
         *	Information sur la mémoire
         ***************************************/
        $total_mem = $free_mem = $total_swap = $buffer_mem = $cache_mem = $shared_mem = NULL;
        $meminfo = file("/proc/meminfo");
        foreach ($meminfo AS $k => $v) {
            list($item, $data) = explode(":", $v, 2);
            $item = chop($item);
            $data = chop($data);
            if ($item == "MemTotal") { $total_mem =$data;	}
            if ($item == "MemFree") { $free_mem = $data; }
            if ($item == "SwapTotal") { $total_swap = $data; }
            if ($item == "SwapFree") { $free_swap = $data; }
            if ($item == "Buffers") { $buffer_mem = $data; }
            if ($item == "Cached") { $cache_mem = $data; }
            if ($item == "MemShared") {$shared_mem = $data; }
        }

        $memoire = array();
        $memoire['total_mem'] = $total_mem;
        $memoire['used_mem'] = ( $total_mem - $free_mem );
        $memoire['real_mem_use'] = $memoire['used_mem'] - $cache_mem;
        $memoire['real_mem_free'] = $total_mem - $memoire['real_mem_use'];

        $memoire['total_swap'] = $total_swap;
        $memoire['used_swap'] = ( $total_swap - $free_swap );
        $memoire['free_swap'] = $free_swap ;



        $memoire['total_buff'] = $buffer_mem;
        $memoire['total_cach'] = $cache_mem;

        $memoire['percent_used_real'] = round( $memoire['real_mem_use'] / $memoire['total_mem']  * 100 ).'%';
        $memoire['percent_free_real'] = round( $memoire['real_mem_free'] / $memoire['total_mem']  * 100 ).'%';

        $memoire['percent_free'] = round( $free_mem / $total_mem * 100 ).'%';
        $memoire['percent_used'] = round( $memoire['used_mem'] / $total_mem * 100 ).'%';
        $memoire['percent_buff'] = round( $buffer_mem / $total_mem * 100 ).'%';
        $memoire['percent_cach'] = round( $cache_mem / $total_mem * 100 ).'%';
        $memoire['percent_shar'] = round( $shared_mem / $total_mem * 100 ).'%';

        $memoire['percent_swap'] = ($total_swap > 0) ? round( ( $total_swap - $free_swap ) / $total_swap * 100 ).'%' : '0%';
        $memoire['percent_swap_free'] = ($total_swap > 0) ? round( $free_swap / $total_swap * 100 ).'%' : '0%';

        // Template
        $template->memoire = $memoire;


        /***************************************
         *	Partitionnage du système
         ***************************************/
        exec ("df", $x);
        $percent_part = $drive = $size = $used = $avail = $percent = $mount = NULL;
        $partition = array();

        foreach($x AS $k => $v) {
            $v = preg_replace('#\s+#', '|', $v);
            list($drive, $size, $used, $avail, $percent, $mount) = explode("|", $v);//*/
            $percent_part = str_replace( "%", "", $percent );
            if ($mount === "/") {
                $partition['primary'] = array(
                    'drive'		=> $drive,
                    'size'		=> $size,
                    'used'		=> $used,
                    'avail'		=> $avail,
                    'percent'	=> $percent,
                    'mount'		=> $mount,
                    'partPerc'	=> $percent_part
                );
            }
            $partition[] = array(
                'drive'		=> $drive,
                'size'		=> $size,
                'used'		=> $used,
                'avail'		=> $avail,
                'percent'	=> $percent,
                'mount'		=> $mount,
                'partPerc'	=> $percent_part
            );
        }
        unset($partition[0]);

        /*** Memoire ***/
        $template->uptime = $uptime;
        $template->time = (exec("date"));
        $template->system	= (isSet($uname[0])) ? $uname[0] : 'Inconnu';
        $template->host		= (isSet($uname[1])) ? $uname[1] : 'Inconnu';
        $template->kernel	= (isSet($uname[2])) ? $uname[2] : 'Inconnu';
        $template->total_cpu = $total_cpu;
        $template->partition = $partition;

        /*** Module ***/
        $mPhp = new PHPmodule();
        $template->modulePHP = $mPhp->parsePHPModules();
        $m = new Model();
        $template->sqlVar = $m->query('SHOW VARIABLES', true);

        $template->show('panelcontrol/index');
    }


    public function error(){
        $page = Page::getInstance();
        $session = Session::getInstance();
        $template = Template::getInstance();
        $request = Request::getInstance();

        $acl = AccessControlList::getInstance();
        if (!$acl->isAllowed('panelcontrol.error')) {
            $c = new errorController();
            return $c->e403();
        }

        $page->setLayout('sd-admin');
        $page->setPageTitle('Inspecteur d\'erreur');

        $errLog = new Cache('erreur_alerte');
        $alerte = $errLog->getCache();

        if (isSet($request->params['id'])) {
            unset($alerte[$request->params['id']]);
            $errLog->setCache($alerte);
        } elseif (isSet($request->params['fn'])) {
            $errLog->setCache();
            return Router::redirect('panelcontrol/error');
        }

        $template->alerte = $alerte;
        $template->show('panelcontrol/error');
    }


    public function log(){
        $request = Request::getInstance();
        $page = Page::getInstance();
        $template = Template::getInstance();

        $acl = AccessControlList::getInstance();
        if (!$acl->isAllowed('panelcontrol.log')) {
            $c = new errorController();
            return $c->e403();
        }
        $page->setLayout('sd-admin');
        $m = new LogModel();
        $tag = $m->getTag();
        $explodeUrl = explode('/', trim($request->url, '/'));

        if (
            (isset($explodeUrl[2]) && $explodeUrl[2] != 'log') &&
            (isset($explodeUrl[2]) && $explodeUrl[2] != 'this')
        ){
            $page->setPageTitle('Liste de logs pour ' . $explodeUrl[2]);
            $log = $m->getLog($request->page, 30, $explodeUrl[2]);

        } elseif (isset($explodeUrl[1]) && $explodeUrl[1] == 'this' && count($explodeUrl) == 4) {
            $log = $m->getUidLog($explodeUrl[3], $request->page, 30, $explodeUrl[1]);

            if (isSet($log['query'][0]->loginmember)) {
                $page->setPageTitle('Liste des logs pour ' . $log['query'][0]->loginmember);
            }
        } else {
            $log = $m->getLog($request->page, 30);
            $page->setPageTitle('Liste des logs');
        }


        $template->tagList = $tag;
        $template->log = $log;
        $template->show('panelcontrol/log');
    }


    /**
     * Le but ici est de permettre de configurer, le site (systeme) afin de le rendre
     * plus performant. Ceci inclus quelques difficulté...
     */
    public function config() {
        $request = Request::getInstance();
        $template = Template::getInstance();
        $page = Page::getInstance();
        $form = Form::getInstance();
        $config = Config::getInstance();
        $session = Session::getInstance();

        $acl = AccessControlList::getInstance();
        if (!$acl->isAllowed('panelcontrol.config')) {
            $c = new errorController();
            return $c->e403();
        }

        $page->setLayout('sd-admin');

        $directoryLayout = scandir(__APP_PATH . DS . 'layout');
        $layoutList = array();
        for ($i=0; $i<count($directoryLayout); $i++) {
            if (
                $directoryLayout[$i] != '.' and
                $directoryLayout[$i] != '..' and
                $directoryLayout[$i] != 'empty.phtml' and
                $directoryLayout[$i] != 'alert.phtml'
            ) {
                if (preg_match('#\.phtml#', $directoryLayout[$i])) {
                    $name = preg_replace('#\.phtml#', '', $directoryLayout[$i]);
                    $layoutList[$name] = $name;
                }
            }
        }
        unset($directoryLayout);
        $errors = array();

        if ($request->data) {
            if (isSet($request->data->mailContact)) {
                if (!Securite::isMail($request->data->mailContact)) {
                    $errors['mailContact'] = 'N\'est pas une adresse mail valide';
                }
            }
            if (isSet($request->data->mailSite)) {
                if (!Securite::isMail($request->data->mailSite)) {
                    $errors['mailSite'] = 'N\'est pas une adresse mail valide';
                }
            }

            if (count($errors)) {
                $form->setErrors($errors);
            } else {
                try {
                    $config
                        ->setSiteTitle($request->data->siteName)
                        ->setSiteSlogan($request->data->siteSlogan)
                        ->setSiteTeam($request->data->siteTeamName)
                        ->setLayout($request->data->layout);
                    $config
                        ->setSiteMail($request->data->mailSite)
                        ->setSiteMailContact($request->data->mailContact);
                    $config->saveChange();
                    return Router::refresh();
                } catch (Exception $e) {
                    $session->setFlash($e->getMessage(), 'warning');
                }
            }
        } else { $request->data = new stdClass(); }

        $config->getConfig();
        $request->data->layout = clean($config->getLayout(), 'str');
        $page->setPageTitle('Configuration du site');
        $template->layoutList = $layoutList;
        $template->config = $config;
        $template->show('panelcontrol/config');
    }

    public function devis(){
        $request = Request::getInstance();
        $page = Page::getInstance();
        $template = Template::getInstance();

        $acl = AccessControlList::getInstance();
        if (!$acl->isAllowed('permission.manager')) {
            $c = new errorController();
            return $c->e403();
        }
        $page->setLayout('sd-admin');
        $m = new DevisModel();

        $template->devis = $m->getList($request->page);
        $template->show('devis/index');
    }

    public function rpc() {
        $acl = AccessControlList::getInstance();
        if (!$acl->isAllowed('permission.manager')) {
            $c = new errorController();
            return $c->e403();
        }

        $query = (isset($_GET['query'])) ? $_GET['query'] : '';
        switch($query){
            case 'devis':
                $request = Request::getInstance();
                if (isset($request->data->id)){
                    $m = new DevisModel();
                    $devis = $m->getById($request->data->id);
                    ?>
<div>
    <p>
        <strong>Contact</strong>: <br>
        <?php
        echo (!empty($devis->company)) ? clean($devis->company, 'str') . ' <br>' : '';
        echo clean($devis->firstname . ' ' . $devis->lastname, 'str') . ' <br>' ;

        echo (!empty($devis->street)) ? clean($devis->street, 'str') . ' <br>' : '';
        echo clean($devis->cp . ' ' . $devis->city . '<br>' . $devis->country, 'str');
        ?>
    </p>
    <p>
        <?php
        echo (!empty($devis->mail)) ? '<strong>E-mail:</strong> ' . clean($devis->mail, 'str') . ' <br>' : '';
        echo (!empty($devis->tel)) ? '<strong>Tel:</strong> ' . clean($devis->tel, 'str') . ' <br>' : '';
        echo (!empty($devis->fax)) ? '<strong>Fax:</strong> ' . clean($devis->fax, 'str') . ' <br>' : '';
        echo (!empty($devis->site)) ? '<strong>Site:</strong> '. clean($devis->site, 'str') . ' <br>' : '';
            $devis->language = unserialize($devis->language);
            if (count($devis->language)){ echo '<strong>Langue:</strong> '; foreach($devis->language AS $k => $lng) { echo clean($lng, 'str') . ' '; } }
        ?>
    </p>
    <p>
        <?php
        echo (!empty($devis->query)) ? '<strong>Requete:</strong> ' . $devis->query . ' <br>' : '';
        $devis->type = unserialize($devis->type);
        if (count($devis->type)){ echo '<strong>Type:</strong> '; foreach($devis->type AS $k => $type) { echo clean($type, 'str') . ' '; } }
        ?>
    </p>
    <div>
        <?php echo clean($devis->description, 'bbcode'); ?>
    </div>
</div>
<?php
                }
                die;
            break;
            default:
                $uptime = exec("uptime");
                $sys_ticks = trim($uptime);

                preg_match('#([0-9]+:[0-9]+:[0-9]+)#', $uptime, $time);
                $time = (count($time) == 0) ? 0 : $time[1];
                preg_match('#([0-9]+) year#', $uptime, $year);
                $year = (count($year) == 0) ? 0 : $year[1];
                preg_match('#([0-9]+) day#', $uptime, $day);
                $day = (count($day) == 0) ? 0 : $day[1];
                preg_match('#([0-9]+:[0-9]+),#', $uptime, $utime);
                $utime = (count($utime) == 0) ? '0:0' : $utime[1];

                $sys_ticks = explode(' ', $sys_ticks);
                $sysinfo = array();
                $sysinfo['time'] = $time;
                $sysinfo['utime']['y'] = $year;
                $sysinfo['utime']['d'] = $day;
                $sysinfo['utime']['h'] = $utime;


                if (preg_match('#([0-9]+\.[0-9]+), ([0-9]+\.[0-9]+), ([0-9]+\.[0-9]+)#', $uptime, $avarege)) {
                    $sysinfo['avg']['avgnow'] = (isSet($avarege[1])) ? trim($avarege[1]) : 'UNDEFINED';
                    $sysinfo['avg']['avg5'] = (isSet($avarege[2])) ? trim($avarege[2]) : 'UNDEFINED';
                    $sysinfo['avg']['avg15'] = (isSet($avarege[3])) ? trim($avarege[3]) : 'UNDEFINED';
                } else {
                    $sysinfo['avg']['avgnow'] = (isSet($sys_ticks[12])) ? trim($sys_ticks[12], ', ') : 'UNDEFINED';
                    $sysinfo['avg']['avg5'] = (isSet($sys_ticks[13])) ? trim($sys_ticks[13], ', ') : 'UNDEFINED';
                    $sysinfo['avg']['avg15'] = (isSet($sys_ticks[14])) ? trim($sys_ticks[14], ', ') : 'UNDEFINED';
                }


                /***************************************
                 *	Information sur la mémoire
                 ***************************************/
                $total_mem = $free_mem = $total_swap = $buffer_mem = $cache_mem = $shared_mem = NULL;

                $meminfo = file("/proc/meminfo");

                foreach ($meminfo AS $k => $v) {
                    list($item, $data) = explode(":", $v, 2);
                    $item = chop($item);
                    $data = chop($data);
                    if ($item == "MemTotal") { $total_mem =$data;	}
                    if ($item == "MemFree") { $free_mem = $data; }
                    if ($item == "SwapTotal") { $total_swap = $data; }
                    if ($item == "SwapFree") { $free_swap = $data; }
                    if ($item == "Buffers") { $buffer_mem = $data; }
                    if ($item == "Cached") { $cache_mem = $data; }
                    if ($item == "MemShared") {$shared_mem = $data; }
                }

                $memoire = array();
                $memoire['total_mem'] = $total_mem;
                $memoire['used_mem'] = ( $total_mem - $free_mem );
                $memoire['real_mem_use'] = $memoire['used_mem'] - $cache_mem;
                $memoire['real_mem_free'] = $total_mem - $memoire['real_mem_use'];

                $memoire['total_swap'] = $total_swap;
                $memoire['used_swap'] = ( $total_swap - $free_swap );
                $memoire['free_swap'] = $free_swap ;

                $memoire['total_buff'] = $buffer_mem;
                $memoire['total_cach'] = $cache_mem;

                $memoire['percent_used_real'] = round( $memoire['real_mem_use'] / $memoire['total_mem']  * 100 );
                $memoire['percent_free_real'] = round( $memoire['real_mem_free'] / $memoire['total_mem']  * 100 );

                $memoire['percent_free'] = round( $free_mem / $total_mem * 100 );
                $memoire['percent_used'] = round( $memoire['used_mem'] / $total_mem * 100 );
                $memoire['percent_buff'] = round( $buffer_mem / $total_mem * 100 );
                $memoire['percent_cach'] = round( $cache_mem / $total_mem * 100 );
                $memoire['percent_shar'] = round( $shared_mem / $total_mem * 100 );

                $memoire['percent_swap'] = ($total_swap > 0) ? round( ( $total_swap - $free_swap ) / $total_swap * 100 ) : '0';
                $memoire['percent_swap_free'] = ($total_swap > 0) ? round( $free_swap / $total_swap * 100 ) : '0';

                echo json_encode(array(
                    'sys' => $sysinfo,
                    'memory' => $memoire
                ));
                die;
            break;
        }
    }
}



function byte($size) {
    $size = (int) $size;
    $units = array('B', ' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
    return array('size' => round($size, 2), 'unit' => $units[$i]);
}

