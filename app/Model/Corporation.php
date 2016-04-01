<?php
class Corporation extends AppModel {
    public $useTable = 't_corporation';
    public $validate = array(
            'corporation_name' => array(
                    'required' => array(
                            'rule' => 'notEmpty',
                            'message' => '法人名を入力してください。'
                    ),
                   'max' => array (
                       'rule' => array ( 'between', 0, 64),
                       'message' => '法人名は64文字以下で入力してください。' 
                   )
            ),
            'corporation_name_kana' => array(
                    'required' => array(
                            'rule' => 'notEmpty',
                            'message' => '法人名（カナ）を入力してください。'
                    ),
                    'checkKana' => array(
                            'rule' => array('checkKatakana'),
                            'message' => '法人名（カナ）にカナ以外の文字が入力されています',
                    ),
                    'max' => array (
                       'rule' => array ( 'between', 0, 128),
                       'message' => '法人名（カナ）は128文字以下で入力してください。' 
                   )
            ),
            'corporation_zip' => array(
                    'required' => array(
                            'rule' => 'notEmpty',
                            'message' => 'zipを入力してください。'
                    ),
                    'max' => array (
                       'rule' => array ( 'between', 0, 16),
                       'message' => 'zipは16文字以下で入力してください。'
                   )
            ),
            'corporation_address' => array(
                    'required' => array(
                            'rule' => 'notEmpty',
                            'message' => '所在地を入力してください。'
                    ),
                    'max' => array (
                       'rule' => array ( 'between', 0, 128),
                       'message' => '所在地は128文字以下で入力してください。'
                   )
            ),
            'corporation_building' => array(
                   'max' => array (
                       'rule' => array ( 'between', 0, 128),
                       'message' => '建物名と階数は128文字以下で入力してください。' 
                   )
            ),
            'corporation_tel' => array(
                    'required' => array(
                            'rule' => 'notEmpty',
                            'message' => '代表電話番号を入力してください。'
                    ),
                    'pattern'=>array(
                            'rule'      => 'isPhoneNumber',
                            'message'   => '正しい代表電話番号を入力してください。',
                    ),
                    'max' => array (
                       'rule' => array ( 'between', 0, 16),
                       'message' => '代表電話番号は16文字以下で入力してください。'
                   )
            ),
    );
    // #147 Start Luvina Modify
    /**
     * get name corporation
     * @param string $com_CD
     * @return string
     */
    public function getCorporationName($com_CD) {
        $corporationName = '';
        $aryCorporation = $this->find('first', array( 'conditions' => array('com_CD' => $com_CD, 'delete_date' => null),
                                                      'fields' => array('corporation_name')));
        if(!empty($aryCorporation)) {
            $corporationName = $aryCorporation['Corporation']['corporation_name'];
        }
        return $corporationName;
    }
    // #147 End Luvina Modify
}