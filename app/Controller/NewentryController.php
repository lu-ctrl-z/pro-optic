<?php
App::uses('SiteController', 'Controller\Base');
/**
 * UserController
 * @author Luvina
 * @access public
 * @see entry()
 * @see entryPreview()
 * @see entrySend()
 */
class NewentryController extends SiteController {
    /**
     * URL: /entry
     * show form entry
     */
    function entry() {
        $this->hkRender('entry', 'default');
    }
    /**
     * URL: /entry/preview
     * validate entry data en show data user input
     */
    function entryPreview() {
        $this->loadModel('User');
        $this->loadModel('UserMailAuth');

        $user_mail = $this->get('user_mail');
        $app['user_mail'] = $user_mail;
        $this->set('app', $app);

        $user = $this->User->getUserByEmail($user_mail);
        if($user) {
            $this->addError('user_mail', 'このメールアドレスは既に登録されているため使用できません');
        } else {
            $this->UserMailAuth->set('user_mail', $user_mail);
            $isOK = $this->UserMailAuth->validates();
            if(!$isOK) {
                $this->addError($this->UserMailAuth->validationErrors);
            }
        }
        if($this->errorCount() > 0) {
            $this->hkRender('entry', 'default');
        } else {
            $this->hkRender('entry_preview', 'default');
        }
    }
    /**
     * URL: /entry/send
     * send mail register
     */
    function entrySend() {
        $this->loadModel('User');
        $this->loadModel('UserMailAuth');
        if($this->CsrfSecurity->validateCsrf($this) === false) {
            $this->Session->setFlash('不正なリクエストです。手順の始めからやり直してください', 'default', array(), 'entry');
            $this->redirect('/entry/');
        }

        $user_mail = $this->get('user_mail');
        $app['user_mail'] = $user_mail;
        $this->set('app', $app);

        $user = $this->User->getUserByEmail($user_mail);
        if($user) {
            $this->addError('user_mail', 'このメールアドレスは既に登録されているため使用できません');
        } else {
            $this->UserMailAuth->set('user_mail', $user_mail);
            $isOK = $this->UserMailAuth->validates();
            if(!$isOK) {
                $this->addError($this->UserMailAuth->validationErrors);
            }
        }
        if($this->errorCount() > 0) {
            $this->hkRender('entry', 'default');
        } else {
            $unique_key = $this->UserMailAuth->addMailAuth($user_mail);
            if($unique_key === false) {
                $this->addError('user_mail', 'DBの登録でエラーがありました。');
                $this->hkRender('entry', 'default');
            } else {
                $subject = "NEWENTRY薬局・薬剤師をご利用してみてのエピソード";
                $mailAdmin = Configure::read('config_mail_admin');
                $base_ssl_url = Configure::read('base_ssl_url');
                $mailTo = $mailAdmin['to'];
                $mailFrom = $mailAdmin['from'];
                $this->sendMail($user_mail, $mailFrom, $subject, 'newentry', 
                        array('site_name' => $base_ssl_url, 
                              'signup_url' => $base_ssl_url.'/signup?u='.$unique_key), 'text');
                $this->redirect('/entry/complete');
            }
        }
    }
    /**
     * URL: /entry/complete
     */
    function entryComplete() {
        $this->hkRender('entry_complete', 'default');
    }
    /**
     * URL: /signup?u=unique_key
     */
    function signup() {
        $this->loadModel('User');
        $this->loadModel('UserMailAuth');
        $u = $this->get('u');
        if(!$u) $this->redirect('/');

        $data = $this->UserMailAuth->find('first', 
                array('conditions' => array('unique_key' => $u, 'delete_date' => null)));
        if(!$data) {
            $this->redirect('/');
        }
        $data = $data['UserMailAuth'];
        $user_mail = $data['user_mail'];
        $unique_key = $data['unique_key'];

        $user = $this->User->getUserByEmail($user_mail);
        if($user) {
            $this->addError('user_mail', 'このメールアドレスは既に登録されているため使用できません');
            $this->hkRender('signup', 'default');
            return;
        }

        $this->Session->write('authorized_mail_address', $user_mail);
        $this->Session->write('unique_key', $unique_key);
        $base_ssl_url = Configure::read('base_ssl_url');
        $this->redirect($base_ssl_url . '/aadd/');
    }
    /**
     * URL: /aadd
     * newentry form register
     */
    function newentryForm() {
        $this->loadModel('User');
        $this->loadModel('UserMailAuth');
        $this->loadModel('Corporation');

        $mail_address = $this->Session->read('authorized_mail_address');
        if (empty($mail_address)) {
            $this->addError('error', '認証されたメールアドレスが取得できません');
            return $this->hkRender('form', 'default');
        }
        $user = $this->User->getUserByEmail($mail_address);
        if($user) {
            $this->addError('error', 'このメールアドレスは既に登録されているため使用できません');
            return $this->hkRender('form', 'default');
        }
        $this->setApp('user_mail', $mail_address);

        $unique_key = $this->Session->read('unique_key');
        if (empty($unique_key)) {
            $this->addError('error', 'エラーがありました');
            return $this->hkRender('form', 'default');
        }
        $this->setApp('unique_key', $unique_key);

        $base_ssl_url = Configure::read('base_ssl_url');
        $this->setApp('base_ssl_url', $base_ssl_url);

        return $this->hkRender('form', 'default');
        //$this->af->set('mail_address', $mail_address);
    }
}