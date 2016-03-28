<?php
App::uses('AdminController', 'Controller\Base');
/**
 * LoginController
 * @author Luvina
 * @access public
 * @see login()
 * @see logout()
 * @see auth()
 * @see index()
 * @see auth()
 */
class AdminUserController extends AdminController {
    public $uses = array('User');
    /**
     * login
     * @author Luvina
     * @access public
     */
    public function login() {
        $userLogin = $this->Session->read('user_login');
        $this->set('user_login', $userLogin);
    }
    /**
     * logout
     * @author Luvina
     * @access public
     */
    public function logout() {
        $this->Session->delete(Configure::read('ss_auth'));
        $this->redirect('/');
    }
    /**
     * auth
     * @author Luvina
     * @access public
     */
    public function auth() {
        $this->loadModel('User');
        if($this->CsrfSecurity->validateCsrf($this) === false) {
            $this->Session->setFlash('不正なリクエストです。手順の始めからやり直してください', 'default', array(), 'error_login');
            $this->redirect('/login');
        }
        $this->Session->write('user_login', $this->request->data);
        $data = $this->data;
        $userMail = trim($data['user_mail']);
        $password = trim($data['password']);
        if($userMail == '' || $password == '') {
            $this->Session->setFlash('メールアドレスとパスワードを入力してください。', 'default', array(), 'error_login');
            $this->redirect($this->referer());
        }
        Security::setHash('md5');
        $password = Security::hash($password);
        $condition['conditions']= array(
            'User.user_mail' => $userMail,
            'User.password' => $password,
            'User.user_mode != ' => 0,
            'User.delete_date' => NULL,
            'User.delete_user' => NULL,
        );
        $aryDataUser = $this->User->find('first', $condition);
        if(!empty($aryDataUser)) {
            $this->Session->write(Configure::read('ss_auth'), $aryDataUser['User']);
            $this->redirect('/portal');
        } else {
            $this->Session->setFlash('正しいメールアドレスとパスワードを入力してください。', 'default', array(), 'error_login');
            $this->redirect($this->referer());
        }
    }
    /**
     * index
     * @author Luvina
     * @access public
     */
    public function index() {
    }
    /**
     * add
     * @author Luvina
     * @access public
     */
    public function add() {
        $aryData = $this->data;
        $this->set('aryData', $aryData);
    }
    /**
     * Confirm Add Contactor
     * @author Luvina
     * @access public
     */
    public function addConfirm() {
        $aryData = $this->data;
        $isOK = $this->User->validateData($aryData);
        if($isOK) {
        
        } else {
            $this->addError($this->User->validationErrors);
        }
    }
}