<?php
App::uses('SiteController', 'Controller/Base');
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
        $this->set('title_layout' , 'Đăng ký cửa hàng kinh doanh' );
        $this->set('description_layout' , 'đăng ký kinh doanh sản phẩm' );
        $this->set('keywords_layout' , 'đăng ký, kinh doanh, sản phẩm, tìm kiếm' );
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
        $this->set('title_layout' , '会員登録：メールアドレス確認 | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );
        if($this->errorCount() > 0) {
            $this->hkRender('entry', 'default');
        } else {
            $this->hkRender('entry_preview', 'default');
        }
    }
    /**
     * URL: /entry/do
     * send mail register
     */
    function entryDo() {
        $this->loadModel('User');
        $this->loadModel('UserMailAuth');
        if($this->CsrfSecurity->validateCsrf($this) === false) {
            $this->Session->setFlash('不正なリクエストです。手順の始めからやり直してください', 'default', array('class' => "form-error-message"), 'entry');
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
            $DB = $this->UserMailAuth->getDataSource();
            $DB->begin();
            $unique_key = $this->UserMailAuth->addMailAuth($DB, $user_mail);
            if($unique_key === false) {
                $this->addError('user_mail', 'DBの登録でエラーがありました。');
                $this->hkRender('entry', 'default');
            } else {
                try {
                    $subject = "[ho-kan.jp] 会員登録のご案内（訪問看護ステーションナビ）";
                    $mailAdmin = Configure::read('config_mail_admin');
                    $base_url = Configure::read('base_url');
                    $base_ssl_url = Configure::read('base_ssl_url');
                    // #134 Start Luvina Fix Bug 【Bug ID: 450】
                    $mailFrom = $mailAdmin['from'];
                    $aryMailTo = array($user_mail, $mailAdmin['to'] );
                    foreach ($aryMailTo as $mailTo) {
                        $this->sendMail($mailTo, $mailFrom, $subject, 'newentry', 
                            array('site_name' => $base_url,
                                  'signup_url' => $base_ssl_url.'/signup?u='.$unique_key), 'text');
                    }
                    // #134 End Luvina Fix Bug 【Bug ID: 450】
                    $DB->commit();
                    $this->redirect('/entry/send');
                } catch (Exception $e) {
                    $DB->rollback();
                    $this->addError('user_mail', 'メールの送信に失敗しました。お手数ですが今一度メールアドレスをご確認ください。');
                    $this->hkRender('entry', 'default');
                }
            }
        }
    }
    /**
     * URL: /entry/complete
     */
    function entrySend() {
        $this->set('title_layout' , '会員登録：メールアドレス送信 | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );
        $this->hkRender('entry_send', 'default');
    }
    /**
     * URL: /signup?u=unique_key
     */
    function signup() {
        $this->loadModel('User');
        $this->loadModel('UserMailAuth');
        $u = $this->get('u');
        if(!$u) $this->redirect('/');

        $validEntryDate = Date('Y-m-d H:i:s', strtotime('-1 days'));
        $data = $this->UserMailAuth->find('first', 
                array('conditions' => array('unique_key' => $u, 'delete_date' => null, 
                        'entry_date >= ' => $validEntryDate)));
        if(!$data) {
            $this->Session->delete('authorized_mail_address');
            $this->Session->delete('unique_key');
            $this->addError('error', 'エラーがありました');
            return $this->hkRender('form', 'portal');
        }
        $data = $data['UserMailAuth'];
        $user_mail = $data['user_mail'];
        $unique_key = $data['unique_key'];

        $user = $this->User->getUserByEmail($user_mail);
        if($user) {
            $this->addError('user_mail', 'このメールアドレスは既に登録されているため使用できません');
            return $this->hkRender('form', 'portal');
        }

        $this->Session->write('authorized_mail_address', $user_mail);
        $this->Session->write('unique_key', $unique_key);
        $base_ssl_url = Configure::read('base_ssl_url');
        return $this->redirect($base_ssl_url . '/aadd/');
    }

    /**
     * permissionNewentry
     * @return boolean
     */
    private function permissionNewentry() {
        $this->loadModel('User');
        $this->loadModel('UserMailAuth');
        $this->loadModel('Corporation');

        $base_ssl_url = Configure::read('base_ssl_url');
        $this->setApp('base_ssl_url', $base_ssl_url);

        $mail_address = $this->Session->read('authorized_mail_address');
        if (empty($mail_address)) {
            $this->addError('error', '認証されたメールアドレスが取得できません');
            return false;
        }

        $user = $this->User->getUserByEmail($mail_address);
        if($user) {
            $this->addError('error', 'このメールアドレスは既に登録されているため使用できません');
            return false;
        }

        $this->setApp('user_mail', $mail_address);
        $unique_key = $this->Session->read('unique_key');
        if (empty($unique_key)) {
            $this->addError('error', 'エラーがありました');
            return false;
        }

        $validEntryDate = Date('Y-m-d H:i:s', strtotime('-1 days'));
        $data = $this->UserMailAuth->find('first',
                array('conditions' => array('unique_key' => $unique_key, 'delete_date' => null,
                'entry_date >= ' => $validEntryDate)));
        if(!$data) {
            $this->addError('error', 'エラーがありました');
            return false;
        }
        return true;
    }
    /**
     * URL: /aadd
     * newentry form register
     */
    function newentryForm() {
        $this->permissionNewentry();
        $this->set('title_layout' , '管理会員登録：管理会員情報登録 | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );
        return $this->hkRender('form', 'portal');
    }
    /**
     * URL: /aadd/preview
     * newentry form confirm
     */
    function newentryPreview() {
        $isOK = $this->permissionNewentry();
        $this->set('title_layout' , '管理会員登録：管理会員情報登録確認 | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );
        if(!$isOK) {
            return $this->hkRender('form', 'portal');
        }
        $data = $this->data;
        $data['user_mail'] = $this->getApp('user_mail');

        $isOK1 = $this->User->isValidate($data);
        if(!$isOK1) {
            $this->addError($this->User->validationErrors);
        }
        $isOK2 = $this->Corporation->isValidate($data);
        if(!$isOK2) {
            $this->addError($this->Corporation->validationErrors);
        }
        if($this->errorCount() > 0) {
            return $this->hkRender('form', 'portal');
        }
        $this->setApp('data', $data);

        return $this->hkRender('form_preview', 'portal');
    }
    /**
     * URL: /aadd/send
     * newentry form do
     */
    function newentrySend() {
        $this->set('title_layout' , '管理会員登録：管理会員情報登録送信 | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );
        return $this->hkRender('form_send', 'portal');
    }
    /**
     * URL: /aadd/do
     * newentry form do
     */
     function newentryDo() {
         $isOK = $this->permissionNewentry();
         if(!$isOK) {
             return $this->hkRender('form', 'portal');
         }

         if($this->CsrfSecurity->validateCsrf($this) === false) {
             $this->Session->setFlash('不正なリクエストです。手順の始めからやり直してください', 'default', array('class' => "form-error-message"), 'entry');
             $this->redirect('/aadd');
         }

         $data = $this->data;
         $data['user_mail'] = $this->getApp('user_mail');
         $this->setApp('data', $data);

         $isOK1 = $this->User->isValidate($data);
         if(!$isOK1) {
             $this->addError($this->User->validationErrors);
         }
         $isOK2 = $this->Corporation->isValidate($data);
         if(!$isOK2) {
             $this->addError($this->Corporation->validationErrors);
         }
         if($this->errorCount() > 0) {
             return $this->hkRender('form', 'portal');
         }

         $DB = $this->User->getDataSource();
         $DB->begin();

         $corporationObj = $this->Corporation;
         $corporationObj->create();
         $corporationObj->set($data);
         $corporationObj->set('id', null);
         $corporationObj->set('com_CD', 0);
         $corporationObj->set('entry_date', Date('Y-m-d H:i:s'));
         $corporationObj->set('entry_user', 0);
         $corporationObj->set('update_user', 0);
         $created = $corporationObj->save();
         if(!$created) {
             $DB->rollback();
             $this->addError('error', 'DBの登録でエラーがありました。');
             return $this->hkRender('form', 'portal');
         }
         $com_CD = $created['Corporation']['id'];
         $corporationObj->set('com_CD', $com_CD);

         $userObj = $this->User;
         $userObj->create();
         $userObj->set($data);
         $userObj->set('id', null);
         $userObj->set('user_mode', 0);
         $userObj->set('com_CD', $com_CD);
         $userObj->set('entry_date', Date('Y-m-d H:i:s'));
         Security::setHash('md5');
         $password = Security::hash($data['password']);
         $userObj->set('password', $password);
         $userObj->set('password_confirm', $password);
         $userObj->set('entry_date', Date('Y-m-d H:i:s'));
         $created = $this->User->save();
         if(!$created) {
             $DB->rollback();
             $this->addError('error', 'DBの登録でエラーがありました。');
             return $this->hkRender('form', 'portal');
         }

         $createdId = $created['User']['id'];
         $this->User->set('entry_user', $createdId);
         $this->User->set('update_date', Date('Y-m-d H:i:s'));
         $this->User->set('update_user', $createdId);
         $updated = $this->User->save();
         if(!$updated) {
             $DB->rollback();
             $this->addError('error', 'DBの登録でエラーがありました。');
             return $this->hkRender('form', 'portal');
         }

         $corporationObj->set('entry_user', $createdId);
         $corporationObj->set('update_user', $createdId);
         $corporationObj->set('update_date', Date('Y-m-d H:i:s'));
         $created = $corporationObj->save();
         if(!$created) {
             $DB->rollback();
             $this->addError('error', 'DBの登録でエラーがありました。');
             return $this->hkRender('form', 'portal');
         }

         $aryUpdate['delete_date'] = $DB->value(Date('Y-m-d H:i:s'));
         $aryUpdateConditions['delete_date'] = null;
         $aryUpdateConditions['user_mail'] = $data['user_mail'];
         $ret = $this->UserMailAuth->updateAll($aryUpdate, $aryUpdateConditions);
         if($ret === false) {
             $DB->rollback();
             $this->addError('error', 'DBの登録でエラーがありました。');
             return $this->hkRender('form', 'portal');
         }

         $this->Session->delete('authorized_mail_address');
         $this->Session->delete('unique_key');

         try {
             $subject = "[ho-kan.jp] 会員登録完了のお知らせ（訪問看護ステーションナビ）";
             $mailAdmin = Configure::read('config_mail_admin');
             $base_url = Configure::read('base_url');
             $mailFrom = $mailAdmin['from'];
             // #134 Start Luvina Fix Bug 【Bug ID: 450】
             $aryMailTo = array($data['user_mail'], $mailAdmin['to'] );
             // #147 Start Luvina Modify
             foreach ($aryMailTo as $mailTo) {
                 $this->sendMail( $mailTo, $mailFrom, $subject, 'newentry_do', 
                            array('site_name' => $base_url, 
                                  'name_sei' => $data['name_sei'],
                                  'name_mei' => $data['name_mei'],
                                  'user_mail' => $data['user_mail'],
                                  'com_name' => $data['corporation_name']),
                                  'text');
             }
             // #147 End Luvina Modify
             // #134 Start Luvina Fix Bug 【Bug ID: 450】
         } catch (Exception $e) {
             $DB->rollback();
             $this->addError('error', 'メールの送信に失敗しました。お手数ですが今一度メールアドレスをご確認ください。');
             $this->hkRender('entry', 'default');
         }
         $DB->commit();
         $base_ssl_url = Configure::read('base_ssl_url');
         return $this->redirect($base_ssl_url . '/aadd/send');
     }
}