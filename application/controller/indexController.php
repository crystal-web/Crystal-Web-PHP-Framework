<?php
/**
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/
if (!defined('__APP_PATH'))
{
	echo '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You don\'t have permission to access this file on this server.</p></body></html>'; die;
}

Class indexController extends Controller{
	public function index(){
        $template = Template::getInstance();
        $request = Request::getInstance();
        $page = Page::getInstance();$page->setLayout('default');

        if ($request->data && !isset($request->data->type)) {
            $request->data->type = false;
        }
        if ($request->data && !isset($request->data->language)) {
            $request->data->language = array();
        }
        if (isset($request->data->lastname,
                    $request->data->firstname,
                    $request->data->street,
                    $request->data->cp,
                    $request->data->country,
                    $request->data->city,
                    $request->data->company,
                    $request->data->language,
                    $request->data->mail,
                    $request->data->tel,
                    $request->data->fax,
                    $request->data->site,
                    $request->data->description,
                    $request->data->type,
                    $request->data->query)
        ) {
            $memcache_obj = new Memcache;
            $memcache_obj->connect('127.0.0.1', 11211);
            $devisctrl = $memcache_obj->get('devis_' . md5(Securite::ipX()));

            if ($devisctrl === false OR $devisctrl < 3) {
                $devis = new DevisModel();

                $errorStr = false;
                if (empty($request->data->lastname) or strlen($request->data->lastname) < 2) {
                    $errorStr .= 'Votre nom est trop court<br>';
                }
                if (empty($request->data->query)) {
                    $errorStr .= 'Le champ "Votre demande" n\'est pas remplis.<br>';
                }
                if (empty($request->data->description)) {
                    $errorStr .= 'Le champ "Description de votre site/de votre projet" n\'est pas remplis.<br>';
                }
                if (!count($request->data->language)) {
                    $errorStr .= 'Le champ "Choix des langues d&eacute;sir&eacute;es" n\'est pas remplis.<br>';
                }
                if (!Securite::isMail($request->data->mail)) {
                    $errorStr .= 'Le champ "E-mail" semble incorrect.<br>';
                }
                if (strlen($request->data->tel) < 5) {
                    $errorStr .= 'Le champ "T&eacute;l&eacute;phone" semble incorrect.<br>';
                }
                if (!$errorStr) {
                    $add = $devis->add($request->data->lastname,
                        $request->data->firstname,
                        $request->data->street,
                        $request->data->cp,
                        $request->data->country,
                        $request->data->city,
                        $request->data->company,
                        $request->data->language,
                        $request->data->mail,
                        $request->data->tel,
                        $request->data->fax,
                        $request->data->site,
                        $request->data->description,
                        $request->data->type,
                        $request->data->query);

                    if ($add) {
                        $message = 'Bonjour,' . PHP_EOL . PHP_EOL;
                        $message .= 'Un nouveau devis de ' . $request->data->firstname . ' ' . $request->data->lastname . ' vient d\'&egrave;tre enregistr&eacute;.' . PHP_EOL . PHP_EOL;
                        $message .= 'Service interne';
                        $mail = new Mail('chbube@gmail.com', 'Un devis vient d\'arriver', $message, 'devis@devphp.me');
                        if ($mail->sendMail()) {
                            $devisctrl = ($devisctrl === false) ? 1 : $devisctrl+1;
                            $memcache_obj->set('devis_' . md5(Securite::ipX()), $devisctrl, MEMCACHE_COMPRESSED, 60);

                            echo "<script>$(document).ready(function(){ setTimeout(\"bootbox.alert('Votre devis est enregistr&eacute;, nous vous contacterons sous peux');\", 750); });</script>";
                        }
                    }
                } else {
                    echo "<script>$(document).ready(function(){


                        $('#devis').slideToggle('slow');
                        $('#callto').slideToggle('slow');
                        var offset = jQuery('#devis').offset().top;
                        jQuery('html, body').animate({scrollTop: offset}, 500);

                    setTimeout(\"bootbox.alert('".addslashes($errorStr)."')\", 750);
                    });</script>";
                }
            } else {
                echo "<script>$(document).ready(function(){setTimeout(\"bootbox.alert('Notre syst&egrave;me anti-spam a d&eacute;t&eacute;ct&eacute; de multiple demande de votre part. Merci de patient&eacute; avant de poster &agrave; nouveau');\", 750); });</script>";
            }
        }

        $template->form = $request->data;
        $template->show('index/index');
	}
}
