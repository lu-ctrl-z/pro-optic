<?php
class UserMailAuth extends AppModel {
    public $useTable = 't_user_mailauth';
    public $validate = array(
        'user_mail' => array(
            'NotEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'メールアドレスを入力してください。'
            ),
            'checkFormatMail' => array(
                'rule' => 'email',
                'message' => 'メールアドレスの書式が正しくありません'
            ),
            'checkMailaddress' => array(
                'rule'    => 'checkMailaddress',
                'message' => 'メールアドレスの書式が正しくありません'
            ),
        ),
    );
    /**
     * addMailAuth
     * @param String $user_mail
     */
    public function addMailAuth($DB, $user_mail) {
        //create unique_key random string
        while (true) {
            $random = Util::getRandom(60);
            $ret = $this->find('first', array('conditions' => array('unique_key' => $random)));
            if( empty($ret) ) break;
        }
        $aryUpdate['delete_date'] = $DB->value(Date('Y-m-d H:i:s'));
        $aryUpdateConditions['delete_date'] = null;
        $aryUpdateConditions['user_mail'] = $user_mail;
        $ret = $this->updateAll($aryUpdate, $aryUpdateConditions);
        if($ret === false) {
            $DB->rollback();
            return false;
        }
        $this->create();
        $this->set('user_mail', $user_mail);
        $this->set('unique_key', $random);
        $this->set('entry_date', Date('Y-m-d H:i:s'));
        if($this->save()) {
            return $random;
        } else {
            $DB->rollback();
            return false;
        }
    }
}