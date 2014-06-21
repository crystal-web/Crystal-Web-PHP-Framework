<?php
/**
 * @acl plugin.index				Liste des plugins et interaction
plugin.manager				Action sur les plugins
 * @revision 2013-02-08
 * @changelog Correction général
 */
Class pluginController Extends Controller {

    public function index() {
        $acl = AccessControlList::getInstance();
        $page = Page::getInstance();
        $request = Request::getInstance();
        $plugin = Plugin::getInstance();
        $template = Template::getInstance();

        if (!$acl->isAllowed()) {Router::redirect();}

        $page->setPageTitle('Plugin manager')
            ->setBreadcrumb('plugin','Plugin');

        $pluginList = $plugin->getList();
        $pluginListNew = array();
        $scanner = opendir(__APP_PATH . DS . 'plugin');
        $slug = (isSet($request->params['slug'])) ? $request->params['slug'] : '';
        $stat = (isSet($request->params['stat'])) ? $request->params['stat'] : false;

        while (($dir = readdir($scanner)) !== false) {
            if ($dir != '.' && $dir != '..') {
                $iniFile = __APP_PATH . DS . 'plugin'  . DS . $dir . DS . 'plugin.ini';
                if (file_exists($iniFile)) {

                    if (!isSet($pluginList[$dir])) {
                        $pluginListNew[$dir] = array(
                            'enable' => false,
                            'info' => parse_ini_file($iniFile)
                        );
                    } else {
                        $pluginListNew[$dir] = array(
                            'enable' => $pluginList[$dir]['enable'],
                            'info' => parse_ini_file($iniFile)
                        );
                    }

                    foreach($pluginListNew[$dir]['info'] as $k=>$v) {
                        if (get_magic_quotes_gpc()) {
                            $pluginListNew[$dir]['info'][$k] = htmlentities($v, ENT_NOQUOTES, 'utf-8');
                        } else {
                            $pluginListNew[$dir]['info'][$k] = htmlentities(addslashes($v), ENT_NOQUOTES, 'utf-8');
                        }

                    }
                }
            }
        }
        closedir($scanner);
        $plugin->setList($pluginListNew);


        if (!empty($slug) && isSet($pluginList[$slug])) {
            $pluginListNew[$slug]['enable'] = ($stat == '1') ? true : false;
            $plugin->setList($pluginListNew);
            Router::redirect('plugin');
            //debug($pluginList);
        }
        //debug($pluginList);

        $template->pluginList = $pluginListNew;
        $template->show('plugin/index');
    }


    /*
    *	Adminstration d'un plugin
    */
    public function manager() {
        $acl = AccessControlList::getInstance();
        $page = Page::getInstance();
        $request = Request::getInstance();
        $plugin = Plugin::getInstance();
        $template = Template::getInstance();
        $session = Session::getInstance();

        if (!$acl->isAllowed()) {
            $session->setFlash('Vous n\'avez pas le droit d\'accès à cette page', 'error');
            Router::redirect();
        }

        if ( isSet($request->params['slug']) && !empty($request->params['slug']) ) {
            $mePlugin = $request->params['slug'];
            $pluginList = $plugin->getList();

            if (isSet( $pluginList[ $mePlugin ] ) ) {
                if (!$pluginList[ $mePlugin ]['enable']) {
                    $session->setFlash('Le plugin n\'est pas activé','error');
                    Router::redirect('plugin');
                }

                $pluginNamed = (isSet($pluginList[ $mePlugin ]['info']['name'])) ? clean($pluginList[ $mePlugin ]['info']['name'], 'str') : $mePlugin;
                $pluginList[ $mePlugin ]['info']['description'] = (isSet($pluginList[ $mePlugin ]['info']['description'])) ? clean($pluginList[ $mePlugin ]['info']['description'], 'str') : '';
                $page->setPageTitle($pluginNamed . ' manager')
                    ->setBreadcrumb('plugin','Plugin');


                if (isSet($pluginList[ $mePlugin ]['info']['setting'])) {
                    ob_start();
                    $plugin->triggerEvents(clean($pluginList[ $mePlugin ]['info']['setting'], 'str'));
                    // Enregistre le contenu du tampon de sortie
                    $buffer = ob_get_contents();

                    // Efface le contenu du tampon de sortie
                    ob_clean();

                    if (!empty($buffer)) {
                        $template->plugin = $pluginList[ $mePlugin ];
                        $template->html = $buffer;
                        $template->show('plugin/manager');
                    }

                } else {
                    $session->setFlash('Le plugin ne dispose pas d\'une administration', 'error');
                    Router::redirect('plugin');
                }
            } else {
                $session->setFlash('Plugin introuvable', 'error');
                Router::redirect('plugin');
            }
        } else {
            $session->setFlash('Plugin introuvable', 'error');
            Router::redirect('plugin');
        }
    }
}