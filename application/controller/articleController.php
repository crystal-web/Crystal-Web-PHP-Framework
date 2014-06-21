<?php
class articleController extends Controller {

    /**
     * Page d'index listant les 9 derniere articles
     * Si il n'y a aucun article, on affiche la page 404
     */
    public function index() {
        $request = Request::getInstance();
        // Si on a un id, on affiche une page
        if (isset($request->params['page'], $request->params['id'])){
            $action = (isset($request->params['action'])) ? $request->params['action'] : false;
            return $this->page($request->params['id'], $action);
        }

        $page = Page::getInstance();
        $page->setPageTitle('Last news');
        $template = Template::getInstance();
        $article = new ArticleModel();
        $template->article =	$article->getPost(9);
        $template->nbArticle = $article->countArticle();
        $template->show('article/index');
    }

    /**
     * Affiche la page aillant $page_id en identifiant et effectue $action
     *
     * @param int $page_id
     * @param bool|string $action soit false, soit edit ou del
     */
    private function page($page_id, $action = false) {
        $request = Request::getInstance();
        $session = Session::getInstance();
        $page = Page::getInstance();
        $page->setPageTitle('News page');
        $page->setHeaderCss("/assets/controller/article/article.css");
        $template = Template::getInstance();
        $acl = AccessControlList::getInstance();

        $article = new ArticleModel();
        $template->article =	$article->getPostById($page_id);
        $template->action = $action;

        if (!$template->article) {
            $c = new errorController();
            return $c->e404();
        }

        $page->setPageTitle($template->article->title);

        if (Captcha::checkCaptcha()) {
            if ($session->isLogged() && isset($request->data->comment)) {
                $this->postComment($page_id, $session->user('login'), Router::url('minecraft/face/player:' . $session->user('login')), $request->data->comment, true);

            }else  if (isset($request->data->comment, $request->data->username, $request->data->mail)){
                $this->postComment($page_id, $request->data->username, $request->data->mail, $request->data->comment);
            }
            Router::refresh(0);
        }
        if ($session->isLogged() && $acl->isAllowed('article', 'manager')) {
            switch($action) {

            }
        }
        $template->ob_usr = (isset($_COOKIE['ob_usr'])) ? decrypter($_COOKIE['ob_usr']) : false;
        $template->isAdmin = ($session->isLogged() && $session->user('group') == 'admin');
        $template->commentList = $article->getComment($page_id);
        $template->show('article/page');
    }


    public function manager() {
        $page = Page::getInstance();
        $template = Template::getInstance();
        $session = Session::getInstance();
        $request = Request::getInstance();
        $acl = AccessControlList::getInstance();
        if (!$acl->isAllowed()) {
            $c = new errorController();
            return $c->e403();
        }

        $article = new ArticleModel();
        $page->setLayout('admin');
        $page->setPageTitle('Article manager');
        $page->setHeaderCss("/assets/controller/article/article.css");

        $template->show('article/tabs.inc');

        $action = isset($request->params['action']) ? $request->params['action'] : NULL;
        // Action sur un article
        if (isset($request->params['id']) && isset($request->params['action'])) {
            switch($action) {
                case 'comment':
                case 'validate':
                    if (isset($request->params['action'], $request->params['id'])){
                        if ($request->params['action'] == 'validate') {
                            $article->validateComment($request->params['id']);
                        } else {
                            $article->deleteComment($request->params['id']);
                        }
                    }
                break;
                /**
                 * On souhaite editer le post
                 */
                case 'edit':
                    $page_id = (int) $request->params['id'];
                    $template->article = $article->getPostById($page_id);
                    if (!$template->article) {
                        $session->setFlash('Article introuvable');
                        return Router::redirect('article/manager');
                    }
                    // initialisation de $newName, afin que si aucune image n'est posté elle soit concervé
                    $picture = $template->article->picture;
                    $errors = array();
                    // On a recu des données
                    if (isset($request->data->title, $request->data->content)) {
                        // Il y a une image a traité
                        if (isSet($_FILES['file'])) {
                            $file = $_FILES['file'];
                            if (!empty($file['name'])) {
                                $up = new Upload($file);
                                if ($up->prepare()) {
                                    if ($up->isWhitelisted(array('.png','.jpeg','.jpg','.gif'))) {
                                        if (preg_match('#image#', $up->getMime())) {
                                            $im = new Image($up->getUploadPath());
                                            if ($im->setdir(__SITE_PATH . DS . 'assets' . DS . 'images' . DS . 'article')) {
                                                $picture = clean(preg_replace('#\.'.$im->getExt().'#', '', $file['name']), 'slug').'.'.$im->getExt();
                                                $im->save($picture);

                                                $im = new Image($up->getUploadPath());
                                                if ($im->setdir(__SITE_PATH . DS . 'assets' . DS . 'images' . DS . 'article' . DS . 'min')) {
                                                    $im->height(170);
                                                    $im->save($picture);
                                                } else { $errors['avatar'] = 'Erreur lors du transfère, l\'écriture est refusé'; }

                                                //$dataToSave->avatar = (isset($dataToSave->avatar)) ? $dataToSave->avatar : '';
                                            } else { $errors['avatar'] = 'Erreur lors du transfère, l\'écriture est refusé'; }
                                        } else { $errors['avatar'] = 'Extention incorrect'; }
                                    } else { $errors['avatar'] = 'Extention incorrect'; }
                                } else { $errors['avatar'] = 'Image trop lourde'; }
                                $up->removeFile();
                            }
                        }

                        // Aucune erreur on continue
                        if (!count($errors)){
                            // Essaye d'enregistré
                            if ($post = $article->edit($page_id, $request->data->title, $request->data->content, $picture)){
                                return Router::redirect('article/manager');
                            } else { // Echec
                                echo '<div class="title"><h3><span>Error</span></h3></div><div class="col-content">' . $article->lastError . '</div>';
                            }
                        } else { // Il y a une erreur
                            echo '<div class="title"><h3><span>Error</span></h3></div><div class="col-content">' . $errors['avatar'] . '</div>';
                        }
                    } else {
                        $request->data = $template->article;
                    }
                    return $template->show('article/make');
                    break;
                /**
                 * On souhaite supprimer un article
                 */
                case 'del':
                    $page_id = (int) $request->params['id'];
                    $template->article = $article->getPostById($page_id);
                    if (!$template->article) {
                        $session->setFlash('Article introuvable');
                        return Router::redirect('article/manager');
                    }
                    if ($session->token()){
                        $session->makeToken();
                        if ($article->delete($page_id)) {
                            ?>
                            <div class="title"><h3><span>Supprimer</span> l'article</h3></div>
                            <div class="well well-small text-center">
                                L'article <strong><?php echo clean($template->article->title, 'str'); ?></strong> est maintenant supprimer
                            </div>
                            <?php
                            return;
                        }
                    }
                    ?>
                    <div class="title"><h3><span>Supprimer</span> l'article</h3></div>
                    <div class="well well-small text-center">
                    Supprimer l'article <strong><?php echo clean($template->article->title, 'str'); ?></strong>
                    <p>
                        <a href="<?php echo Router::url('article/manager/id:' . $page_id . '/action:del') . '?token=' . $session->getToken(); ?>" class="btn btn-warning">
                            Supprimer
                        </a>
                        <a href="<?php echo Router::url('article/manager'); ?>" class="btn btn-primary">
                            Annuler
                        </a>
                    </p>
                    </div>
                    <?php
                    return;
                break;
                default:
                    return Router::redirect('article/manager');
                break;
            }
        } elseif($action == 'make') {

            $picture = NULL;
            $errors = array();
            // On a recu des données
            if (isset($request->data->title, $request->data->content)) {
                // On recoi une image
                if (isSet($_FILES['file'])) {
                    $file = $_FILES['file'];
                    if (!empty($file['name'])) {
                        $up = new Upload($file);
                        if ($up->prepare()) {
                            if ($up->isWhitelisted(array('.png','.jpeg','.jpg','.gif'))) {
                                if (preg_match('#image#', $up->getMime())) {
                                    $im = new Image($up->getUploadPath());
                                    if ($im->setdir(__SITE_PATH . DS . 'assets' . DS . 'images' . DS . 'article')) {
                                        $picture = clean(preg_replace('#\.'.$im->getExt().'#', '', $file['name']), 'slug').'.'.$im->getExt();
                                        $im->save($picture);
                                        $im = new Image($up->getUploadPath());
                                        if ($im->setdir(__SITE_PATH . DS . 'assets' . DS . 'images' . DS . 'article' . DS . 'min')) {
                                            $im->height(170);
                                            $im->save($picture);
                                        } else { $errors['avatar'] = 'Erreur lors du transfère, l\'écriture est refusé'; }

                                        //$dataToSave->avatar = (isset($dataToSave->avatar)) ? $dataToSave->avatar : '';
                                    } else { $errors['avatar'] = 'Erreur lors du transfère, l\'écriture est refusé'; }
                                } else { $errors['avatar'] = 'Extention incorrect'; }
                            } else { $errors['avatar'] = 'Extention incorrect'; }
                        } else { $errors['avatar'] = 'Extention incorrect ou image trop lourde'; }
                        $up->removeFile();
                    }
                }

                if (!count($errors)){
                    if ($post = $article->post($session->user('id'), $session->user('login'), $request->data->title, $request->data->content, $picture)){
                        return Router::redirect('article/page:' . clean($post->title, 'slug').'/id:' . $post->id);
                    } else {
                        echo '<div class="title"><h3><span>Error</span></h3></div><div class="col-content">' . $article->lastError . '</div>';
                    }
                } else {
                    echo '<div class="title"><h3><span>Error</span></h3></div><div class="col-content">' . $errors['avatar'] . '</div>';
                }
            }

            $page = Page::getInstance();
            $page->setPageTitle('New page');
            $template->show('article/make');
        }
        if($action == 'comment' || $action == "validate") {
            $list = $article->getListComment(/* $validate */ false);

            $data = '<ul class="commentValidator">';
            if (!$list) {
                $data .= '<li>Aucun commentaire</li>';
            } else {
                for ($i=0; $i < count($list); $i++) {
                    $data .= '<li class="clearfix" data-id="'.$list[$i]->id.'">' . clean($list[$i]->comment, 'str') . '&nbsp;&nbsp;&nbsp;<strong>' . clean( $list[$i]->user, 'str') .'</strong>
						<span class="pull-right"><a href="'. Router::url('article/manager/id:' . $list[$i]->id.'/action:comment') .'" class="btn-u btn-u-red" data-action="remove"><i class="fa fa-times"></i></a>
						<a href="'. Router::url('article/manager/id:' . $list[$i]->id.'/action:validate') .'" class="btn-u" data-action="validate"><i class="fa fa-check"></i></a></span>
					</li>';
                }
            }
            $data .= '</ul>';
            echo $data;
            return;
        } else {
            $article = new ArticleModel();
            $template->list = $article->getArticleList(false, $request->page);
            $template->show('article/manager');
        }
    }



    /**
     * @param $page_id
     * @param $username
     * @param $comment
     * @param $mail
     */
    private function postComment($page_id,  $username, $mail, $comment, $hasTwitter = false) {
        $error = false;
        if (strlen($username) < 3) {
            if (is_ajax()){
                echo json_encode(array('error' => 'Votre pseudo est trop court'));
                die;
            } else {
                $error = 'Votre pseudo est trop court';
            }
        } elseif (!Securite::isMail($mail) and !$hasTwitter) {
            if (is_ajax()){
                echo json_encode(array('error' => 'Votre adresse e-mail semble incorrect'));
                die;
            } else {
                $error = 'Votre adresse e-mail semble incorrect';
            }
        } elseif (strlen($comment) < 3) {
            if (is_ajax()){
                echo json_encode(array('error' => 'Votre commentaire est trop court'));
                die;
            } else {
                $error = 'Votre commentaire est trop court';
            }
        } elseif (strlen($comment) > 255) {
            if (is_ajax()){
                echo json_encode(array('error' => 'Votre commentaire est trop long'));
                die;
            } else {
                $error = 'Votre commentaire est trop long';
            }
        }

        $array = explode(' ', $comment);
        // $array = array_filter($array, 'empty'); // on supprime les cases vides
        if ( (count($array) / (strlen($comment) / 6)) < 0.2 ) {
            if (is_ajax()){
                echo json_encode(array('error' => 'Message frauduleux d&eacute;tect&eacute;'));
                die;
            } else {
                $error = 'Message frauduleux d&eacute;tect&eacute;';
            }
        }

        $countComment = 0;
        if (class_exists('Memcache')) {
            $memcache_obj = new Memcache;
            $memcache_obj->connect('127.0.0.1', 11211);
            $countComment = $memcache_obj->get('minetraxx_article_' . md5(Securite::ipX()));
            if ($countComment > 3) {
                if (is_ajax()){
                    echo json_encode(array('error' => 'Veuillez patient&eacute; quelque minutes avant de post&eacute; &agrave; nouveau'));
                    die;
                } else {
                    $error = 'Veuillez patient&eacute; quelque minutes avant de post&eacute; &agrave; nouveau';
                }
            }
        }

        $session = Session::getInstance();
        $article = new ArticleModel();
        $actived = ($session->isLogged() /*&& $session->user('group') == 'admin'*/) ? true : false;

        if ($article->postComment($page_id, $username, $mail, $comment, $actived)) {
            $cookie = (isset($_COOKIE['ob_usr'])) ? decrypter($_COOKIE['ob_usr']) : array();
            $cookie['user'] = $username;
            $cookie['mail'] = $mail;
            setcookie( 'ob_usr', encrypter($cookie), strtotime( '+30 days' ), "/");

            if (class_exists('Memcache') && !$actived) {
                $countComment++;
                $memcache_obj->set('minetraxx_article_' . md5(Securite::ipX()), $countComment, MEMCACHE_COMPRESSED, 500);
            }

            $actived = ($actived) ? '' : 'Il sera visible apr&eacute;s mod&eacute;ration.';
            if (is_ajax()){
                echo json_encode(array('success' => 'Votre commentaire est enregistr&eacute;. ' . $actived, $cookie));
                die;
            } else {
                $success = 'Votre commentaire est enregistr&eacute;. ' . $actived;
            }
        } else {
            if (is_ajax()){
                echo json_encode(array('error' => 'Erreur inhatendu, veuillez r&eacute;ssayer plus tard'));
                die;
            } else {
                $error = 'Erreur inhatendu, veuillez r&eacute;ssayer plus tard';
            }
        }
        ?>
        <?php
        if (isset($success)) {
            $session->setFlash($success);
        } elseif ($error) {
            $session->setFlash($error);
        }
        ?>
    <?php
    }
}
