<?php
class permissionController extends Controller {
    public function index(){
        $page = Page::getInstance(); $page->setLayout('sd-admin');
        $session = Session::getInstance();
        $template = Template::getInstance();
        $acl = AccessControlList::getInstance();
        $perm = new PermissionsModel();

        $page->setPageTitle('Information serveur ');

        if (!$acl->isAllowed('permission.manager')) {
            $c = new errorController();
            return $c->e403();
        }

        debug($perm->getGroupList());
        $template->show('permission/index');
    }
}