<?php

Class faqController extends Controller {
    public $acl = array('faq.manager' => 'Utilisé le gestionnaire de configuration');

    public function index() {
        $page = Page::getInstance();
        $template = Template::getInstance();
        $faq = new FaqModel();

        $page->setPageTitle('Foire aux questions');
        $template->faq = $faq->find(array(
            'fields' => 'FQ.id, question, reponse, catname',
            'conditions' => array('active' => 'on'),
            'join' => array(__SQL . '_FaqCat AS FQ' => 'FQ.id = Faq.cid'),
            'order' => 'cid ASC'
        ));
        $template->show('faq/index');
    }

    public function manager() {
        $page = Page::getInstance();
        $template = Template::getInstance();
        $acl = AccessControlList::getInstance();
        $request = Request::getInstance();
        $session = Session::getInstance();
        $form = Form::getInstance();

        $faq = new FaqModel();
        $html = new Html();

        if (!$acl->isAllowed()) {
            $o = $this->loadController('error');
            return $o->e403();
        }

        $page->setPageTitle('Manager');
        $page->setBreadcrumb('faq', 'Foire aux questions');

        $html->script(array('type' => 'application/javascript'),
            "
            if(!('contains' in String.prototype))
                String.prototype.contains = function(str, startIndex) { return -1 !== String.prototype.indexOf.call(this, str, startIndex); };

            jQuery(function(){
                jQuery('.cancel').live('click', function(e) {
                    e.preventDefault();
                    jQuery('.modal').modal('hide');
                });
                jQuery('.dellink').on('click', function(e){
                    e.preventDefault();
                    var attr = jQuery(this).attr('class');
                    var message = (attr.contains('on')) ? 'Désactiver' : 'Activer';
                    jQuery.alertModal('<p>' + message + ' la question ?</p><p><a href=\"' + jQuery(this).attr('href') + '?token=" . $session->getToken() . "\" class=\"btn-u\">Oui</a> <a href=\"#\" class=\"btn-u btn-u-red cancel\">Non</a></p>', {title: 'Alert !', showButtons: false});
				});
			});
			"
        )->end();

        if(isSet($request->params['action'])) {
            switch ($request->params['action']) {
                case 'del':
                    if (isset($request->params['id']) && $session->token()) {
                        $faqDel = $faq->findFirst(array(
                            'fields' => 'id, question, reponse, active',
                            'conditions' => array('id' => (int) $request->params['id'])));
                        if ($faqDel) {
                            $faqDel->active = ($faqDel->active == 'on') ? 'off' : 'on';
                            if ($faq->save($faqDel)) {
                                if ($faqDel->active == 'on') {
                                    $session->setFlash('Question activ&eacute;');
                                } else {
                                    $session->setFlash('Question d&eacute;sactiv&eacute;');
                                }
                                return Router::redirect('faq/manager');
                            }
                        }
                    }
                    break;
                case 'edit':
                    if(isSet($request->params['id'])) {
                        $faqEdit = $faq->findFirst(array(
                            'fields' => 'id, question, reponse',
                            'conditions' => array('id' => (int) $request->params['id'])));
                        if ($faqEdit) {
                            if (isset($request->data->question, $request->data->reponse) AND $session->token()) {
                                $faqEdit->question = $request->data->question;
                                $faqEdit->reponse = $request->data->reponse;
                                if ($faq->save($faqEdit)) {
                                    $session->setFlash('Modification enregistr&eacute;e');
                                    return Router::redirect('faq/manager');
                                }
                            }

                            $request->data = $faqEdit;
                            $html->form(array('class' =>  "form-horizontal", 'method' => "post", 'action' => Router::url('faq/manager/action:edit/id:' . (int) $request->params['id']) . '?token=' . $session->getToken()),
                                $form->input('question', 'Question').
                                $form->input('reponse', 'Réponse', array('type' => 'textarea', 'editor' => '')).
                                $form->input('submit', 'Editer', array('type' => 'submit', 'class' => 'btn primary'))
                            )->end();
                        }
                    }
                    break;
                case 'make':
                    $faq->setTable('FaqCat');
                    $group = $faq->find(array(
                        'fields' => 'id, catname',
                        'order' => 'id ASC',
                        'group' => 'id'
                    ));

                    $options = array();
                    for($i=0;$i<count($group);$i++){
                        $options[$group[$i]->id] = $group[$i]->catname;
                    }

                    if(isSet($request->params['id'])) {
                        if ($request->params['id'] == 0) {
                            if (isset($request->data->catname)) {
                                $data = new stdClass();
                                $data->catname = $request->data->catname;
                                $faq->save($data);
                                return Router::redirect('faq/manager');
                            }

                            $html->form(array('class' =>  "form-horizontal", 'method' => "post", 'action' => Router::url('faq/manager/action:make/id:0')),
                                $form->input('catname', 'Nom de la catégorie') .
                                $form->input('submit', 'Sauvegarder', array('type' => 'submit', 'class' => 'btn primary'))
                            )->end();
                        } else {
                            if (isset($request->data->cid, $request->data->question, $request->data->reponse)) {
                                $faq->setTable('Faq');
                                $data = new stdClass();
                                $data->cid = $request->data->cid;
                                $data->question = $request->data->question;
                                $data->reponse = $request->data->reponse;
                                $faq->save($data);
                                return Router::redirect('faq/manager');
                            }

                            $html->form(array('class' =>  "form-horizontal", 'method' => "post", 'action' => Router::url('faq/manager/action:make/id:1')),
                                $form->select('cid', 'Catégorie', $options) .
                                $form->input('question', 'Question').
                                $form->input('reponse', 'Réponse', array('type' => 'textarea', 'editor' => '')).
                                $form->input('submit', 'Sauvegarder', array('type' => 'submit', 'class' => 'btn primary'))
                            )->end();
                        }
                    }
                    break;
            }
        }


        // On pense a l'envers ^^
        // On est obligé de pensé cat -> list
        $faq->setTable('FaqCat');

        $faqList = $faq->find(array(
            'fields' => 'FQ.id, question, reponse, active, catname, Faq.id AS cid',
            'join' => array(__SQL . '_Faq AS FQ' => 'FQ.cid = Faq.id'),
            'order' => 'cid ASC'
        ));

        $html->table(array('class' => 'table table-striped table-bordered'))
            ->tr()
            ->td('Questions fréquentes')->end()
            ->td(array('class' => 'span1', 'style' => 'text-align: center;'))
            ->a(array('href' => Router::url('faq/manager/action:make/id:0')), 'x')->end()
            ->end()
            ->end();

        $c=0;
        for($i=0;$i<count($faqList);$i++) {
            if ($c != $faqList[$i]->cid) {
                $c = $faqList[$i]->cid;
                $html->tr(array('class' => 'grd-blue color-white'))
                    ->td(clean($faqList[$i]->catname, 'str'))->end()
                    ->td(array('class' => 'span1', 'style' => 'text-align: center;'))
                    ->a(array('href' => Router::url('faq/manager/action:make/id:' . $c)), 'x')->end()
                    ->end()
                    ->end();
            }
            if (!is_null($faqList[$i]->question)) {
                $html->tr()
                    ->td(clean($faqList[$i]->question, 'str'))->end()
                    ->td(array('class' => 'span1', 'style' => 'text-align: center;'))
                    ->a(array('href' => Router::url('faq/manager/action:edit/id:' . $faqList[$i]->id)))
                    ->i(array('class' => 'icon-edit'))->end()
                    ->end()
                    ->span(' ')->end()
                    ->a(array('class' => 'dellink ' . $faqList[$i]->active,
                        'href' => Router::url('faq/manager/action:del/id:' . $faqList[$i]->id),
                        'style' => ($faqList[$i]->active == 'on') ? 'color:green' : 'color:red'))
                    ->i(array('class' => 'icon-trash'))->end()
                    ->end()
                    ->end()
                    ->end();
            }
        }

        $html->end();

        echo $html;
    }

}