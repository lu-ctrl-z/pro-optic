<?php
App::uses('SiteController', 'Controller\Base');
/**
 * HomeController
 * @author Luvina
 * @access public
 * @see index()
 */
class HomeController extends SiteController {

    /**
     * index
     * @author Luvina
     * @access public
     */
    public function index() {
        $this->hkRender('index', 'default');
    }
}