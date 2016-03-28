<?php
App::uses('SiteController', 'Controller\Base');
/**
 * AdminController
 * @author Luvina
 * @access public
 * @see isLogined()
 * @see beforeRender()
 * @see disableCsrf()
 * @see enableCsrf()
 * @see beforeFilter()
 * @see checkLocalAccess()
 * @see loadConfig()
 */
class AdminController extends SiteController {

    /**
     * @var CsrfSecurityComponent
     */
    public $userInfo = null;

    /**
     * beforeRender
     * @author Luvina
     * @access public
     * @return
     */
    public function beforeRender() {
        $this->layout = 'admin_default';
        $this->set('request', $this->request);
    }

    /**
     * beforeFilter
     * @author Luvina
     * @access public
     * @return
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->page = $this->get('page');
        CakeSession::start();

        $controllerAdmin = Configure::read('admin_controller');
        $actionNotCheck = Configure::read('action_admin_login_not_check');
        $actionNotRedirect = Configure::read('action_admin_login_not_redirect');
        if (!in_array($this->name, $controllerAdmin) || (in_array($this->name, $controllerAdmin) && !in_array($this->action, $actionNotCheck))) {
            if (!$this->isLogined()) {
                $this->redirect('/login');
            }
        }

        if ($this->isLogined() && in_array($this->action, $actionNotRedirect)) {
            $this->redirect('/portal');
        }
    }
}