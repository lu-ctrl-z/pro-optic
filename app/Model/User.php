<?php
App::import('model', 'Corporation');
class User extends AppModel {
    public $useTable = 't_user';
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
        'name_sei' => array(
            'NotEmpty' => array(
                'rule' => 'notEmpty',
                'message' => '姓を入力してください。'
            ),
            'CheckLength' => array(
                 'rule' => array('between', 0, 32),
                 'message' => '姓は32文字以下で入力してください。'
             ),
        ),
        'name_mei' => array(
            'NotEmpty' => array(
                'rule' => 'notEmpty',
                'message' => '名を入力してください。'
            ),
            'CheckLength' => array(
                'rule' => array('between', 0, 32),
                'message' => '名は32文字以下で入力してください。'
            ),
        ),
        'name_kana_sei' => array(
            'CheckLength' => array(
                'rule' => array('between', 0, 32),
                'message' => '姓（カナ）は32文字以下で入力してください。'
            ),
            'checkKana' => array(
                 'rule' => array('checkKatakana'),
                 'message' => '姓（カナ）にかな以外の文字が入力されています',
            ),
       ),
       'name_kana_mei' => array(
            'CheckLength' => array(
                'rule' => array('between', 0, 32),
                'message' => '名（カナ）は32文字以下で入力してください。'
            ),
            'checkKana' => array(
                 'rule' => array('checkKatakana'),
                 'message' => '名（カナ）にカナ以外の文字が入力されています',
            ),
       ),
       'department' => array(
            'NotEmpty' => array(
                'rule' => 'notEmpty',
                'message' => '所属部署を入力してください。'
            ),
            'CheckLength' => array(
                'rule' => array('between', 0, 128),
                'message' => '所属部署は128文字以下で入力してください。'
            ),
       ),
       'tel' => array(
            'NotEmpty' => array(
                'rule' => 'notEmpty',
                'message' => '電話番号を入力してください。'
            ),
            'pattern'=>array(
                   'rule'      => 'isPhoneNumber',
                   'message'   => '正しい電話番号を入力してください。',
            ),
       ),
       'fax' => array(
            'pattern'=>array(
                   'rule'      => 'isPhoneNumber',
                   'message'   => '正しいFAX番号を入力してください。',
            ),
       ),
       'password' => array(
            'NotEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'パスワードを入力してください。'
            ),
       ),
       'password_confirm' => array(
            'NotEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'パスワード（確認）を入力してください。'
            ),
            'CheckValue' => array(
                'rule'    => 'checkPassword',
                'message' => 'パスワード（確認）が確認用パスワードと一致しません'
            )
       ),
    );

    /**
     * check Password
     * @param unknown $data
     * @return boolean
     */
    function checkPassword($data){
        $value = array_values($data);
        $comparewithvalue = $value[0];
        return ($this->data[$this->name]['password'] == $comparewithvalue);
    }

    /**
     * inCorporation
     * @param unknown $val
     */
    function inCorporation($val) {
        $com_CD = array_values($val);
        $corporation = new Corporation();
        $com = $corporation->find('first', array(
                'conditions' => array('com_CD' => $com_CD, 'delete_date' => null)));
        if(!$com) return false;
        return true;
    }

    /**
     * get User by email address
     * @param String $user_mail
     * @return data user
     */
    public function getUserByEmail($user_mail) {
        $user_mail = trim($user_mail);
        return $this->find('first', array(
                    'conditions' => array('user_mail' => $user_mail, 'delete_date' => null)));
    }
}