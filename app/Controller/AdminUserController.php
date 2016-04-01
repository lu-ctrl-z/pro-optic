<?php
App::uses('AdminController', 'Controller/Base');
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
    // #147 Start Luvina Modify
    public $uses = array('User', 'Corporation');
    // #147 End Luvina Modify
    /**
     * login
     * @author Luvina
     * @access public
     */
    public function login() {
        $this->set('title_layout' , '会員ログイン | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );
        $this->hkRender('login', 'portal');
    }
    /**
     * logout
     * @author Luvina
     * @access public
     */
    public function logout() {
        if(!$this->isLogin()) 
            return $this->redirect('/');

        $this->Session->delete(Configure::read('ss_auth'));
        $auto_login_key = Configure::read('auto_login_key');
        $auto_login_value = $this->Cookie->read($auto_login_key);
        if(!empty($auto_login_value)) {
            $this->loadModel('AutoLogin');
            $conditions['auto_login_key'] = $auto_login_value;
            $this->AutoLogin->deleteAll($conditions);
        }
        $this->Cookie->delete($auto_login_key);

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
            $this->Session->setFlash('不正なリクエストです。手順の始めからやり直してください', 'default', array('class' => 'form-error-message'), 'error_login');
            $this->redirect('/login');
        }

        $data = $this->data;
        $userMail = trim($data['user_mail']);
        $password = trim($data['password']);
        if($userMail == '' || $password == '') {
            $this->addError('error', 'メールアドレスとパスワードを入力してください。');
            return $this->hkRender('login', 'portal');
        }

        Security::setHash('md5');
        $password = Security::hash($password);
        $condition['conditions']= array(
            'user_mail' => $userMail,
            'password' => $password,
            'user_mode != ' => 0,
            'delete_date' => NULL,
        );

        $aryDataUser = $this->User->find('first', $condition);
        if(!empty($aryDataUser)) {
            $this->Session->write(Configure::read('ss_auth'), $aryDataUser['User']);
            //Store autologin 
            if(isset($data['auto_login']) && $data['auto_login'] == 1) {
                $this->loadModel('AutoLogin');
                $value = $this->AutoLogin->addAutoLogin($aryDataUser['User']['id']);
                $auto_login_key = Configure::read('auto_login_key');
                $auto_login_expires = Configure::read('auto_login_expires');
                if(!$auto_login_expires) $auto_login_expires = 30;
                if($value != false) {
                    $this->Cookie->write($auto_login_key, $value, false, "$auto_login_expires days");
                }
            }
            return $this->redirect('/portal');
        } else {
            $this->addError('error', '正しいメールアドレスとパスワードを入力してください。');
            return $this->hkRender('login', 'portal');
        }
    }
    /**
     * URL: /portal/ulist
     * @author Luvina
     * @access public
     */
    public function uList() {
        $this->loadConfig('User.ini.php');
        $this->setApp('user_mode', Configure::read('user_mode'));

        $user = $this->getUser();
        $com_CD = null;
        // #146 Start Luvina Modify
        $conditions = array('User.delete_date' => null,);
        // #135 Start Luvina Fix Bug 449
        $order = array('User.update_date DESC', 'User.entry_date DESC', 'User.id DESC');
        // #135 End Luvina Fix Bug 449
        $aryJoins = array();
        $keyWord = $this->get('keyword');
        $params = array();
        $aryConditionOr = array();
        if($keyWord != '') {
            $params[] = 'keyword=' . $keyWord;
            $aryConditionOr = array('User.name_sei like ' => '%'. $keyWord .'%', 
                                    'User.name_mei like ' => '%'. $keyWord .'%');
        }

        $text_placeholder = '会員名を入力';
        if($this->isAdmin()) {
            $com_CD = $user['com_CD'];
            $conditions['com_CD'] = $com_CD;
        } elseif($this->isSupperAdmin()) {
            if($keyWord != '') {
                $aryConditionOr['Corporation.corporation_name like '] = '%'. $keyWord .'%';
                $aryJoins = array(
                    array(
                        'table' => 't_corporation',
                        'alias' => 'Corporation',
                        'type' => 'INNER',
                        'conditions' => array(
                            "User.com_CD = Corporation.com_CD"
                        )
                    )
                );
                $conditions['Corporation.delete_date'] = null;
            }
            // #148 Start Luvina Modify
            $userMode = $this->get('user_mode');
            if($userMode != '') {
                $params[] = 'user_mode=' . $userMode;
                $conditions['User.user_mode'] = $userMode;
            }
            // #148 End Luvina Modify
            $text_placeholder = '会員名、法人を入力';
        } elseif($this->isMember()) {
            $conditions['id'] = $this->getUserId();
        }

        $limit = 10;
        $strParams = implode('&', $params);
        $url_params = array(
                'controller' => 'adminUser',
                'action' => 'uList',
                '?' => $strParams,
        );
        $conditions['OR'] = $aryConditionOr;
        $uCount = $this->User->find('count' , array('conditions' => $conditions, 'joins' => $aryJoins,));
        if ($uCount > 0) {
            Util::setPager('User', $uCount, $url_params, $limit, 9);
        }

        $offset = ($this->page - 1) * $limit;

        $aryFields = array('id', 'user_mail', 'name_sei', 'name_mei', 'user_mode', 'entry_date', 'update_date');
        $uList = $this->User->find('all' , array('',
                                                 'conditions' => $conditions,
                                                 'fields' => $aryFields,
                                                 'joins' => $aryJoins,
                                                 'order' => $order,
                                                 'limit' => $limit,
                                                 'offset' => $offset));
        $this->set('text_placeholder' , $text_placeholder);
        // #146 End Luvina Modify
        $this->setApp('uList', $uList);
        $this->set('title_layout' , '会員ポータル | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );
        $this->hkRender('ulist', 'portal');
    }
    /**
     * URL:/uadd/
     * @author Luvina
     * @access public
     */
    public function uAdd() {
        $this->nextUrl = Configure::read('base_ssl_url') . '/portal/uadd/';
        $this->permissionAdd();
        $this->set('title_layout' , '会員情報：登録 | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );
        $this->hkRender('uadd', 'portal');
    }

    /**
     * check permission Add User
     * @return void|boolean
     */
    private function permissionAdd() {
        if($this->isMember()) {
            return $this->redirect('/portal/');
        } elseif($this->isSupperAdmin()) {
            $instate_id = $this->get('instate_id');
            if($instate_id) {
                $instateUser = $this->User->find('first', array(
                        'conditions' => array('id' => $instate_id, 'user_mode' => 2, 'delete_date' => null)));
                if($instateUser) {
                    $this->instateCom_CD = $instateUser['User']['com_CD'];
                    return true;
                } else {
                    return $this->redirect('/portal/confirm/?next=' . rawurlencode($this->nextUrl));
                }
            } else {
                return $this->redirect('/portal/confirm/?next=' . rawurlencode($this->nextUrl));
            }
        }
        return true;
    }
    /**
     * URL: /uadd/preview
     */
    function uAddPreview() {
        $this->set('title_layout' , '会員情報：登録確認 | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );
        $this->nextUrl = Configure::read('base_ssl_url') . '/portal/uadd/';
        $this->permissionAdd();

        $data = $this->data;
        $this->setApp('data', $data);

        $isOK = $this->User->isValidate($data);
        if(!$isOK) {
            $this->addError($this->User->validationErrors);
        }

        if($data['user_mail']) {
            $user = $this->User->getUserByEmail($data['user_mail']);
            if($user) {
                $this->addError('user_mail', 'このメールアドレスは既に登録されているため使用できません');
            }
        }
        if($this->errorCount() > 0) {
            return $this->hkRender('uadd', 'portal');
        }
        return $this->hkRender('uadd_preview', 'portal');
    }
    /**
     * URL: /uadd/do
     */
    function uAddDo() {
        $this->set('title_layout' , '会員情報：登録確認 | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );
        $this->nextUrl = Configure::read('base_ssl_url') . '/portal/uadd/';
        $this->permissionAdd();

        $com_CD = null;
        if($this->isSupperAdmin() && $this->instateCom_CD) {
            $com_CD = $this->instateCom_CD;
        } elseif($this->isAdmin()) {
            $userLogin = $this->User->find('first', array('conditions' => array('id' => $this->getUserId(), 'delete_date' => null)));
            if(!$userLogin) {
                $this->logout();
            } else {
                $com_CD = $userLogin['User']['com_CD'];
            }
        } else {
            $this->addError('error', 'DBの登録でエラーがありました。');
            return $this->hkRender('uadd', 'portal');
        }

        $this->User->create();
        $data = $this->data;
        $this->setApp('data', $data);
        $data['com_CD'] = $com_CD;
        $isOK = $this->User->isValidate($data);
        if(!$isOK) {
            $this->addError($this->User->validationErrors);
        }

        if($data['user_mail']) {
            $user = $this->User->getUserByEmail($data['user_mail']);
            if($user) {
                $this->addError('error', 'このメールアドレスは既に登録されているため使用できません');
            }
        }

        if($this->errorCount() > 0) {
            return $this->hkRender('uadd', 'portal');
        }
        if($this->CsrfSecurity->validateCsrf($this) === false) {
            $this->Session->setFlash('不正なリクエストです。手順の始めからやり直してください', 'default', array('class' => "form-error-message"), 'entry');
            if($data['instate_id']) {
                $this->redirect('/portal/uadd/?instate_id='.$data['instate_id']);
            } else {
                $this->redirect('/portal/uadd');
            }
        }

        $DB = $this->User->getDataSource();
        $DB->begin();
        $this->User->set('id', null);
        $this->User->set('user_mode', 3);
        $this->User->set('com_CD', $com_CD);
        $this->User->set('entry_date', Date('Y-m-d H:i:s'));
        Security::setHash('md5');
        $password = Security::hash($data['password']);
        $this->User->set('password', $password);
        $this->User->set('password_confirm', $password);
        $created = $this->User->save();
        if(!$created) {
            $DB->rollback();
            $this->addError('error', 'DBの登録でエラーがありました。');
            return $this->hkRender('uadd', 'portal');
        }

        $createdId = $this->getUserId();
        $this->User->set('entry_user', $createdId);
        $this->User->set('update_date', Date('Y-m-d H:i:s'));
        $this->User->set('update_user', $createdId);
        $updated = $this->User->save();
        if(!$updated) {
            $DB->rollback();
            $this->addError('error', 'DBの登録でエラーがありました。');
            return $this->hkRender('uadd', 'portal');
        }
        $DB->commit();

        // #144 Start Luvina Modify
        $subject = "[ho-kan.jp] 一般会員登録完了のお知らせ（訪問看護ステーションナビ）";
        $mailAdmin = Configure::read('config_mail_admin');
        $base_url = Configure::read('base_url');
        $mailFrom = $mailAdmin['from'];
        $aryMailTo = array($data['user_mail'], $mailAdmin['to']);
        // #147 Start Luvina Modify
        $comName = $this->Corporation->getCorporationName($com_CD);
        foreach ($aryMailTo as $mailTo) {
            try {
                $this->sendMail( $mailTo, $mailFrom, $subject, 'uadd_do',  array('site_name' => $base_url,
                                                                                 'name_sei' => $data['name_sei'],
                                                                                 'name_mei' => $data['name_mei'],
                                                                                 'user_mail' => $data['user_mail'],
                                                                                 'password' => $data['password'],
                                                                                 'com_name' => $comName),
                                                                                 'text');
                } catch (Exception $e) { }
        }
        // #147 End Luvina Modify
        // #144 End Luvina Modify
        return $this->redirect('/portal/uadd/send');
    }

    /**
     * URL: /portal/uadd/send
     */
    function uAddSend() {
        $this->set('title_layout' , '会員情報：登録送信 | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );
        return $this->hkRender('uadd_send', 'portal');
    }
    /**
     * check permission edit user
     */
    private function permissionEdit() {
        $user_id = $this->get('id');
        if(!$user_id) return false;

        $conditions['id'] = $user_id;
        $conditions['delete_date'] = null;

        if($this->isAdmin()) {
            $userLogin = $this->getUser();
            $com_CD = $userLogin['com_CD'];
            $conditions['com_CD'] = $com_CD;
        } elseif($this->isMember() && ($user_id != $this->getUserId())) {
            return false;
        }

        $listUserInComCD = $this->User->find('first', array('conditions' => $conditions, 'fields' => array('id') ));
        if(empty($listUserInComCD)) return false;
        return true;
    }
    /**
     * URL: /portal/uedit
     */
    function uEdit() {
        $user_id = $this->get('id');

        $this->set('title_layout' , '会員情報：編集 | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );

        $yes = $this->permissionEdit();
        if($yes == false) {
            $this->addError('error', 'こちらのユーザは見つかりませんでした。お手数をおかけいたしますが、いま一度URLをご確認ください。');
            return $this->hkRender('uedit', 'portal');
        }

        $user = $this->User->find('first', array('conditions' => array(
                                'id' => $user_id,
                                'delete_date' => null) ));
        if(empty($user)) {
            $this->addError('error', 'こちらのユーザは見つかりませんでした。お手数をおかけいたしますが、いま一度URLをご確認ください。');
            return $this->hkRender('uedit', 'portal');
        }

        $this->setApp('user_mail', $user['User']['user_mail']);
        $user = $user['User'];
        unset($user['password']);
        $this->exportParams($user);
        if($user['user_mode'] == 2 || $user['user_mode'] == 0) {
            $this->setApp('isAdmin', true);
            $this->loadModel('Corporation');
            $fields = array_keys($this->Corporation->validate);
            $comData = $this->Corporation->find('first', array('conditions' => array('com_CD' => $user['com_CD']),
                   'fields' => $fields
            ));
            $comData = $comData['Corporation'];
            $this->exportParams($comData);
        }
        if($this->isSupperAdmin() && $user['user_mode'] == 0) {
            $this->setApp('changeUserMode', true);
        }
        return $this->hkRender('uedit', 'portal');
    }
    /**
     * URL: /portal/uedit/preview
     */
    function uEditPreview() {
        $this->set('title_layout' , '会員情報：編集 | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );
        $user_id = $this->get('id');
        if(!$user_id) {
            $this->addError('error', 'こちらのユーザは見つかりませんでした。お手数をおかけいたしますが、いま一度URLをご確認ください。');
            return $this->hkRender('uedit', 'portal');
        }

        $yes = $this->permissionEdit();
        if($yes == false) {
            $this->addError('error', 'こちらのユーザは見つかりませんでした。お手数をおかけいたしますが、いま一度URLをご確認ください。');
            return $this->hkRender('uedit', 'portal');
        }

        $user = $this->User->find('first', array('conditions' => array(
                'id' => $user_id,
                'delete_date' => null) ));
        if(empty($user)) {
            $this->addError('error', 'こちらのユーザは見つかりませんでした。お手数をおかけいたしますが、いま一度URLをご確認ください。');
            return $this->hkRender('uedit', 'portal');
        }
        $data = $this->data;
        if($user['User']['user_mode'] == 2 || $user['User']['user_mode'] == 0) {
            $this->setApp('isAdmin', true);
            $this->loadModel('Corporation');
            $isOK = $this->Corporation->isValidate($data);
            if(!$isOK) {
                $this->addError($this->Corporation->validationErrors);
            }
        }

        $this->setApp('user_mail', $user['User']['user_mail']);
        $field = $this->User->getField();
        unset($field['user_mail']);
        unset($field['com_CD']);
        if(!$data['password']) {
            unset($data['password']);
            unset($data['password_confirm']);
            unset($field['password']);
            unset($field['password_confirm']);
        }

        if($this->isSupperAdmin() && $user['User']['user_mode'] == 0) {
            $this->setApp('changeUserMode', true);
            if(!isset($data['user_mode'])) {
                $this->addError('user_mode', '承認 を入力してください。');
            } elseif(!($data['user_mode']==2 || $data['user_mode']==0)) {
                $this->addError('user_mode', '承認の値が0～2のいずれかであること。');
            }
        } else {
            unset($data['user_mode']);
        }
        $isOK = $this->User->isValidate($data, $field);
        $this->setApp('data', $data);
        if(!$isOK) {
            $this->addError($this->User->validationErrors);
        }
        if($this->errorCount() > 0) {
            return $this->hkRender('uedit', 'portal');
        }
        $this-> set('title_layout' , '会員情報：編集確認 | 訪問看護ステーションナビ' );
        return $this->hkRender('uedit_preview', 'portal');
    }
    /**
     * URL: /portal/uedit/do
     */
    function uEditDo() {
        $this->set('title_layout' , '会員情報：編集 | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );
        $user_id = $this->get('id');
        if(!$user_id) {
            $this->addError('error', 'こちらのユーザは見つかりませんでした。お手数をおかけいたしますが、いま一度URLをご確認ください。');
            return $this->hkRender('uedit', 'portal');
        }

        if($this->CsrfSecurity->validateCsrf($this) === false) {
            $this->Session->setFlash('不正なリクエストです。手順の始めからやり直してください', 'default', array('class' => "form-error-message"), 'message');
            return $this->redirect('/portal/uedit/?id='.$user_id);
        }

        $yes = $this->permissionEdit();
        if($yes == false) {
            $this->addError('error', 'こちらのユーザは見つかりませんでした。お手数をおかけいたしますが、いま一度URLをご確認ください。');
            return $this->hkRender('uedit', 'portal');
        }

        $user = $this->User->find('first', array('conditions' => array(
                'id' => $user_id,
                'delete_date' => null) ));
        if(empty($user)) {
            $this->addError('error', 'こちらのユーザは見つかりませんでした。お手数をおかけいたしますが、いま一度URLをご確認ください。');
            return $this->hkRender('uedit', 'portal');
        }

        $data = $this->data;
        $field = $this->User->getField();
        unset($field['user_mail']);
        unset($field['com_CD']);
        if(!$data['password']) {
            unset($data['password']);
            unset($data['password_confirm']);
            unset($field['password']);
            unset($field['password_confirm']);
        }
        if($this->isSupperAdmin() && $user['User']['user_mode'] == 0) {
            $this->setApp('changeUserMode', true);
            if(!isset($data['user_mode'])) {
                $this->addError('user_mode', '承認 を入力してください。');
            } elseif(!($data['user_mode']==2 || $data['user_mode']==0)) {
                $this->addError('user_mode', '承認の値が0～2のいずれかであること。');
            }
        } else {
            unset($data['user_mode']);
        }
        $userUpdate = $this->User;
        $DB = $userUpdate->getDataSource();
        $DB->begin();

        $isOK = $userUpdate->isValidate($data, $field);
        $userUpdate->set('id', $user_id);
        $this->setApp('data', $data);
        if(!$isOK) {
            $this->addError($this->User->validationErrors);
        }

        if($user['User']['user_mode'] == 2 || $user['User']['user_mode'] == 0) {
            $this->setApp('isAdmin', true);
            $this->loadModel('Corporation');
            $comData = $this->Corporation->find('first', array('conditions' => array('com_CD' => $user['User']['com_CD'])));
            $Corporation = $this->Corporation;
            $Corporation->set('id', $comData['Corporation']['id']);
            $isOK = $Corporation->isValidate($data);
            if(!$isOK) {
                $this->addError($this->Corporation->validationErrors);
            }
        }

        if($this->errorCount() > 0 ) {
            return $this->hkRender('uedit', 'portal');
        }

        if($user['User']['user_mode'] == 2 || $user['User']['user_mode'] == 0) {
            $Corporation->set('com_CD', $comData['Corporation']['com_CD']);
            $Corporation->set('entry_date', $comData['Corporation']['entry_date']);
            $Corporation->set('entry_user', $comData['Corporation']['entry_user']);
            $Corporation->set('update_date', Date('Y-m-d H:i:s'));
            $Corporation->set('update_user', $this->getUserId());
            $ok = $Corporation->save();
            if(!$ok) {
                $DB->rollback();
                $this->addError('error', 'DBの登録でエラーがありました。');
                return $this->hkRender('uedit', 'portal');
            }
        }
        if(isset($data['password'])) {
            Security::setHash('md5');
            $password = Security::hash($data['password']);
            $userUpdate->set('password', $password);
            $userUpdate->set('password_confirm', $password);
        }
        if($this->isSupperAdmin() && $user['User']['user_mode'] == 0) {
            $userUpdate->set('user_mode', $data['user_mode']);
        } else {
            $userUpdate->set('user_mode', $user['User']['user_mode']);
        }
        $userUpdate->set('user_mail', $user['User']['user_mail']);
        $userUpdate->set('update_date', Date('Y-m-d H:i:s'));
        $userUpdate->set('update_user', $this->getUserId());
        $userUpdate->set('com_CD', $user['User']['com_CD']);
        $ok = $userUpdate->save();
        if(!$ok) {
            $DB->rollback();
            $this->addError('error', 'DBの登録でエラーがありました。');
            return $this->hkRender('uedit', 'portal');
        }
        $DB->commit();
        // #144 Start Luvina Modify
        $subject = "[ho-kan.jp] 会員情報変更完了のお知らせ（訪問看護ステーションナビ）";
        $mailAdmin = Configure::read('config_mail_admin');
        $base_url = Configure::read('base_url');
        $mailFrom = $mailAdmin['from'];
        $aryMailTo = array($user['User']['user_mail'], $mailAdmin['to']);
        // #147 Start Luvina Modify
        $com_name = '';
        if(isset($data['corporation_name']) && $data['corporation_name'] != '') {
            $com_name = $data['corporation_name'];
        } else {
            $com_name = $this->Corporation->getCorporationName($user['User']['com_CD']);
        }
        foreach ($aryMailTo as $mailTo) {
            try {
                $this->sendMail( $mailTo, $mailFrom, $subject, 'uedit_do', 
                        array('site_name' => $base_url,
                              'name_sei' => $data['name_sei'],
                              'name_mei' => $data['name_mei'],
                              'com_name' => $com_name),
                              'text');
            } catch (Exception $e) {}
        }
        // #147 End Luvina Modify
        // #144 End Luvina Modify
        return $this->redirect('/portal/uedit/send');
    }

    /**
     * URL: /portal/uedit/send
     */
    function uEditSend() {
        $this->set('title_layout' , '会員情報：編集送信 | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );
        $this->hkRender('uedit_send', 'portal');
    }

    /**
     * URL: /portal/udel/
     */
    function uDel() {
        $this->set('title_layout' , '会員情報：削除 | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );

        $user_id = $this->get('id');

        $user = $this->User->find('first', array('conditions' => array(
                'id' => $user_id,
                'delete_date' => null) ));
        if(empty($user)) {
            $this->addError('error', 'こちらのユーザは見つかりませんでした。お手数をおかけいたしますが、いま一度URLをご確認ください。');
            return $this->hkRender('udel', 'portal');
        }

        $user = $user['User'];
        $yes = $this->permissionEdit();
        if($yes == false || ($user['user_mode'] == 2 && $this->isAdmin())) {
            $this->addError('error', 'こちらのユーザは見つかりませんでした。お手数をおかけいたしますが、いま一度URLをご確認ください。');
            return $this->hkRender('udel', 'portal');
        }

        unset($user['password']);
        $this->exportParams($user);
        if($user['user_mode'] == 2 || $user['user_mode'] == 0) {
            $this->setApp('isAdmin', true);
            $this->loadModel('Corporation');
            $fields = array_keys($this->Corporation->validate);
            $comData = $this->Corporation->find('first', array('conditions' => array('com_CD' => $user['com_CD']),
                    'fields' => $fields
            ));
            $comData = $comData['Corporation'];
            $this->exportParams($comData);
        }
        return $this->hkRender('udel', 'portal');
    }
    /**
     * @param array $mail_to
     */
    // #147 Start Luvina Modify
    private function _udelSendMail($mail_to, $com_name) {
        $subject = "[ho-kan.jp] 会員削除完了のお知らせ（訪問看護ステーションナビ）";
        $mailAdmin = Configure::read('config_mail_admin');
        $base_url = Configure::read('base_url');
        $mailFrom = $mailAdmin['from'];
        foreach ($mail_to as $userInfo) {
            try {
                $this->sendMail( $userInfo['user_mail'], $mailFrom, $subject, 'udel_do',
                        array(  'site_name' => $base_url,
                                'name_sei' => $userInfo['name_sei'],
                                'name_mei' => $userInfo['name_mei'],
                                'com_name' => $com_name ), 'text');
            } catch (Exception $e) {}
        }
    }
     // #147 End Luvina Modify
    /**
     * URL: /portal/udel/do
     */
    function uDelDo() {
        $this->set('title_layout' , '会員情報：削除 | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );
        $user_id = $this->get('id');

        $user = $this->User->find('first', array('conditions' => array(
                'id' => $user_id,
                'delete_date' => null) ));
        if(empty($user)) {
            $this->addError('error', 'こちらのユーザは見つかりませんでした。お手数をおかけいたしますが、いま一度URLをご確認ください。');
            return $this->hkRender('udel', 'portal');
        }
        $user = $user['User'];
        $yes = $this->permissionEdit();
        if($yes == false || ($user['user_mode'] == 2 && $this->isAdmin())) {
            $this->addError('error', 'こちらのユーザは見つかりませんでした。お手数をおかけいたしますが、いま一度URLをご確認ください。');
            return $this->hkRender('udel', 'portal');
        }

        if($this->CsrfSecurity->validateCsrf($this) === false) {
            $this->Session->setFlash('不正なリクエストです。手順の始めからやり直してください', 'default', array('class' => "form-error-message"), 'message');
            return $this->redirect('/portal/udel/?id='.$user_id);
        }

        $DB = $this->User->getDataSource();
        $DB->begin();
        $aryUpdate['delete_date'] = $DB->value(Date('Y-m-d H:i:s'));
        $aryUpdate['delete_user'] = $this->getUserId();
        $updateUserConditions['delete_date'] = null;

        $mail_to = array();
        // #147 Start Luvina Modify
        $com_name = $this->Corporation->getCorporationName($user['com_CD']);
        // #147 End Luvina Modify
        // #144 Start Luvina Modify
        $mailAdmin = Configure::read('config_mail_admin');
        $mail_to['mail_config'] = array( 'user_mail' => $mailAdmin['to'],
                                         'name_sei' => $user['name_sei'],
                                         'name_mei' => $user['name_mei']);
        // #144 End Luvina Modify
        if($user['user_mode'] == 0) {
            $updateUserConditions['id'] = $user['id'];
            $ok = $this->User->updateAll($aryUpdate, $updateUserConditions);
            if($ok === false) {
                $DB->rollback();
                $this->addError('error', 'DBの登録でエラーがありました。');
                return $this->hkRender('udel', 'portal');
            }

            $this->loadModel('Corporation');
            $updateCorporationConditions['com_CD'] = $user['com_CD'];
            $ok = $this->Corporation->updateAll($aryUpdate, $updateCorporationConditions);
            if($ok === false) {
                $DB->rollback();
                $this->addError('error', 'DBの登録でエラーがありました。');
                return $this->hkRender('udel', 'portal');
            }

            $DB->commit();
            $mail_to[$user['id']] = $user;
            // #147 Start Luvina Modify
            $this->_udelSendMail($mail_to, $com_name);
            // #147 End Luvina Modify
            return $this->redirect('/portal/udel/send/');
        } elseif($user['user_mode'] == 1) {
            return $this->redirect('/portal/');
        } elseif($user['user_mode'] == 2) {
            $updateUserConditions['com_CD'] = $user['com_CD'];
            $listUserDeleted = $this->User->find('all', array('conditions' => $updateUserConditions));
            foreach ($listUserDeleted as $userInfo) {
                $mail_to[$userInfo['User']['id']] = $userInfo['User'];
            }

            $ok = $this->User->updateAll($aryUpdate, $updateUserConditions);
            if($ok === false) {
                $DB->rollback();
                $this->addError('error', 'DBの登録でエラーがありました。');
                return $this->hkRender('udel', 'portal');
            }

            $this->loadModel('Corporation');
            $updateCorporationConditions['com_CD'] = $user['com_CD'];
            $ok = $this->Corporation->updateAll($aryUpdate, $updateCorporationConditions);
            if($ok === false) {
                $DB->rollback();
                $this->addError('error', 'DBの登録でエラーがありました。');
                return $this->hkRender('udel', 'portal');
            }
            //delete station
            $this->loadModel('StationProfile');
            $ok = $this->StationProfile->deleteStationByComCD($user['com_CD'], $aryUpdate);
            if(!$ok) {
                $DB->rollback();
                $this->addError('error', 'DBの登録でエラーがありました。');
                return $this->hkRender('udel', 'portal');
            }

            $DB->commit();
            // #147 Start Luvina Modify
            $this->_udelSendMail($mail_to, $com_name);
            // #147 End Luvina Modify
            return $this->redirect('/portal/udel/send/');
        } elseif($user['user_mode'] == 3) {
            $updateUserConditions['id'] = $user['id'];
            $mail_to[$user['id']] = $user;
            $ok = $this->User->updateAll($aryUpdate, $updateUserConditions);
            if($ok === false) {
                $DB->rollback();
                $this->addError('error', 'DBの登録でエラーがありました。');
                return $this->hkRender('udel', 'portal');
            }
            /* $this->loadModel('StationProfile');
            $ok = $this->StationProfile->deleteStationByUserId($user['id'], $aryUpdate);
            if($ok === false) {
                $DB->rollback();
                $this->addError('error', 'DBの登録でエラーがありました。');
                return $this->hkRender('udel', 'portal');
            } */
            $DB->commit();
            // #147 Start Luvina Modify
            $this->_udelSendMail($mail_to, $com_name);
            // #147 End Luvina Modify
            return $this->redirect('/portal/udel/send/');
        }

        return $this->redirect('/portal/');
    }
}