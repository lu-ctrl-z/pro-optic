<?php
class StationPublic extends AppModel {
    public $useTable = 't_station_public';
    public $validate = array(
            'station_name' => array (
                    'required' => array (
                            'rule' => 'notEmpty',
                            'message' => '店舗名を入力してください。'
                    ),
                    'max' => array (
                            'rule' => array ( 'between', 0, 128  ),
                            'message' => '店舗名は128文字以下で入力してください。'
                    )
            ),
            'zip' => array (
                    'required' => array (
                            'rule' => 'notEmpty',
                            'message' => '郵便番号を入力してください。'
                    ),
                    'max' => array (
                            'rule' => array ( 'between', 0, 16  ),
                            'message' => '郵便番号は16文字以下で入力してください。'
                    )
            ),
            'address' => array (
                    'required' => array (
                            'rule' => 'notEmpty',
                            'message' => '所在地を入力してください。'
                    ),
                    'max' => array (
                            'rule' => array ( 'between', 0, 128  ),
                            'message' => '所在地は128文字以下で入力してください。'
                    )
            ),
            'station_tel'       =>  array(
                    'required'      => array(
                            'rule'      => 'notEmpty',
                            'message'   => '電話番号を入力して下さい。'
                    ),
                    'max'           => array(
                            'rule'      => array('between', 0, 16),
                            'message'   => '電話番号は16文字以下で入力してください。'
                    )
            ),
            'opening_date'    => array(
                    'max'           => array(
                            'rule'      => array('between', 0, 128),
                            'message'   => '開設日は128文字以下で入力してください。'
                    )
            ),
    );
}