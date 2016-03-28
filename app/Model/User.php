<?php
class User extends AppModel {
    public $useTable = 't_user';
    private $aryColumn = array(
            'user_mail',
            'name_sei',
            'name_mei',
            'name_kana_sei',
            'name_kana_mei',
            'department',
            'tel',
            'fax',
            'password',
    );
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
        ),
        'name_sei' => array(
            'NotEmpty' => array(
                'rule' => 'notEmpty',
                'message' => '正しい姓を入力してください。'
            ),
            'CheckLength' => array(
                 'rule' => array('between', 0, 32),
                 'message' => '姓は32文字以下で入力してください。'
             ),
        ),
        'name_mei' => array(
            'NotEmpty' => array(
                'rule' => 'notEmpty',
                'message' => '正しい名を入力してください。'
            ),
            'CheckLength' => array(
                'rule' => array('between', 0, 32),
                'message' => '名は32文字以下で入力してください。'
            ),
        ),
        'name_kana_sei' => array(
            'NotEmpty' => array(
                'rule' => 'notEmpty',
                'message' => '正しい姓（カナ）を入力してください。'
            ),
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
            'NotEmpty' => array(
                'rule' => 'notEmpty',
                'message' => '正しい名（カナ）を入力してください。'
            ),
            'CheckLength' => array(
                'rule' => array('between', 0, 32),
                'message' => '名（カナ）は32文字以下で入力してください。'
            ),
            'checkKana' => array(
                 'rule' => array('checkKatakana'),
                 'message' => '名（カナ）にかな以外の文字が入力されています',
            ),
       ),
       'department' => array(
            'CheckLength' => array(
                'rule' => array('between', 0, 128),
                'message' => '所属部署は128文字以下で入力してください。'
            ),
       ),
       'tel' => array(
            'NotEmpty' => array(
                'rule' => 'notEmpty',
                'message' => '正しい電話番号を入力してください。'
            ),
            'CheckLength' => array(
                'rule' => array('between', 0, 16),
                'message' => '電話番号は16文字以下で入力してください。'
            ),
            'pattern'=>array(
                   'rule'      => '/^0(([5789]0[0-9]{8})|([0-4|6]{1}[0-9]{8})|([7-9|5]{1}[1-9]{1}[0-9]{7}))$/',
                   'message'   => '電話番号を正しく入力してください',
            ),
       ),
       'fax' => array(
            'CheckLength' => array(
                'rule' => array('between', 0, 16),
                'message' => 'FAX番号は16文字以下で入力してください。'
            ),
       ),
       'password' => array(
            'NotEmpty' => array(
                'rule' => 'notEmpty',
                'message' => '正しいパスワードを入力してください。'
            ),
            'CheckLength' => array(
                'rule' => array('between', 0, 32),
                'message' => 'パスワード(hash)は32文字以下で入力してください。'
            ),
       ),
       'password_confrim' => array(
            'NotEmpty' => array(
                'rule' => 'notEmpty',
                'message' => '正しいパスワードを入力してください。'
            ),
            'CheckLength' => array(
                'rule' => array('between', 0, 32),
                'message' => 'パスワード(hash)は32文字以下で入力してください。'
            ),
            'CheckValue' => array(
                'rule' => array('between', 0, 32),
                'message' => 'パスワード(hash)は32文字以下で入力してください。'
            ),
       ),
    );
   /**
     * validate Data
     * @param array $aryData
     * @author Luvina
     * @return boolean
     */
    public function validateData($aryData){
        if(is_array($aryData) && (count($aryData) > 0)) {
            foreach ($this->aryColumn as $key) {
                $this->set($key, $aryData[$key]);
            }
        }
        $isValid = $this->validates($this->aryColumn);
        return $isValid;
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