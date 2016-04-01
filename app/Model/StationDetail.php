<?php
class StationDetail extends AppModel {
    public $useTable = 't_station_detail';
    public $validate = array(
        'station_tel'       =>  array(
            'required'      => array(
                'rule'      => 'notEmpty',
                'message'   => '電話番号を入力して下さい'
            ),
            'pattern'=>array(
                'rule'      => 'isPhoneNumber',
                'message'   => '電話番号を正しく入力してください',
            ),
        ),
        'station_fax'       =>  array(
            'pattern'=>array(
                'rule'      => 'isPhoneNumber',
                'message'   => '電話番号を正しく入力してください',
            ),
        ),
        'opening_date'      =>  array(
            'max'           => array(
                'rule'      => array('date', 'ymd'),
                'allowEmpty' => true,
                'message'   => '開設日には日付を入力して下さい'
            )
        ),
        'business_hour'     => array(
            'required'      => array(
                'rule'      => 'notEmpty',
                'message'   => '営業日時を入力して下さい'
            ),
            'max'           => array(
                'rule'      => array('between', 0, 64),
                'message'   => '営業日時は64文字以下で入力してください。'
            )
        ),
        'holiday'           => array(
            'required'      => array(
                'rule'      => 'notEmpty',
                'message'   => '定休日を入力して下さい'
            ),
            'max'           => array(
                'rule'      => array('between', 0, 128),
                'message'   => '定休日は128文字以下で入力してください。'
            )
        ),
         'core_time'         => array(
            'max'           => array(
                'rule'      => array('between', 0, 128),
                'message'   => '対応時間は128文字以下で入力してください。'
            )
        ),
        'transportation'    => array(
            'max'           => array(
                'rule'      => array('between', 0, 150),
                'message'   => '訪問移動手段は150文字以下で入力してください。'
            )
        ),
        'staff_com'         => array(
            'max'           => array(
                'rule'      => array('between', 0, 150),
                'message'   => 'スタッフコメントは150文字以下で入力してください。'
            )
        ),
        'visit_guidance_com'=> array(
            'max'           => array(
                'rule'      => array('between', 0, 256),
                'message'   => '補足事項 は256文字以下で入力してください。'
            )
        ),
        '24_hour_aday_com'=> array(
            'max'           => array(
                'rule'      => array('between', 0, 256),
                'message'   => '補足事項 は256文字以下で入力してください。'
            )
        ),
        'pal_care_com'=> array(
            'max'           => array(
                'rule'      => array('between', 0, 256),
                'message'   => '補足事項 は256文字以下で入力してください。'
            )
        ),
        'mental_care_com'=> array(
            'max'           => array(
                'rule'      => array('between', 0, 256),
                'message'   => '補足事項 は256文字以下で入力してください。'
            )
        ),
        'kids_care_com'=> array(
            'max'           => array(
                'rule'      => array('between', 0, 256),
                'message'   => '補足事項 は256文字以下で入力してください。'
            )
        ),
        'dementia_care_com'=> array(
            'max'           => array(
                'rule'      => array('between', 0, 256),
                'message'   => '補足事項 は256文字以下で入力してください。'
            )
        ),
        'handicapped_care_com'=> array(
            'max'           => array(
                'rule'      => array('between', 0, 256),
                'message'   => '補足事項 は256文字以下で入力してください。'
            )
        ),
        'visit_real_record'=> array(
            'max'           => array(
                'rule'      => array('between', 0, 256),
                'message'   => '補足事項 は256文字以下で入力してください。'
            )
        ),
        'other_license'=> array(
            'max'           => array(
                'rule'      => array('between', 0, 150),
                'message'   => '補足事項は150文字以下で入力してください。'
            )
        ),
        'other_info'=> array(
            'max'           => array(
                'rule'      => array('between', 0, 150),
                'message'   => 'その他の資格は150文字以下で入力してください。'
            )
        ),
    ); 
}