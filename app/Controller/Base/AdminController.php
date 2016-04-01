<?php
App::uses('SiteController', 'Controller/Base');
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
        $this->layout = 'portal';
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
            if (!$this->isLogin()) {
                $this->redirect('/login');
            }
        }

        if ($this->isLogin() && in_array($this->action, $actionNotRedirect)) {
            $this->redirect('/portal');
        }
    }
    // #144 Start Luvina Modify
    /**
     * setArrayMailConfig set data for mail config  when sendmail
     * @author Luvina
     * @access public
     * @return
     */
    public function setArrayMailConfig ($mail_to) {
        $mailAdmin = Configure::read('config_mail_admin');
        $aryTmpConfigMail = array('user_mail' => $mailAdmin['to'],
                                  'name_sei' => '',
                                  'name_mei' => '');
        foreach ($mail_to as $userInfo) {
            if($userInfo["user_mode"] == 2) {
                $aryTmpConfigMail['name_sei'] = $userInfo['name_sei'];
                $aryTmpConfigMail['name_mei'] = $userInfo['name_mei'];
            }
            if($userInfo["user_mode"] == 3) {
                $aryTmpConfigMail['name_sei'] = $userInfo['name_sei'];
                $aryTmpConfigMail['name_mei'] = $userInfo['name_mei'];
                break;
            }
        }
        $mail_to['mail_config'] = $aryTmpConfigMail;
        return $mail_to;
    }
    // #144 End Luvina Modify
}