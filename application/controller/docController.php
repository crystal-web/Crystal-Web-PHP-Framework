<?php
class docController extends Controller {
    public function index() {
        $template = Template::getInstance();
        $page = Page::getInstance();
        $page->setPageTitle("Typographie");

        $template->show('doc/typographie');
    }

    public function style() {
        $template = Template::getInstance();
        $template->show('doc/style');
    }
}