<?php
//define csv HYN
// #102 Start Luvina Modify
App::import('model','DrugStoreAddDetail');
// #118 Start Luvina Modify
App::import('model','DrugstoreUniquekey');
// #118 End Luvina Modify
$i=0;
define("KUBUN_HYN_VALUE",     1);
define("KEY_NAME",      0);
define("KEY_VALUE",      1);
define("MED_INST_CODE", $i++);
define("COM_CD", $i++);
define("COM_CONTACT_CD", $i++);
define("CHAIN_DRUGSTORE_CD", $i++);
define("DRUGSTORE_CD", $i++);
define("DRUGSTORE_MNG_CD", $i++);
define("DRUGSTORE_NAME", $i++);
define("DRUGSTORE_NAME_YOMI", $i++);
define("TITLE", $i++);
define("DRUGSTORE_EXPL", $i++);
define("PIC_POSITION", $i++);
define("PIC_NAME", $i++);
define("PIC_NAME_YOMI", $i++);
define("DRUGSTORE_ADDRESS", $i++);
define("DRUGSTORE_FAX", $i++);
define("DRUGSTORE_TEL", $i++);
define("IMPLE_AREA", $i++);
define("VISIT_AVAIL_DATE", $i++);
define("VISIT_GUIDANCE", $i++);
define("PAL_CARE", $i++);
define("DISCHARGE_CONF_JOIN", $i++);
define("OTHER_INFO", $i++);
define("DRUG_STORAGE1", $i++);
define("DRUG_STORAGE2", $i++);
define("DRUG_STORAGE3", $i++);
define("ASEPTIC_HANDLE1", $i++);
define("ASEPTIC_HANDLE2", $i++);
define("ASEPTIC_HANDLE3", $i++);
define("ASEPTIC_HANDLE4", $i++);
define("ASEPTIC_HANDLE5", $i++);
define("_24_HOUR_ADAY", $i++);
define("SHIPPING_SYS", $i++);
define("VISIT_PHARMACIST_NUM", $i++);
define("SKILL_1", $i++);
define("SKILL_1_PPLE_NUM", $i++);
define("SKILL_2", $i++);
define("SKILL_2_PPLE_NUM", $i++);
define("SKILL_3", $i++);
define("SKILL_3_PPLE_NUM", $i++);
define("VISIT_REAL_RECORD", $i++);
define("STAFF_CM", $i++);
define("BUSINESS_HOUR", $i++);
define("MED_PRODUCT", $i++);
define("PAL_CARE_REAL_RECORD", $i++);
define("DRUG_ADD", $i++);
define("ASEPTIC_ADD", $i++);
define("_24_HOUR_ADAY_ADD", $i++);
define("VISIT_DAY_ADD", $i++);
define("LATITUDE", $i++);
define("LONGITUDE", $i++);
define("SCOPE", $i++);

define("VISIT_SUBJECT", $i++);
define("VISIT_SUBJECT_ADD", $i++);
define("NON_DISPENSING1", $i++);
define("NON_DISPENSING2", $i++);
define("NON_DISPENSING3", $i++);
define("NON_DISPENSING4", $i++);
define("NON_DISPENSING5", $i++);
define("NON_DISPENSING6", $i++);
define("CHILD_SPT", $i++);
define("CHILD_SPT_ADD", $i++);
define("DEMENTIA_SPT", $i++);
define("DEMENTIA_SPT_ADD", $i++);
define("PREF_CD", $i++);
define("EXPIRATION_DATE", $i++);
define("WATCH_TEAM", $i++);
define("REGISTRATION1", $i++);
define("REGISTRATION2", $i++);
define("REGISTRATION3", $i++);

//Define Column CSV
define("COLUMN_HYN", $i++);
define("COLUMN_MARON", 7);
//Define csv Maron
define("MARON_INSR_TYPE",      0);
define("MARON_PREF_CODE",      1);
define("MARON_MED_INST_CODE",      2);
define("MARON_DRUGSTORE_NAME",      3);
define("MARON_POSTCODE",      4);
define("MARON_DRUGSTORE_ADDRESS",      5);
define("MARON_DRUGSTORE_TEL",      6);
// #102 End Luvina Modify
// #10 bugId-216 Start Luvina Modify
define("MSG_MED_INST_CODE_SINGEL", '正しい医療機関番号を指定してください。');
define("MSG_MED_INST_CODE_MULTY", 'の 医療機関番号をご確認ください。');
// #10 bugId-216 End Luvina Modify
/**
 * DrugStore
 * @author luvina
 * @access public
 * @see validateDataCsv()
 * @see insertToDrugStore()
 * @see updateDrugStore()
 * @see deleteDrugStore()
 */
class DrugStore extends AppModel{
    public  $useTable = 't_drugstore';
    private $aryUniqueList = array();
    public  $user_id;
    public  $index = 0;
    public  $total = null;
    public  $errors = array();
    public  $warning = array();
    public  $prefectureList = array();
    public  $aryValidateConfig = array();
    public  $fieldListInsertUpdate = array(
                'kubun',
                'med_inst_code',
                'com_CD',
                'com_contact_CD',
                'chain_drugstore_CD',
                'drugstore_CD',
                'drugstore_mng_CD',
                'drugstore_name',
                'drugstore_name_yomi',
                'title',
                'drugstore_expl',
                'pic_position',
                'pic_name',
                'pic_name_yomi',
                'drugstore_address',
                'drugstore_fax',
                'drugstore_tel',
                'imple_area',
                'visit_avail_date',
                'visit_guidance',
                'pal_care',
                'discharge_conf_join',
                'other_info',
                'drug_storage',
                'aseptic_handle',
                '24_hour_aday',
                'shipping_sys',
                'visit_pharmacist_num',
                'skill_1',
                'skill_1_pple_num',
                'skill_2',
                'skill_2_pple_num',
                'skill_3',
                'skill_3_pple_num',
                'visit_real_record',
                'staff_cm',
                'business_hour',
                'med_product',
                'pal_care_real_record',
                'drug_add',
                'aseptic_add',
                '24_hour_aday_add',
                'visit_day_add',
                'latitude',
                'longitude',
                'scope',
                'entry_date',
                'entry_user',
                'flag_set',
                // #106 Start Luvina Modify
                'display_type',
                // #106 End Luvina Modify
            );
    public $fieldListInsertMaron = array(
                'kubun',
                'insr_type',
                'maron_pref_code',
                'med_inst_code',
                'drugstore_name',
                'maron_postcode',
                'drugstore_address',
                'drugstore_tel',
                'entry_date',
                'entry_user',
                // #106 Start Luvina Modify
                'display_type',
                // #106 End Luvina Modify
            );
    // #102 Start Luvina Modify
    public $fieldListInsertAddDetail = array(
                'drugstore_CD',
                'visit_subject',
                'visit_subject_add',
                'non_dispensing1',
                'non_dispensing2',
                'non_dispensing3',
                'non_dispensing4',
                'non_dispensing5',
                'non_dispensing6',
                'child_spt',
                'child_spt_add',
                'dementia_spt',
                'dementia_spt_add',
                'pref_CD',
                'expiration_date',
                'watch_team',
                'registration1',
                'registration2',
                'registration3',
                'entry_date',
                'entry_user',
            );
    // #102 End Luvina Modify
    // #118 Start Luvina Modify
    public $fieldListInsertUniquekey = array(
                'drugstore_CD',
                'unique_key',
                'entry_date',
            );
    // #118 End Luvina Modify
    // #107 Start Luvina Fix Bug 356
    public $validate = array(
            'med_inst_code' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい医療機関番号を入力してください。'
                ),
                'CheckLength' => array(
                    'rule' => array('between', 10, 10),
                    'message' => '医療機関番号の値が10 桁であること。'
                ),
                'checkNumberHalfSize' => array(
                    'rule' => array('checkNumberHalfSize', 'med_inst_code'),
                    'message' => '医療機関番号が半角数値であること。'
                ),
                'unique' => array(
                    'rule'    => array('checkUnique', array('med_inst_code', 'kubun')),
                    'message' => 'DBの医療機関番号と重複しています。'
                )
            ),
            'update_med_inst_code' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい医療機関番号を入力してください。'
                ),
                'CheckLength' => array(
                    'rule' => array('between', 10, 10),
                    'message' => '医療機関番号の値が10 桁であること。'
                ),
                'checkNumberHalfSize' => array(
                    'rule' => array('checkNumberHalfSize', 'update_med_inst_code'),
                    'message' => '医療機関番号が半角数値であること。'
                )
            ),
            'com_CD' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい法人CDを入力してください。'
                ),
                'CheckLength' => array(
                    'rule' => array('between', 0, 16),
                    'message' => '法人CDは16文字以内で入力してください。'
                ),
            ),
            'com_contact_CD' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい法人窓口CDを入力してください。'
                ),
                'CheckLength' => array(
                    'rule' => array('between', 0, 16),
                    'message' => '法人窓口CDは16文字以内で入力してください。'
                ),
            ),
            'chain_drugstore_CD' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい薬局チェーンCDを入力してください。'
                ),
                'CheckLength' => array(
                    'rule' => array('between', 0, 16),
                    'message' => '薬局チェーンCDは16文字以内で入力してください。'
                ),
            ),
            'drugstore_CD' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい薬局CDを入力してください。'
                ),
                'unique' => array(
                    'rule'    => 'isUnique',
                    'message' => 'DBの薬局CDと重複しています。'
                ),
                'CheckLength' => array(
                    'rule' => array('between', 0, 16),
                    'message' => '薬局CDは16文字以内で入力してください。'
                ),
            ),
            'update_drugstore_CD' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい薬局CDを入力してください。'
                ),
                'CheckLength' => array(
                    'rule' => array('between', 0, 16),
                    'message' => '薬局CDは16文字以内で入力してください。'
                ),
            ),
            'drugstore_name_yomi' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい薬局名よみを入力してください。'
                ),
                'CheckLength' => array(
                    'rule' => array('between', 0, 128),
                    'message' => '薬局名よみは128文字以内で入力してください。'
                ),
            ),
            'title' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい見出しを入力してください。'
                ),
                'CheckLength' => array(
                    'rule' => array('between', 0, 128),
                    'message' => '見出し は128文字以内で入力してください。'
                ),
            ),
            'pic_name' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい担当者名前を入力してください。'
                ),
                'CheckLength' => array(
                    'rule' => array('between', 0, 64),
                    'message' => '担当者名前は64文字以内で入力してください。'
                ),
            ),
            'imple_area' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい実施地域を入力してください。'
                ),
                'CheckLength' => array(
                    'rule' => array('between', 0, 256),
                    'message' => '実施地域 は256文字以内で入力してください。'
                ),
            ),
            'visit_avail_date' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい訪問可能日時を入力してください。'
                ),
                'CheckLength' => array(
                    'rule' => array('between', 0, 256),
                    'message' => '訪問可能日時は256文字以内で入力してください。'
                ),
            ),
            'visit_guidance' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい訪問指導の実施を入力してください。'
                )
            ),
            'pal_care' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい緩和ケアの対応を入力してください。'
                )
            ),
            'discharge_conf_join' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい退院カンファレンスの参加を入力してください。'
                )
            ),
            '24_hour_aday' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい24時間体制を入力してください。'
                )
            ),
            'visit_pharmacist_num' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい訪問実施薬剤師人数を入力してください。'
                ),
                'checkNumberHalfSize' => array(
                    'rule' => array('checkNumberHalfSize', 'visit_pharmacist_num'),
                    'message' => '訪問実施薬剤師人数が半角数値であること。'
                )
            ),
            'skill_1_pple_num' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい資格1人数を入力してください。'
                ),
                'checkNumberHalfSize' => array(
                    'rule' => array('checkNumberHalfSize', 'skill_1_pple_num'),
                    'message' => '資格1人数が半角数値であること。'
                )
            ),
            'skill_1' => array(
                'checkValue' => array(
                    'rule' => 'numeric',
                    'message' => '資格1が数値であること。'
                )
            ),
            'skill_2_pple_num' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい資格2人数を入力してください。'
                ),
                'checkNumberHalfSize' => array(
                    'rule' => array('checkNumberHalfSize', 'skill_2_pple_num'),
                    'message' => '資格2人数が半角数値であること。'
                )
            ),
            'skill_2' => array(
                'checkValue' => array(
                    'rule' => 'numeric',
                    'message' => '資格2が数値であること。'
                )
            ),
            'skill_3_pple_num' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい資格3人数を入力してください。'
                ),
                'checkNumberHalfSize' => array(
                    'rule' => array('checkNumberHalfSize', 'skill_3_pple_num'),
                    'message' => '資格3人数が半角数値であること。'
                )
            ),
            'skill_3' => array(
                'checkValue' => array(
                    'rule' => 'numeric',
                    'message' => '資格3が数値であること。'
                )
            ),
            'business_hour' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい営業時間を入力してください。'
                ),
                'CheckLength' => array(
                    'rule' => array('between', 0, 64),
                    'message' => '営業時間 は64文字以内で入力してください。'
                ),
            ),
            'med_product' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい医療・衛生材料を入力してください。'
                )
            ),
            'drugstore_name' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい薬局名（施設名）を入力してください。'
                ),
                'CheckLength' => array(
                    'rule' => array('between', 0, 128),
                    'message' => '薬局名（施設名）は128文字以内で入力してください。'
                ),
            ),
            'adjustment_addictive_drug' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい麻薬の保管（麻薬調整可）を入力してください。'
                ),
                'CheckValue' => array(
                    'rule' => array('boolean'),
                    'message' => '麻薬の保管（麻薬調整可）は０か１を入力してください。'
                )
            ),
            'dealing_drug_addiction' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい麻薬の保管（麻薬対応）を入力してください。'
                ),
                'CheckValue' => array(
                    'rule' => array('boolean'),
                    'message' => '麻薬の保管（麻薬対応）は０か１を入力してください。'
                )
            ),
            'no_addictive_drug' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい麻薬の保管（麻薬無し）を入力してください。'
                ),
                'CheckValue' => array(
                    'rule' => array('boolean'),
                    'message' => '麻薬の保管（麻薬無し）は０か１を入力してください。'
                )
            ),
            'aseptic_handling_can_handle' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい無菌調剤対応（対応可）を入力してください。'
                ),
                'CheckValue' => array(
                    'rule' => array('boolean'),
                    'message' => '無菌調剤対応（対応可）は０か１を入力してください。'
                )
            ),
            'aseptic_handling_clean_bench' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい無菌調剤対応（クリーンベンチ有り）を入力してください。'
                ),
                'CheckValue' => array(
                    'rule' => array('boolean'),
                    'message' => '無菌調剤対応（クリーンベンチ有り）は０か１を入力してください。'
                )
            ),
            'aseptic_handling_sterile_room' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい無菌調剤対応（無菌室有り）を入力してください。'
                ),
                'CheckValue' => array(
                    'rule' => array('boolean'),
                    'message' => '無菌調剤対応（無菌室有り）は０か１を入力してください。'
                )
            ),
            'aseptic_handling_safety_cabinet' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい無菌調剤対応（安全キャビネットあり）を入力してください。'
                ),
                'CheckValue' => array(
                    'rule' => array('boolean'),
                    'message' => '無菌調剤対応（安全キャビネットあり）は０か１を入力してください。'
                )
            ),
            'aseptic_handling_can_not_handle' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい無菌調剤対応（対応不可）を入力してください。'
                ),
                'CheckValue' => array(
                    'rule' => array('boolean'),
                    'message' => '無菌調剤対応（対応不可）は０か１を入力してください。'
                )
            ),
            'latitude' => array(
                'checkValue' => array(
                    'rule' => 'numeric',
                    'message' => '正しい緯度の値を入力してください。'
                )
            ),
            'longitude' => array(
                'checkValue' => array(
                    'rule' => 'numeric',
                    'message' => '正しい経度の値を入力してください。'
                )
            ),
            'scope' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい範囲を入力してください。'
                ),
                'checkValue' => array(
                    'rule' => 'numeric',
                    'message' => '範囲が数値であること。'
                )
            ),

            'insr_type' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい保険区分を入力してください。'
                ),
                'CheckLength' => array(
                    'rule' => array('between', 0, 32),
                    'message' => '保険区分は32文字以内で入力してください。'
                ),
            ),
            'maron_postcode' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい郵便番号を入力してください。'
                ),
                'CheckLength' => array(
                    'rule' => array('between', 0, 8),
                    'message' => '郵便番号は8文字以内で入力してください。'
                ),
            ),
            'drugstore_address' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい住所を入力してください。'
                ),
                'CheckLength' => array(
                    'rule' => array('between', 0, 256),
                    'message' => '住所 は256文字以内で入力してください。'
                ),
            ),
            'drugstore_tel' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しいTELを入力してください。'
                ),
                'CheckLength' => array(
                    'rule' => array('between', 0, 16),
                    'message' => 'TELは16文字以内で入力してください。'
                ),
            ),
            'drugstore_expl' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい薬局説明を入力してください。'
                ),
                'CheckLength' => array(
                    'rule' => array('between', 0, 256),
                    'message' => '薬局説明は256文字以内で入力してください。'
                ),
            ),
            // #10 bugId-216 Start Luvina Modify
            // check validate for search
            'search_med_inst_code' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい医療機関番号を入力してください。'
                ),
                'CheckLength' => array(
                    'rule' => array('between', 10, 10),
                    'message' => '医療機関番号の値が10 桁であること。'
                ),
                'checkNumberHalfSize' => array(
                    'rule' => array('checkNumberHalfSize', 'search_med_inst_code'),
                    'message' => '医療機関番号が半角数値であること。'
                )
            ),
            // #10 bugId-216 End Luvina Modify
            'search_drugstore_address' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい住所を入力してください。'
                )
            ),
            // #86 Start Luvina Modify
            'search_drugstore_lat' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい緯度を入力してください。'
                ),
                'checkValue' => array(
                    'rule' => 'numeric',
                    'message' => '正しい緯度の値を入力してください。'
                )
            ),
            'search_drugstore_lng' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい経度を入力してください。'
                ),
                'checkValue' => array(
                    'rule' => 'numeric',
                    'message' => '正しい経度の値を入力してください。'
                )
            ),
            // #86 End Luvina Modify
            'search_adjustment_addictive_drug' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい麻薬の保管（麻薬調整可）を入力してください。'
                ),
                'CheckValue' => array(
                    'rule' => array('boolean'),
                    'message' => '麻薬の保管（麻薬調整可）は０か１を入力してください。'
                )
            ),
            'search_dealing_drug_addiction' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい麻薬の保管（麻薬対応）を入力してください。'
                ),
                'CheckValue' => array(
                    'rule' => array('boolean'),
                    'message' => '麻薬の保管（麻薬対応）は０か１を入力してください。'
                )
            ),
            'search_no_addictive_drug' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい麻薬の保管（麻薬無し）を入力してください。'
                ),
                'CheckValue' => array(
                    'rule' => array('boolean'),
                    'message' => '麻薬の保管（麻薬無し）は０か１を入力してください。'
                )
            ),
            'search_aseptic_handling_can_handle' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい無菌調剤対応（対応可）を入力してください。'
                ),
                'CheckValue' => array(
                    'rule' => array('boolean'),
                    'message' => '無菌調剤対応（対応可）は０か１を入力してください。'
                )
            ),
            'search_aseptic_handling_clean_bench' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい無菌調剤対応（クリーンベンチ有り）を入力してください。'
                ),
                'CheckValue' => array(
                    'rule' => array('boolean'),
                    'message' => '無菌調剤対応（クリーンベンチ有り）は０か１を入力してください。'
                )
            ),
            'search_aseptic_handling_sterile_room' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい無菌調剤対応（無菌室有り）を入力してください。'
                ),
                'CheckValue' => array(
                    'rule' => array('boolean'),
                    'message' => '無菌調剤対応（無菌室有り）は０か１を入力してください。'
                )
            ),
            'search_aseptic_handling_safety_cabinet' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい無菌調剤対応（安全キャビネットあり）を入力してください。'
                ),
                'CheckValue' => array(
                    'rule' => array('boolean'),
                    'message' => '無菌調剤対応（安全キャビネットあり）は０か１を入力してください。'
                )
            ),
            'search_aseptic_handling_can_not_handle' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい無菌調剤対応（対応不可）を入力してください。'
                ),
                'CheckValue' => array(
                    'rule' => array('boolean'),
                    'message' => '無菌調剤対応（対応不可）は０か１を入力してください。'
                )
            ),
            'search_visit_pharmacist_num' => array(
                'checkNumberHalfSize' => array(
                    'rule' => array('checkNumberHalfSize', 'search_visit_pharmacist_num'),
                    'message' => '訪問実施薬剤師人数が半角数値であること。'
                )
            ),
            // #102 Start Luvina Modify
            'search_page' => array(
                'naturalNumber' => array(
                    'rule' => 'naturalNumber',
                    'message' => 'ページ番号が正の整数であること。'
                )
            ),

            // table t_drugstore_adddetail
             'pref_CD' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい都道府県コードを入力してください。'
                ),
                'checkValue' => array(
                    'rule' => '/^(([1-9]{1})|([1-3]{1})([0-9]{1})|4([0-7]{1}))$/',
                    'message' => '都道府県コードが1～47の範囲内であること。'
                ),
            ),
            'expiration_date' => array(
                'checkDate' => array(
                    'rule' => array('date', 'ymd'),
                    'allowEmpty' => true,
                    'message' => '正しい最新フラグ有効期限を入力してください。'
                )
            ),
             'child_spt' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい小児対応を入力してください。'
                ),
                'checkValue' => array(
                    'rule' => '/^[0|1|2]{0,1}$/i',
                    'message' => '小児対応の値が0か1か2であること。'
                ),
            ),
            'dementia_spt' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい認知症対応を入力してください。'
                ),
                'checkValue' => array(
                    'rule' => '/^[0|1|2]{0,1}$/i',
                    'message' => '認知症対応の参加の値が0か1か2であること。'
                ),
            ),
            'non_dispensing1' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい調剤以外の取扱（医療材料）を入力してください。'
                ),
                'checkValue' => array(
                    'rule' => '/^[0|1]{0,1}$/i',
                    'message' => '調剤以外の取扱（医療材料）の値が0か1であること。'
                ),
            ),
            'non_dispensing2' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい調剤以外の取扱（衛生材料）を入力してください。'
                ),
                'checkValue' => array(
                    'rule' => '/^[0|1]{0,1}$/i',
                    'message' => '調剤以外の取扱（衛生材料）の取扱（医療材料）の値が0か1であること。'
                ),
            ),
            'non_dispensing3' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい調剤以外の取扱（OTC）を入力してください。'
                ),
                'checkValue' => array(
                    'rule' => '/^[0|1]{0,1}$/i',
                    'message' => '調剤以外の取扱（OTC）の値が0か1であること。'
                ),
            ),
            'non_dispensing4' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい調剤以外の取扱（食料品）を入力してください。'
                ),
                'checkValue' => array(
                    'rule' => '/^[0|1]{0,1}$/i',
                    'message' => '調剤以外の取扱（食料品）の値が0か1であること。'
                ),
            ),
            'non_dispensing5' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい調剤以外の取扱（飲料品）を入力してください。'
                ),
                'checkValue' => array(
                    'rule' => '/^[0|1]{0,1}$/i',
                    'message' => '調剤以外の取扱（飲料品）の値が0か1であること。'
                ),
            ),
            'non_dispensing6' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい調剤以外の取扱（日用品・その他）を入力してください。'
                ),
                'checkValue' => array(
                    'rule' => '/^[0|1]{0,1}$/i',
                    'message' => '調剤以外の取扱（日用品・その他）の値が0か1であること。'
                ),
            ),
            'registration1' => array(
                'checkValue' => array(
                    'rule' => '/^[0|1]{0,1}$/i',
                    'allowEmpty' => true,
                    'message' => '届出情報１（在宅届出）の値が0か1であること。'
                )
            ),
            'registration2' => array(
                'checkValue' => array(
                    'rule' => '/^[0|1]{0,1}$/i',
                    'allowEmpty' => true,
                    'message' => '届出情報２（麻薬免許）の値が0か1であること。'
                )
            ),
            'registration3' => array(
                'checkValue' => array(
                    'rule' => '/^[0|1]{0,1}$/i',
                    'allowEmpty' => true,
                    'message' => '届出情報３（生活保護法指定機関）の値が0か1であること。'
                )
            ),

            // #102 End Luvina Modify
            // #107 Start Luvina Modify
            'drug_storage' => array(
                'checkValueDrugStorage' => array(
                    'rule' => array('checkValueDrugStorage', 'drug_storage'),
                    'message' => '正しい麻薬の保管を入力してください。'
                )
            ),
            'aseptic_handle' => array(
                'checkValueAsepticHandle' => array(
                    'rule' => array('checkValueAsepticHandle', 'aseptic_handle'),
                    'message' => '正しい無菌調剤対応を入力してください。'
                )
            ),
            'expiration_date_edit' => array(
                'NotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '正しい最新フラグ有効期限を入力してください。'
                ),
            ),
            // #107 End Luvina Modify
            'drugstore_mng_CD' => array(
                'CheckLength' => array(
                    'rule' => array('between', 0, 16),
                    'message' => '薬局管理CDは16文字以内で入力してください。'
                ),
            ),
            'pic_position' => array(
                'CheckLength' => array(
                    'rule' => array('between', 0, 128),
                    'message' => '担当者肩書きは128文字以内で入力してください。'
                ),
            ),
            'pic_name_yomi' => array(
                'CheckLength' => array(
                    'rule' => array('between', 0, 64),
                    'message' => ' 担当者名前よみ は64文字以内で入力してください。'
                ),
            ),
            'drugstore_fax' => array(
                'CheckLength' => array(
                    'rule' => array('between', 0, 16),
                    'message' => 'FAXは16文字以内で入力してください。'
                ),
            ),
            'other_info' => array(
                'CheckLength' => array(
                    'rule' => array('between', 0, 256),
                    'message' => '他は256文字以内で入力してください。'
                ),
            ),
            'shipping_sys' => array(
                'CheckLength' => array(
                    'rule' => array('between', 0, 128),
                    'message' => '移動方法は128文字以内で入力してください。'
                ),
            ),
            'visit_real_record' => array(
                'CheckLength' => array(
                    'rule' => array('between', 0, 256),
                    'message' => '訪問指導実績は256文字以内で入力してください。'
                ),
            ),
            'staff_cm' => array(
                'CheckLength' => array(
                    'rule' => array('between', 0, 256),
                    'message' => ' スタッフコメントは256文字以内で入力してください。'
                ),
            ),
            'pal_care_real_record' => array(
                'CheckLength' => array(
                    'rule' => array('between', 0, 256),
                    'message' => '緩和ケア実績 は256文字以内で入力してください。'
                ),
            ),
            'drug_add' => array(
                'CheckLength' => array(
                    'rule' => array('between', 0, 256),
                    'message' => ' 麻薬補足 は256文字以内で入力してください。'
                ),
            ),
            'aseptic_add' => array(
                'CheckLength' => array(
                    'rule' => array('between', 0, 256),
                    'message' => '無菌調剤補足は256文字以内で入力してください。'
                ),
            ),
            '24_hour_aday_add' => array(
                'CheckLength' => array(
                    'rule' => array('between', 0, 256),
                    'message' => '24時間体制補足は256文字以内で入力してください。'
                ),
            ),
            'visit_day_add' => array(
                'CheckLength' => array(
                    'rule' => array('between', 0, 256),
                    'message' => '訪問可能日時補足 は256文字以内で入力してください。'
                ),
            ),
            'maron_pref_code' => array(
                'CheckLength' => array(
                    'rule' => array('between', 0, 2),
                    'message' => '都道府県コードは2文字以内で入力してください。'
                ),
            ),
            'visit_subject' => array(
                'CheckLength' => array(
                    'rule' => array('between', 0, 16),
                    'message' => '訪問対象は16文字以内で入力してください。'
                ),
            ),
            'visit_subject_add' => array(
                'CheckLength' => array(
                    'rule' => array('between', 0, 256),
                    'message' => '訪問の実績、補足は256文字以内で入力してください。'
                ),
            ),
            'dementia_spt_add' => array(
                'CheckLength' => array(
                    'rule' => array('between', 0, 256),
                    'message' => '認知症対応補足は256文字以内で入力してください。'
                ),
            ),
            'child_spt_add' => array(
                'CheckLength' => array(
                    'rule' => array('between', 0, 256),
                    'message' => '小児対応補足は256文字以内で入力してください。'
                ),
            ),
            'watch_team' => array(
                'CheckLength' => array(
                    'rule' => array('between', 0, 16),
                    'message' => '見守りチームは16文字以内で入力してください。'
                ),
            ),
            // #107 End Luvina Fix Bug 356
        );

    /**
     * validate Data file CSV
     * @author luvina
     * @access public
     * @param array $aryData
     * @param boolean $isHyn : choose tyle validate : hyn or Maron
     * @param boolean $checkUnique
     * @return boolean
     */
    public function validateDataCsv($aryData, $isHyn = true, $checkUnique = true){
        if ($isHyn) {
            $this->validate['visit_guidance'][] = $this->aryValidateConfig['check_value_visit_guidance'];
            $this->validate['pal_care'][] = $this->aryValidateConfig['check_value_pal_care'];
            $this->validate['discharge_conf_join'][] = $this->aryValidateConfig['check_value_discharge_conf_join'];
            $this->validate['24_hour_aday'][] = $this->aryValidateConfig['check_value_24_hour_aday'];
            $this->validate['med_product'][] = $this->aryValidateConfig['check_value_med_product'];
            $aryFieldValidateSkill = array();
            // #107 Start Luvina Fix Bug 356
            if(is_array($aryData) && (count($aryData) > 0)) {
                if ($checkUnique) {
                    $this->set('kubun', KUBUN_HYN_VALUE);
                    $this->set('med_inst_code', trim($aryData[MED_INST_CODE]));
                    $this->set('drugstore_CD', trim($aryData[DRUGSTORE_CD]));
                } else {
                    $this->set('update_med_inst_code', trim($aryData[MED_INST_CODE]));
                    $this->set('update_drugstore_CD', trim($aryData[DRUGSTORE_CD]));
                }
                $this->set('drugstore_name', trim($aryData[DRUGSTORE_NAME]));
                $this->set('adjustment_addictive_drug', $aryData[DRUG_STORAGE1]);
                $this->set('dealing_drug_addiction', $aryData[DRUG_STORAGE2]);
                $this->set('no_addictive_drug', $aryData[DRUG_STORAGE3]);
                $this->set('aseptic_handling_can_handle', $aryData[ASEPTIC_HANDLE1]);
                $this->set('aseptic_handling_clean_bench', $aryData[ASEPTIC_HANDLE2]);
                $this->set('aseptic_handling_sterile_room', $aryData[ASEPTIC_HANDLE3]);
                $this->set('aseptic_handling_safety_cabinet', $aryData[ASEPTIC_HANDLE4]);
                $this->set('aseptic_handling_can_not_handle', $aryData[ASEPTIC_HANDLE5]);
                $this->set('scope', $aryData[SCOPE]);
                $this->set('com_CD', trim($aryData[COM_CD]));
                $this->set('com_contact_CD', trim($aryData[COM_CONTACT_CD]));
                $this->set('chain_drugstore_CD', trim($aryData[CHAIN_DRUGSTORE_CD]));
                $this->set('drugstore_name_yomi', trim($aryData[DRUGSTORE_NAME_YOMI]));
                $this->set('title', trim($aryData[TITLE]));
                $this->set('pic_name', trim($aryData[PIC_NAME]));
                $this->set('imple_area', trim($aryData[IMPLE_AREA]));
                $this->set('visit_avail_date', trim($aryData[VISIT_AVAIL_DATE]));
                $this->set('visit_guidance', $aryData[VISIT_GUIDANCE]);
                $this->set('pal_care', $aryData[PAL_CARE]);
                $this->set('discharge_conf_join', $aryData[DISCHARGE_CONF_JOIN]);
                $this->set('24_hour_aday', $aryData[_24_HOUR_ADAY]);
                $this->set('visit_pharmacist_num', $aryData[VISIT_PHARMACIST_NUM]);
                $this->set('business_hour', trim($aryData[BUSINESS_HOUR]));
                $this->set('med_product', $aryData[MED_PRODUCT]);
                $this->set('drugstore_expl', trim($aryData[DRUGSTORE_EXPL]));
                $this->set('drugstore_address', trim($aryData[DRUGSTORE_ADDRESS]));
                $this->set('drugstore_tel', trim($aryData[DRUGSTORE_TEL]));
                if($aryData[SKILL_1] != '') {
                    $this->set('skill_1_pple_num', $aryData[SKILL_1_PPLE_NUM]);
                    $this->set('skill_1', $aryData[SKILL_1]);
                    $aryFieldValidateSkill[] = 'skill_1';
                    $aryFieldValidateSkill[] = 'skill_1_pple_num';
                }

                if($aryData[SKILL_2] != '') {
                    $this->set('skill_2_pple_num', $aryData[SKILL_2_PPLE_NUM]);
                    $this->set('skill_2', $aryData[SKILL_2]);
                    $aryFieldValidateSkill[] = 'skill_2';
                    $aryFieldValidateSkill[] = 'skill_2_pple_num';
                }

                if($aryData[SKILL_3] != '') {
                    $this->set('skill_3_pple_num', $aryData[SKILL_3_PPLE_NUM]);
                    $this->set('skill_3', $aryData[SKILL_3]);
                    $aryFieldValidateSkill[] = 'skill_3';
                    $aryFieldValidateSkill[] = 'skill_3_pple_num';
                }

                if(!empty($aryData[LATITUDE])) {
                    $this->set('latitude', $aryData[LATITUDE]);
                    $aryFieldValidateSkill[] = 'latitude';
                }
                if(!empty($aryData[LONGITUDE])) {
                    $this->set('longitude', $aryData[LONGITUDE]);
                    $aryFieldValidateSkill[] = 'longitude';
                }
                // #102 Start Luvina Modify
                $this->set('drugstore_mng_CD', trim($aryData[DRUGSTORE_MNG_CD]));
                $this->set('pic_position', trim($aryData[PIC_POSITION]));
                $this->set('pic_name_yomi', trim($aryData[PIC_NAME_YOMI]));
                $this->set('drugstore_fax', trim($aryData[DRUGSTORE_FAX]));
                $this->set('other_info', trim($aryData[OTHER_INFO]));
                $this->set('shipping_sys', trim($aryData[SHIPPING_SYS]));
                $this->set('visit_real_record', trim($aryData[VISIT_REAL_RECORD]));
                $this->set('staff_cm', trim($aryData[STAFF_CM]));
                $this->set('pal_care_real_record', trim($aryData[PAL_CARE_REAL_RECORD]));
                $this->set('drug_add', trim($aryData[DRUG_ADD]));
                $this->set('aseptic_add', trim($aryData[ASEPTIC_ADD]));
                $this->set('24_hour_aday_add', trim($aryData[_24_HOUR_ADAY_ADD]));
                $this->set('visit_day_add', trim($aryData[VISIT_DAY_ADD]));
                $this->set('non_dispensing1', $aryData[NON_DISPENSING1]);
                $this->set('non_dispensing2', $aryData[NON_DISPENSING2]);
                $this->set('non_dispensing3', $aryData[NON_DISPENSING3]);
                $this->set('non_dispensing4', $aryData[NON_DISPENSING4]);
                $this->set('non_dispensing5', $aryData[NON_DISPENSING5]);
                $this->set('non_dispensing6', $aryData[NON_DISPENSING6]);
                $this->set('child_spt', $aryData[CHILD_SPT]);
                $this->set('dementia_spt', $aryData[DEMENTIA_SPT]);
                $this->set('pref_CD', $aryData[PREF_CD]);
                $this->set('expiration_date', $aryData[EXPIRATION_DATE]);
                $this->set('registration1', $aryData[REGISTRATION1]);
                $this->set('registration2', $aryData[REGISTRATION2]);
                $this->set('registration3', $aryData[REGISTRATION3]);
                $this->set('visit_subject', trim($aryData[VISIT_SUBJECT]));
                $this->set('visit_subject_add', trim($aryData[VISIT_SUBJECT_ADD]));
                $this->set('dementia_spt_add', trim($aryData[DEMENTIA_SPT_ADD]));
                $this->set('child_spt_add', trim($aryData[CHILD_SPT_ADD]));
                $this->set('watch_team', trim($aryData[WATCH_TEAM]));
            }
            $aryfield = array(
                'med_inst_code',
                'update_med_inst_code',
                'drugstore_name',
                'adjustment_addictive_drug',
                'dealing_drug_addiction',
                'no_addictive_drug',
                'aseptic_handling_can_handle',
                'aseptic_handling_clean_bench',
                'aseptic_handling_sterile_room',
                'aseptic_handling_safety_cabinet',
                'aseptic_handling_can_not_handle',
                'scope',
                'com_CD',
                'com_contact_CD',
                'chain_drugstore_CD',
                'drugstore_CD',
                'update_drugstore_CD',
                'drugstore_name_yomi',
                'title',
                'pic_name',
                'imple_area',
                'visit_avail_date',
                'visit_guidance',
                'pal_care',
                'discharge_conf_join',
                '24_hour_aday',
                'visit_pharmacist_num',
                'business_hour',
                'med_product',
                'drugstore_expl',
                'drugstore_address',
                'drugstore_tel',
                'drugstore_mng_CD',
                'pic_position',
                'pic_name_yomi',
                'drugstore_fax',
                'other_info',
                'shipping_sys',
                'visit_real_record',
                'staff_cm',
                'pal_care_real_record',
                'drug_add',
                'aseptic_add',
                '24_hour_aday_add',
                'visit_day_add',
                'non_dispensing1',
                'non_dispensing2',
                'non_dispensing3',
                'non_dispensing4',
                'non_dispensing5',
                'non_dispensing6',
                'child_spt',
                'dementia_spt',
                'pref_CD',
                'expiration_date',
                'registration1',
                'registration2',
                'registration3',
                'visit_subject',
                'visit_subject_add',
                'dementia_spt_add',
                'child_spt_add',
                'watch_team',
            );
            // #102 End Luvina Modify
            $fieldList['fieldList'] = array_merge($aryfield, $aryFieldValidateSkill);
        } else {
            if(is_array($aryData) && !empty($aryData)) {
                $this->set('insr_type', trim($aryData[MARON_INSR_TYPE]));
                $this->set('update_med_inst_code', trim($aryData[MARON_MED_INST_CODE]));
                $this->set('drugstore_name', trim($aryData[MARON_DRUGSTORE_NAME]));
                $this->set('maron_postcode', trim($aryData[MARON_POSTCODE]));
                $this->set('drugstore_address', trim($aryData[MARON_DRUGSTORE_ADDRESS]));
                $this->set('drugstore_tel', trim($aryData[MARON_DRUGSTORE_TEL]));
                $this->set('maron_pref_code', trim($aryData[MARON_PREF_CODE]));
            }

            $fieldList = array(
                'fieldList' => array(
                    'insr_type',
                    'update_med_inst_code',
                    'drugstore_name',
                    'maron_postcode',
                    'drugstore_address',
                    'drugstore_tel',
                    'maron_pref_code',
                )
            );
        }
        // #107 End Luvina Fix Bug 356

        $isValid = $this->validates($fieldList);
        if (!$isValid) {
            foreach ($this->validationErrors as $value) {
                foreach ($value as $itemValue) {
                    $this->errors[] = $this->index . '行目 ' . $itemValue;
                }

            }
            $this->validationErrors = null;
            return true;
        } else {
            return false;
        }

    }

    /**
     * validateForApiSearch
     * @author luvina
     * @access public
     * @param array $aryData
     * @return boolean
     */
    public function validateForApiSearch($aryData){
        $fieldList = array();
        $this->validate['search_visit_guidance'] = $this->aryValidateConfig['check_value_visit_guidance'];
        $this->validate['search_pal_care'] = $this->aryValidateConfig['check_value_pal_care'];
        $this->validate['search_discharge_conf_join'] = $this->aryValidateConfig['check_value_discharge_conf_join'];
        $this->validate['search_24_hour_aday'] = $this->aryValidateConfig['check_value_24_hour_aday'];
        $this->validate['search_med_product'] = $this->aryValidateConfig['check_value_med_product'];
        // #10 bugId-216 Start Luvina Modify
        $numMedInstCode = 0;
        if (isset($aryData[FIELD_MED_INST_CODE])) {
            foreach ($aryData[FIELD_MED_INST_CODE] as $value) {
                $numMedInstCode++;
                $aryRuleValidate = $this->validate['search_med_inst_code'];
                $aryRuleValidate['checkNumberHalfSize']['rule'] = array('checkNumberHalfSize', 'search_med_inst_code' . $numMedInstCode);
                $this->validate['search_med_inst_code' . $numMedInstCode] = $aryRuleValidate;
                $this->set('search_med_inst_code' . $numMedInstCode, $value);
                $fieldList[] = 'search_med_inst_code' . $numMedInstCode;
            }
            if (isset($aryData[FIELD_DRUGSTORE_ADDRESS])) {
                $this->set('search_drugstore_address', $aryData[FIELD_DRUGSTORE_ADDRESS]);
                $fieldList[] = 'search_drugstore_address';
            }
            //#86 Start Luvina Modify
            if (isset($aryData[FIELD_DRUGSTORE_LATITUDE])) {
                $this->set('search_drugstore_lat', $aryData[FIELD_DRUGSTORE_LATITUDE]);
                $fieldList[] = 'search_drugstore_lat';
            }
            if (isset($aryData[FIELD_DRUGSTORE_LONGITUDE])) {
                $this->set('search_drugstore_lng', $aryData[FIELD_DRUGSTORE_LONGITUDE]);
                $fieldList[] = 'search_drugstore_lng';
            }
        } else {
            $this->set('search_drugstore_address', isset($aryData[FIELD_DRUGSTORE_ADDRESS]) ? $aryData[FIELD_DRUGSTORE_ADDRESS] : '');
            $fieldList[] = 'search_drugstore_address';
            $this->set('search_drugstore_lat', isset($aryData[FIELD_DRUGSTORE_LATITUDE]) ? $aryData[FIELD_DRUGSTORE_LATITUDE] : '');
            $fieldList[] = 'search_drugstore_lat';
            $this->set('search_drugstore_lng', isset($aryData[FIELD_DRUGSTORE_LONGITUDE]) ? $aryData[FIELD_DRUGSTORE_LONGITUDE] : '');
            $fieldList[] = 'search_drugstore_lng';
        }
        //#86 End Luvina Modify
        // #10 bugId-216 End Luvina Modify

        if (isset($aryData[FIELD_DRUG_STORAGE])) {
            $this->set('search_adjustment_addictive_drug', isset($aryData[FIELD_DRUG_STORAGE][0]) ? $aryData[FIELD_DRUG_STORAGE][0] : '');
            $this->set('search_dealing_drug_addiction',    isset($aryData[FIELD_DRUG_STORAGE][1]) ? $aryData[FIELD_DRUG_STORAGE][1] : '');
            $this->set('search_no_addictive_drug',         isset($aryData[FIELD_DRUG_STORAGE][2]) ? $aryData[FIELD_DRUG_STORAGE][2] : '');
            $fieldList[] = 'search_adjustment_addictive_drug';
            $fieldList[] = 'search_dealing_drug_addiction';
            $fieldList[] = 'search_no_addictive_drug';
        }

        if (isset($aryData[FIELD_ASEPTIC_HANDLE])) {
            $this->set('search_aseptic_handling_can_handle',     isset($aryData[FIELD_ASEPTIC_HANDLE][0]) ? $aryData[FIELD_ASEPTIC_HANDLE][0] : '');
            $this->set('search_aseptic_handling_clean_bench',    isset($aryData[FIELD_ASEPTIC_HANDLE][1]) ? $aryData[FIELD_ASEPTIC_HANDLE][1] : '');
            $this->set('search_aseptic_handling_sterile_room',   isset($aryData[FIELD_ASEPTIC_HANDLE][2]) ? $aryData[FIELD_ASEPTIC_HANDLE][2] : '');
            $this->set('search_aseptic_handling_safety_cabinet', isset($aryData[FIELD_ASEPTIC_HANDLE][3]) ? $aryData[FIELD_ASEPTIC_HANDLE][3] : '');
            $this->set('search_aseptic_handling_can_not_handle', isset($aryData[FIELD_ASEPTIC_HANDLE][4]) ? $aryData[FIELD_ASEPTIC_HANDLE][4] : '');
            $fieldList[] = 'search_aseptic_handling_can_handle';
            $fieldList[] = 'search_aseptic_handling_clean_bench';
            $fieldList[] = 'search_aseptic_handling_sterile_room';
            $fieldList[] = 'search_aseptic_handling_safety_cabinet';
            $fieldList[] = 'search_aseptic_handling_can_not_handle';
        }

        if (isset($aryData[FIELD_24_HOUR_ADAY])) {
            $this->set('search_24_hour_aday', $aryData[FIELD_24_HOUR_ADAY]);
            $fieldList[] = 'search_24_hour_aday';
        }

        if (isset($aryData[FIELD_VISIT_GUIDANCE])) {
            $this->set('search_visit_guidance', $aryData[FIELD_VISIT_GUIDANCE]);
            $fieldList[] = 'search_visit_guidance';
        }

        if (isset($aryData[FIELD_PAL_CARE])) {
            $this->set('search_pal_care', $aryData[FIELD_PAL_CARE]);
            $fieldList[] = 'search_pal_care';
        }

        if (isset($aryData[FIELD_DISCHARGE_CONF_JOIN])) {
            $this->set('search_discharge_conf_join', $aryData[FIELD_DISCHARGE_CONF_JOIN]);
            $fieldList[] = 'search_discharge_conf_join';
        }

        if (isset($aryData[FIELD_MED_PRODUCT])) {
            $this->set('search_med_product', $aryData[FIELD_MED_PRODUCT]);
            $fieldList[] = 'search_med_product';
        }

        if (isset($aryData[FIELD_VISIT_PHARMACIST_NUM])) {
            $this->set('search_visit_pharmacist_num', $aryData[FIELD_VISIT_PHARMACIST_NUM]);
            $fieldList[] = 'search_visit_pharmacist_num';
        }

        if (isset($aryData[FIELD_PAGE])) {
            $this->set('search_page', $aryData[FIELD_PAGE]);
            $fieldList[] = 'search_page';
        }

        $isValid = $this->validates($fieldList);
        if (!$isValid) {
            // #10 bugId-216 Start Luvina Modify
            $this->customerMedInstCodeError($numMedInstCode);
            // #10 bugId-216 End Luvina Modify
            return true;
        } else{
            return false;
        }
    }

// #10 bugId-216 Start Luvina Modify
    /**
     * customerMedInstCodeError
     * @author luvina
     * @access public
     * @return
     */
    private function customerMedInstCodeError($numMedInstCode = 1) {
        $aryMedInstCodeError = array();
        foreach ($this->validationErrors as $key => $value) {
            if (!preg_match('/^search_med_inst_code/', $key)) {
                foreach ($value as $itemValue) {
                    $this->errors[] = $itemValue;
                }
            } else {
                if ($numMedInstCode == 1) {
                    $this->errors[] = MSG_MED_INST_CODE_SINGEL;
                } else {
                    $aryMedInstCodeError[] = preg_replace('/^search_med_inst_code/', '', $key);
                }
            }
        }
        if(count($aryMedInstCodeError)) {
            $this->errors[] = implode("番目、", $aryMedInstCodeError) . MSG_MED_INST_CODE_MULTY;
        }
    }
// #10 bugId-216 End Luvina Modify

    /**
     * isUniqueInCsv : check unique list import
     * @author luvina
     * @access public
     * @param array $aryUniqueValue
     * @param array $aryUniqueFields
     * @return boolean
     */
    public function isUniqueInCsv($aryUniqueValue, $aryUniqueFields) {
        $isUnique = true;

        foreach ($aryUniqueFields as $key => $value) {
            if (isset($aryUniqueValue[$key][$value[KEY_VALUE]])) {
                $this->errors[] = $this->index . '行目 ' . $aryUniqueValue[$key][$value[KEY_VALUE]] . '行目の' . $value[KEY_NAME] . 'と重複しています。';
                $isUnique = false;
            }
        }

        return $isUnique;
    }
    /**
     * insert drugstore
     * @author luvina
     * @access public
     * @param array $listDrugStore
     * @return
     */
    public function insertToDrugStore($listDrugStore) {
        $aryParamValue = array();
        $valueSqlList = array();
        $aryParam = array_fill(0, count($this->fieldListInsertUpdate), '?');
        $sql = 'INSERT INTO t_drugstore(';
        $sql .= implode(', ', $this->fieldListInsertUpdate);
        $sql .= ') VALUES';

        // #102 Start Luvina Modify
        $aryParamAddDetail = array_fill(0, count($this->fieldListInsertAddDetail), '?');
        $sqlAddDetail = 'INSERT INTO t_drugstore_adddetail(';
        $sqlAddDetail .= implode(', ', $this->fieldListInsertAddDetail);
        $sqlAddDetail .= ') VALUES';
        // #102 End Luvina Modify

        // #118 Start Luvina Modify
        $aryParamUniquekey = array_fill(0, count($this->fieldListInsertUniquekey), '?');
        $sqlUniquekey = 'INSERT INTO t_drugstore_uniquekey(';
        $sqlUniquekey .= implode(', ', $this->fieldListInsertUniquekey);
        $sqlUniquekey .= ') VALUES';
        // #118 End Luvina Modify
        foreach ($listDrugStore as $value) {
            $this->index++;
            $checkColumn = $this->checkColumnCsv($value, COLUMN_HYN);
            if(!$checkColumn) {
                $this->errors[] = $this->index . '行目 は形式が不正です。';
                continue;
            }

            $aryUniqueFields = array(
                MED_INST_CODE  => array('医療機関番号', $value[MED_INST_CODE]),
                DRUGSTORE_CD   => array('薬局CD', $value[DRUGSTORE_CD])
            );
            $isError = $this->validateDataCsv($value);
            $isUnique = $this->isUniqueInCsv($this->aryUniqueList, $aryUniqueFields);
            if(!isset($this->aryUniqueList[MED_INST_CODE][$value[MED_INST_CODE]])) {
                $this->aryUniqueList[MED_INST_CODE][$value[MED_INST_CODE]] = $this->index;
            }

            if(!isset($this->aryUniqueList[DRUGSTORE_CD][$value[DRUGSTORE_CD]])) {
                $this->aryUniqueList[DRUGSTORE_CD][$value[DRUGSTORE_CD]] = $this->index;
            }

            if(!$isError && $isUnique) {
                if(empty($this->errors)) {
                    $valueSql = '(';
                    $valueSql .= implode(', ', $aryParam);
                    $valueSql .= ')';
                    $valueSqlList[] = $valueSql;
                    // binding data
                    // #107 Start Luvina Fix Bug 356
                    $aryParamValue[] = 1;
                    $aryParamValue[] = trim($value[MED_INST_CODE]);
                    $aryParamValue[] = trim($value[COM_CD]);
                    $aryParamValue[] = trim($value[COM_CONTACT_CD]);
                    $aryParamValue[] = trim($value[CHAIN_DRUGSTORE_CD]);
                    $aryParamValue[] = trim($value[DRUGSTORE_CD]);
                    $aryParamValue[] = trim($value[DRUGSTORE_MNG_CD]);
                    $aryParamValue[] = trim($value[DRUGSTORE_NAME]);
                    $aryParamValue[] = trim($value[DRUGSTORE_NAME_YOMI]);
                    $aryParamValue[] = trim($value[TITLE]);
                    $aryParamValue[] = trim($value[DRUGSTORE_EXPL]);
                    $aryParamValue[] = trim($value[PIC_POSITION]);
                    $aryParamValue[] = trim($value[PIC_NAME]);
                    $aryParamValue[] = trim($value[PIC_NAME_YOMI]);
                    $aryParamValue[] = trim($value[DRUGSTORE_ADDRESS]);
                    $aryParamValue[] = trim($value[DRUGSTORE_FAX]);
                    $aryParamValue[] = trim($value[DRUGSTORE_TEL]);
                    $aryParamValue[] = trim($value[IMPLE_AREA]);
                    $aryParamValue[] = trim($value[VISIT_AVAIL_DATE]);
                    $aryParamValue[] = $value[VISIT_GUIDANCE];
                    $aryParamValue[] = $value[PAL_CARE];
                    $aryParamValue[] = $value[DISCHARGE_CONF_JOIN];
                    $aryParamValue[] = trim($value[OTHER_INFO]);
                    $aryParamValue[] = $value[DRUG_STORAGE1] . $value[DRUG_STORAGE2] . $value[DRUG_STORAGE3];
                    $aryParamValue[] = $value[ASEPTIC_HANDLE1] . $value[ASEPTIC_HANDLE2] . $value[ASEPTIC_HANDLE3] . $value[ASEPTIC_HANDLE4] . $value[ASEPTIC_HANDLE5];
                    $aryParamValue[] = $value[_24_HOUR_ADAY];
                    $aryParamValue[] = trim($value[SHIPPING_SYS]);
                    $aryParamValue[] = $value[VISIT_PHARMACIST_NUM];
                    $aryParamValue[] = $value[SKILL_1];
                    $aryParamValue[] = $value[SKILL_1_PPLE_NUM];
                    $aryParamValue[] = $value[SKILL_2];
                    $aryParamValue[] = $value[SKILL_2_PPLE_NUM];
                    $aryParamValue[] = $value[SKILL_3];
                    $aryParamValue[] = $value[SKILL_3_PPLE_NUM];
                    $aryParamValue[] = trim($value[VISIT_REAL_RECORD]);
                    $aryParamValue[] = trim($value[STAFF_CM]);
                    // #122 Start Luvina Modify
                    $business_hour = str_replace('\n', PHP_EOL, $value[BUSINESS_HOUR]);
                    $aryParamValue[] = trim($business_hour);
                    // #122 End Luvina Modify
                    $aryParamValue[] = $value[MED_PRODUCT];
                    $aryParamValue[] = trim($value[PAL_CARE_REAL_RECORD]);
                    $aryParamValue[] = trim($value[DRUG_ADD]);
                    $aryParamValue[] = trim($value[ASEPTIC_ADD]);
                    $aryParamValue[] = trim($value[_24_HOUR_ADAY_ADD]);
                    $aryParamValue[] = trim($value[VISIT_DAY_ADD]);
                    if(empty($value[LATITUDE]) || empty($value[LONGITUDE])) {
                        $aryParamValue[] = null;
                        $aryParamValue[] = null;
                    } else {
                        $aryParamValue[] = $value[LATITUDE];
                        $aryParamValue[] = $value[LONGITUDE];
                    }
                    $aryParamValue[] = $value[SCOPE];
                    $aryParamValue[] = date('Y-m-d H:i:s');
                    $aryParamValue[] = $this->user_id;
                    $aryParamValue[] = (empty($value[LATITUDE]) || empty($value[LONGITUDE])) ? 0 : 1;
                    // #106 Start Luvina Modify
                    $aryParamValue[] = 0;
                    // #106 End Luvina Modify

                    // #102 Start Luvina Modify
                    $valueSqlAddDetail = '(';
                    $valueSqlAddDetail .= implode(', ', $aryParamAddDetail);
                    $valueSqlAddDetail .= ')';
                    $valueSqlListAddDetail[] = $valueSqlAddDetail;

                    $aryParamAddDetailValue[] = trim($value[DRUGSTORE_CD]);
                    $aryParamAddDetailValue[] = trim($value[VISIT_SUBJECT]);
                    $aryParamAddDetailValue[] = trim($value[VISIT_SUBJECT_ADD]);
                    $aryParamAddDetailValue[] = $value[NON_DISPENSING1];
                    $aryParamAddDetailValue[] = $value[NON_DISPENSING2];
                    $aryParamAddDetailValue[] = $value[NON_DISPENSING3];
                    $aryParamAddDetailValue[] = $value[NON_DISPENSING4];
                    $aryParamAddDetailValue[] = $value[NON_DISPENSING5];
                    $aryParamAddDetailValue[] = $value[NON_DISPENSING6];
                    $aryParamAddDetailValue[] = $value[CHILD_SPT];
                    $aryParamAddDetailValue[] = trim($value[CHILD_SPT_ADD]);
                    $aryParamAddDetailValue[] = $value[DEMENTIA_SPT];
                    $aryParamAddDetailValue[] = $value[DEMENTIA_SPT_ADD];
                    $aryParamAddDetailValue[] = trim($value[PREF_CD]);
                    if (empty($value[EXPIRATION_DATE])) {
                        $aryParamAddDetailValue[] = date("Y-m-d",strtotime("+60 day"));
                    } else {
                        $aryParamAddDetailValue[] = $value[EXPIRATION_DATE];
                    }

                    $aryParamAddDetailValue[] = trim($value[WATCH_TEAM]);
                    $aryParamAddDetailValue[] = $value[REGISTRATION1];
                    $aryParamAddDetailValue[] = $value[REGISTRATION2];
                    $aryParamAddDetailValue[] = $value[REGISTRATION3];

                    $aryParamAddDetailValue[] = date('Y-m-d H:i:s');
                    $aryParamAddDetailValue[] = $this->user_id;
                    // #107 End Luvina Fix Bug 356
                    // #118 Start Luvina Modify
                    $valueSqlUniquekey = '(';
                    $valueSqlUniquekey .= implode(', ', $aryParamUniquekey);
                    $valueSqlUniquekey .= ')';
                    $valueSqlListUniquekey[] = $valueSqlUniquekey;

                    $aryParamUniquekeyValue[] = trim($value[DRUGSTORE_CD]);
                    $drugstoreUniquekey = new DrugstoreUniquekey();
                    $uniqueKey = $drugstoreUniquekey->getUniqueKey($value[DRUGSTORE_CD]);
                    $aryParamUniquekeyValue[] = $uniqueKey;
                    $aryParamUniquekeyValue[] = date('Y-m-d H:i:s');
                    // #118 End Luvina Modify
                }
            }
        }

        if(empty($this->errors) && !empty($aryParamValue)) {
            $sql .= implode(', ', $valueSqlList);
            $query = $this->query($sql, $aryParamValue);
        }
        if(empty($this->errors) && !empty($aryParamAddDetail)) {
            $sqlAddDetail .= implode(', ', $valueSqlListAddDetail);
            $query = $this->query($sqlAddDetail, $aryParamAddDetailValue);
        }
        // #102 End Luvina Modify
        // #118 Start Luvina Modify
        if(empty($this->errors) && !empty($aryParamUniquekey)) {
            $sqlUniquekey .= implode(', ', $valueSqlListUniquekey);
            $query = $this->query($sqlUniquekey, $aryParamUniquekeyValue);
        }
        // #118 End Luvina Modify
    }
    /**
     * insert drugstore Maron
     * @author luvina
     * @access public
     * @param array $listDrugStoreMaron
     * @return
     */
    public function insertToDrugStoreMaron($listDrugStoreMaron) {
        $aryParamValue = array();
        $valueSqlList = array();
        $aryParam = array_fill(0, count($this->fieldListInsertMaron), '?');
        $sql = 'INSERT INTO t_drugstore(';
        $sql .= implode(', ', $this->fieldListInsertMaron);
        $sql .= ') VALUES';
        foreach ($listDrugStoreMaron as $value) {
            $this->index++;
            $checkColumn = $this->checkColumnCsv($value, COLUMN_MARON);
            if(!$checkColumn) {
                $this->errors[] = $this->index . '行目 は形式が不正です。';
                continue;
            }

            $aryUniqueFields = array(
                    MARON_MED_INST_CODE => array('医療機関番号', $value[MARON_MED_INST_CODE])
            );
            $isError = $this->validateDataCsv($value, false);
            $isUnique = $this->isUniqueInCsv($this->aryUniqueList, $aryUniqueFields);
            if(!isset($this->aryUniqueList[MARON_MED_INST_CODE][$value[MARON_MED_INST_CODE]])) {
                $this->aryUniqueList[MARON_MED_INST_CODE][$value[MARON_MED_INST_CODE]] = $this->index;
            }
            if(!$isError && $isUnique) {
                if(empty($this->errors)) {
                    $valueSql = '(';
                    $valueSql .= implode(', ', $aryParam);
                    $valueSql .= ')';
                    $valueSqlList[] = $valueSql;
                    // binding data
                    // #107 Start Luvina Fix Bug 356
                    $aryParamValue[] = 2;
                    $aryParamValue[] = trim($value[MARON_INSR_TYPE]);
                    $aryParamValue[] = trim($value[MARON_PREF_CODE]);
                    $aryParamValue[] = trim($value[MARON_MED_INST_CODE]);
                    $aryParamValue[] = trim($value[MARON_DRUGSTORE_NAME]);
                    $aryParamValue[] = trim($value[MARON_POSTCODE]);
                    $value[MARON_PREF_CODE] = intval($value[MARON_PREF_CODE]);
                    if(isset($this->prefectureList[$value[MARON_PREF_CODE]])) {
                        $aryParamValue[] = $this->prefectureList[$value[MARON_PREF_CODE]] . $value[MARON_DRUGSTORE_ADDRESS];
                    } else {
                        $aryParamValue[] = trim($value[MARON_DRUGSTORE_ADDRESS]);
                    }

                    $aryParamValue[] = trim($value[MARON_DRUGSTORE_TEL]);
                    // #107 End Luvina Fix Bug 356
                    $aryParamValue[] = date('Y-m-d H:i:s');
                    $aryParamValue[] = $this->user_id;
                    // #106 Start Luvina Modify
                    $aryParamValue[] = 1;
                    // #106 End Luvina Modify
                }
            }
        }
        if(empty($this->errors) && !empty($aryParamValue)) {
            $sql .= implode(', ', $valueSqlList);
            $query = $this->query($sql, $aryParamValue);
        }
    }
    /**
     * update drugstore
     * @author luvina
     * @access public
     * @param array $listDrugStore
     * @return
     */
    public function updateDrugStore($listDrugStore) {
        $fieldListUpdate = $this->fieldListInsertUpdate;
        // #107 Start Luvina Fix Bug 356
        $db = $this->getDataSource();
        // #107 End Luvina Fix Bug 356
        $aryCondition['kubun'] = 1;
        foreach ($listDrugStore as $value) {
            $this->index++;
            $checkColumn = $this->checkColumnCsv($value, COLUMN_HYN);
            if(!$checkColumn) {
                $this->errors[] = $this->index . '行目 は形式が不正です。';
                continue;
            }
            $conditions = array(
                'kubun' => 1,
                'med_inst_code' => $value[MED_INST_CODE]
            );
            $count = $this->find('count', array("conditions" => $conditions));

            $aryUniqueFields = array(
                MED_INST_CODE  => array('医療機関番号', $value[MED_INST_CODE]),
                DRUGSTORE_CD   => array('薬局CD', $value[DRUGSTORE_CD])
            );
            $isError = $this->validateDataCsv($value, true, false);
            $isUnique = $this->isUniqueInCsv($this->aryUniqueList, $aryUniqueFields);
            if(!isset($this->aryUniqueList[MED_INST_CODE][$value[MED_INST_CODE]])) {
                $this->aryUniqueList[MED_INST_CODE][$value[MED_INST_CODE]] = $this->index;
            }

            if(!isset($this->aryUniqueList[DRUGSTORE_CD][$value[DRUGSTORE_CD]])) {
                $this->aryUniqueList[DRUGSTORE_CD][$value[DRUGSTORE_CD]] = $this->index;
            }

            if ($count == 0) {
                $this->errors[] = $this->index . '行目 医療機関番号がHYNデータに存在していません。';
                continue;
            } else {
                $conditions = array(
                    'kubun' => 1,
                    'med_inst_code !=' => $value[MED_INST_CODE],
                    'drugstore_CD'     => $value[DRUGSTORE_CD]
                );
                $count = $this->find('count', array("conditions" => $conditions));
                if ($count > 0) {
                    $this->errors[] = $this->index . '行目 薬局CDが既に存在しています。';
                    continue;
                }
            }
            if(!$isError && $isUnique) {
                if(empty($this->errors)) {
                    $cur = 0;
                    // #107 Start Luvina Fix Bug 356
                    $aryCondition['med_inst_code'] = $value[MED_INST_CODE];
                    $aryData['com_CD'] = $db->value(trim($value[COM_CD]));
                    $aryData['com_contact_CD'] = $db->value(trim($value[COM_CONTACT_CD]));
                    $aryData['chain_drugstore_CD'] = $db->value(trim($value[CHAIN_DRUGSTORE_CD]));
                    $aryData['drugstore_CD'] = $db->value(trim($value[DRUGSTORE_CD]));
                    $aryData['drugstore_mng_CD'] = $db->value(trim($value[DRUGSTORE_MNG_CD]));
                    $aryData['drugstore_name'] = $db->value(trim($value[DRUGSTORE_NAME]));
                    $aryData['drugstore_name_yomi'] = $db->value(trim($value[DRUGSTORE_NAME_YOMI]));
                    $aryData['title'] = $db->value(trim($value[TITLE]));
                    $aryData['drugstore_expl'] = $db->value(trim($value[DRUGSTORE_EXPL]));
                    $aryData['pic_position'] = $db->value(trim($value[PIC_POSITION]));
                    $aryData['pic_name'] = $db->value(trim($value[PIC_NAME]));
                    $aryData['pic_name_yomi'] = $db->value(trim($value[PIC_NAME_YOMI]));
                    $aryData['drugstore_address'] = $db->value(trim($value[DRUGSTORE_ADDRESS]));
                    $aryData['drugstore_fax'] = $db->value(trim($value[DRUGSTORE_FAX]));
                    $aryData['drugstore_tel'] = $db->value(trim($value[DRUGSTORE_TEL]));
                    $aryData['imple_area'] = $db->value(trim($value[IMPLE_AREA]));
                    $aryData['visit_avail_date'] = $db->value(trim($value[VISIT_AVAIL_DATE]));
                    $aryData['visit_guidance'] = $db->value($value[VISIT_GUIDANCE]);
                    $aryData['pal_care'] = $db->value($value[PAL_CARE]);
                    $aryData['discharge_conf_join'] = $db->value($value[DISCHARGE_CONF_JOIN]);
                    $aryData['other_info'] = $db->value(trim($value[OTHER_INFO]));
                    $aryData['drug_storage'] = $db->value($value[DRUG_STORAGE1] . $value[DRUG_STORAGE2] . $value[DRUG_STORAGE3]);
                    $aryData['aseptic_handle'] = $db->value($value[ASEPTIC_HANDLE1] . $value[ASEPTIC_HANDLE2] . $value[ASEPTIC_HANDLE3] . $value[ASEPTIC_HANDLE4] . $value[ASEPTIC_HANDLE5]);
                    $aryData['24_hour_aday'] = $db->value($value[_24_HOUR_ADAY]);
                    $aryData['shipping_sys'] = $db->value(trim($value[SHIPPING_SYS]));
                    $aryData['visit_pharmacist_num'] = $db->value($value[VISIT_PHARMACIST_NUM]);
                    $aryData['skill_1'] = $db->value($value[SKILL_1]);
                    $aryData['skill_1_pple_num'] = $db->value($value[SKILL_1_PPLE_NUM]);
                    $aryData['skill_2'] = $db->value($value[SKILL_2]);
                    $aryData['skill_2_pple_num'] = $db->value($value[SKILL_2_PPLE_NUM]);
                    $aryData['skill_3'] = $db->value($value[SKILL_3]);
                    $aryData['skill_3_pple_num'] = $db->value($value[SKILL_3_PPLE_NUM]);
                    $aryData['visit_real_record'] = $db->value(trim($value[VISIT_REAL_RECORD]));
                    $aryData['staff_cm'] = $db->value(trim($value[STAFF_CM]));
                    // #122 Start Luvina Modify
                    $business_hour = str_replace('\n', PHP_EOL, $value[BUSINESS_HOUR]);
                    $aryData['business_hour'] = $db->value(trim($business_hour));
                    // #122 End Luvina Modify
                    $aryData['med_product'] = $db->value($value[MED_PRODUCT]);
                    $aryData['pal_care_real_record'] = $db->value(trim($value[PAL_CARE_REAL_RECORD]));
                    $aryData['drug_add'] = $db->value(trim($value[DRUG_ADD]));
                    $aryData['aseptic_add'] = $db->value(trim($value[ASEPTIC_ADD]));
                    $aryData['24_hour_aday_add'] = $db->value(trim($value[_24_HOUR_ADAY_ADD]));
                    $aryData['visit_day_add'] = $db->value(trim($value[VISIT_DAY_ADD]));
                    if(empty($value[LATITUDE]) || empty($value[LONGITUDE])) {
                        $aryData['latitude']  = null;
                        $aryData['longitude'] = null;

                    } else {
                        $aryData['latitude'] = $value[LATITUDE];
                        $aryData['longitude'] = $value[LONGITUDE];
                    }
                    $aryData['scope'] = $db->value($value[SCOPE]);
                    $aryData['update_date'] = $db->value(date('Y-m-d H:i:s'));
                    $aryData['update_user'] = $db->value($this->user_id);
                    $aryData['flag_set'] = (empty($value[LATITUDE]) || empty($value[LONGITUDE])) ? 0 : 1;
                    $this->updateAll($aryData, $aryCondition);
                    // #102 Start Luvina Modify
                    $aryConditionAddDetail['drugstore_CD'] = $value[DRUGSTORE_CD];

                    $aryDataAddDetail['visit_subject'] = $db->value(trim($value[VISIT_SUBJECT]));
                    $aryDataAddDetail['visit_subject_add'] = $db->value(trim($value[VISIT_SUBJECT_ADD]));
                    $aryDataAddDetail['non_dispensing1'] = $db->value($value[NON_DISPENSING1]);
                    $aryDataAddDetail['non_dispensing2'] = $db->value($value[NON_DISPENSING2]);
                    $aryDataAddDetail['non_dispensing3'] = $db->value($value[NON_DISPENSING3]);
                    $aryDataAddDetail['non_dispensing4'] = $db->value($value[NON_DISPENSING4]);
                    $aryDataAddDetail['non_dispensing5'] = $db->value($value[NON_DISPENSING5]);
                    $aryDataAddDetail['non_dispensing6'] = $db->value($value[NON_DISPENSING6]);
                    $aryDataAddDetail['child_spt'] = $db->value($value[CHILD_SPT]);
                    $aryDataAddDetail['child_spt_add'] = $db->value(trim($value[CHILD_SPT_ADD]));
                    $aryDataAddDetail['dementia_spt'] = $db->value($value[DEMENTIA_SPT]);
                    $aryDataAddDetail['dementia_spt_add'] = $db->value(trim($value[DEMENTIA_SPT_ADD]));
                    if (empty($value[EXPIRATION_DATE])) {
                        $aryDataAddDetail['expiration_date'] = $db->value(date("Y-m-d",strtotime("+60 day")));
                    } else {
                        $aryDataAddDetail['expiration_date'] = $db->value($value[EXPIRATION_DATE]);
                    }
                    $aryDataAddDetail['pref_CD'] = $db->value($value[PREF_CD]);
                    $aryDataAddDetail['watch_team'] = $db->value(trim($value[WATCH_TEAM]));
                    $aryDataAddDetail['registration1'] = $db->value($value[REGISTRATION1]);
                    $aryDataAddDetail['registration2'] = $db->value($value[REGISTRATION2]);
                    $aryDataAddDetail['registration3'] = $db->value($value[REGISTRATION3]);
                    $aryDataAddDetail['update_date'] = $db->value(date('Y-m-d H:i:s'));
                    $aryDataAddDetail['update_user'] = $db->value($this->user_id );
                    // #107 End Luvina Fix Bug 356
                    $drugStoreAddDetailModel = new DrugStoreAddDetail();
                    $drugStoreAddDetailModel->updateAll($aryDataAddDetail, $aryConditionAddDetail);
                    // #102 End Luvina Modify
                }
            }
        }
    }
    /**
     * delete drugstore
     * @author luvina
     * @access public
     * @param array $listDrugStore
     * @return
     */
    public function deleteDrugStore($listDrugStore = null, $kubun = 1) {
        if($kubun == 1 && !is_null($listDrugStore)) {
            $condition = array();
            foreach($listDrugStore as $value) {
                $this->index++;
                $checkColumn = $this->checkColumnCsv($value, COLUMN_HYN);
                if(!$checkColumn) {
                    $this->errors[] = $this->index . '行目 は形式が不正です。';
                    continue;
                }

                $conditions = array(
                    'kubun' => 1,
                    'med_inst_code' => $value[MED_INST_CODE]
                );
                $count = $this->find('count', array("conditions" => $conditions));

                $aryUniqueFields = array(
                    MED_INST_CODE  => array('医療機関番号', $value[MED_INST_CODE]),
                    DRUGSTORE_CD   => array('薬局CD', $value[DRUGSTORE_CD])
                );
                $isError = $this->validateDataCsv($value, true, false);
                $isUnique = $this->isUniqueInCsv($this->aryUniqueList, $aryUniqueFields);
                if(!isset($this->aryUniqueList[MED_INST_CODE][$value[MED_INST_CODE]])) {
                    $this->aryUniqueList[MED_INST_CODE][$value[MED_INST_CODE]] = $this->index;
                }

                if(!isset($this->aryUniqueList[DRUGSTORE_CD][$value[DRUGSTORE_CD]])) {
                    $this->aryUniqueList[DRUGSTORE_CD][$value[DRUGSTORE_CD]] = $this->index;
                }

                if ($count == 0) {
                    $this->errors[] = $this->index . '行目 医療機関番号がHYNデータに存在していません。';
                    continue;
                }
                if(!$isError && $isUnique) {
                    $condition[] = $value[MED_INST_CODE];
                     // #102 Start Luvina Modify
                    $conditionAddDetail[] = $value[DRUGSTORE_CD];
                     // #102 End Luvina Modify
                }
            }

            if(count($this->errors) == 0 && !empty($condition)) {
                if(count($condition) == 1) {
                    $this->deleteAll(array('kubun' => 1, 'med_inst_code' => $condition[0]), false, false);
                } else {
                    $this->deleteAll(array('kubun' => 1, 'med_inst_code IN' => $condition), false, false);
                }
                // #102 Start Luvina Modify
                $drugStoreAddDetailModel = new DrugStoreAddDetail();
                $drugStoreAddDetailModel->deleteAll(array('drugstore_CD' => $conditionAddDetail), false, false);
                // #102 End Luvina Modify
            }
        } elseif ($kubun == 2 && is_null($listDrugStore)) {
            $this->deleteAll(array('kubun' => $kubun), false, false);
        }
    }
    /**
     * findDrugstoreListByLocation
     * @author Luvina
     * @access public
     * @param array $scopeList
     * @param float $latitude
     * @param float $longitude
     * @param array $condition
     * @param array $aryFields
     * @return array
     */
    public function findDrugstoreListByLocation($scopeList, $latitude, $longitude, $condition, $aryFields = array()) {
        $drugstoreMapList = array();
        $drugstoreIdList = array();
        foreach ($scopeList as $key => $value) {
            if ($condition['kubun']== '1') {
                $condition['scope'] = $value['Scope']['id'];
            }
            $condition['distance <= '] = $value['Scope']['distance'];
            $distance = '6371 * ACOS(COS(RADIANS('.$latitude.')) * COS(RADIANS(latitude))* COS(RADIANS(longitude) - RADIANS('.$longitude.')) + ';
            $distance .= 'SIN(RADIANS('.$latitude.'))* SIN(RADIANS(latitude)))';

            if(empty($aryFields)) {
                $aryFields = array(
                    'id',
                    'drugstore_CD',
                    'drugstore_name',
                    'latitude',
                    'longitude',
                    'distance'
                );
            }
            if (!in_array('distance', $aryFields)) {
                $aryFields[] = 'distance';
            }

            $aryOrders = array(
                'distance ASC'
            );

            // find all address in map
            $this->virtualFields['distance'] = $distance;
            // #102 Start Luvina Modify
            $aryJoins = array(
                array(
                    'table' => 't_drugstore_adddetail',
                    'alias' => 'adddetail',
                    'type' => 'LEFT',
                    'conditions' => array(
                        "DrugStore.drugstore_CD = adddetail.drugstore_CD"
                    )
                )
            );

            $list = $this->find('all', array('conditions' => $condition, 'fields' => $aryFields, 'joins' => $aryJoins, 'order' => $aryOrders));
            // #102 End Luvina Modify
            // #6 bugId-218 Start Luvina Modify
            foreach ($list as $key => $value) {
                $drugstoreMapList[] = $value;
            }
        }

        if (!empty($drugstoreMapList)) {
            $arySort = array('distance' => array(SORT_ASC));
            $drugstoreMapList = $this->sortArray($drugstoreMapList, $arySort);
            foreach ($drugstoreMapList as $key => $value) {
                $drugstoreIdList[] = $value['DrugStore']['id'];
            }
        }
        // #6 bugId-218 Start Luvina Modify
        return ($condition['kubun']== '1') ? array($drugstoreMapList, $drugstoreIdList) : $drugstoreMapList;
    }

    /**
     * findDrugstoreMaronListByLocation
     * @author Luvina
     * @access public
     * @param array $condition
     * @param array $aryFields
     * @return array
     */
    public function findDrugstoreMaronListByLocation($conditions, $aryFields, $aryOrders = array()) {
        $drugstoreList = $this->find('all', array('conditions' => $conditions, 'fields' => $aryFields, 'order' => $aryOrders));
        return $drugstoreList;
    }
// #102 Start Luvina Modify
// #106 Start Luvina Modify
    /**
     * getDrugstoreById
     * @author Luvina
     * @access public
     * @param int $drugstore_CD
     * @return array $arrayDrugstore
     */
    public function getDrugstoreById($drugstore_CD, $isPre=null, $isEdit=null) {
        $arrayDrugstore = array();
        $aryFields = array(
                'id',
                'com_CD',
                'drugstore_name',
                'drugstore_CD',
                'drugstore_name_yomi',
                'title',
                'drugstore_expl',
                'drugstore_address',
                'latitude',
                'longitude',
                'pic_position',
                'pic_name',
                'pic_name_yomi',
                'drugstore_fax',
                'business_hour',
                'drugstore_tel',
                'imple_area',
                'visit_avail_date',
                'visit_day_add',
                'visit_guidance',
                'visit_guidance as visit_guidance_show',
                'visit_real_record',
                'pal_care',
                'pal_care as pal_care_show',
                'discharge_conf_join',
                '24_hour_aday',
                '24_hour_aday as 24_hour_aday_show',
                'drug_storage',
                'drug_storage as drug_storage_show',
                'aseptic_handle',
                'aseptic_handle as aseptic_handle_show',
                'pal_care_real_record',
                'drug_add',
                'aseptic_add',
                '24_hour_aday_add',
                'med_product',
                'other_info',
                'shipping_sys',
                'visit_pharmacist_num',
                'staff_cm',
                'skill_1',
                'skill_1_pple_num',
                'skill_2',
                'skill_2_pple_num',
                'skill_3',
                'skill_3_pple_num',
                // #107 Start Luvina Modify
                'med_inst_code',
                'chain_drugstore_CD',
                'com_contact_CD',
                'drugstore_mng_CD',
                'scope',
                'kubun',
                'latitude',
                'longitude',
                'display_type',
        );

        $condition = array(
                'DrugStore.drugstore_CD' => $drugstore_CD,
                'kubun' => 1,
                'DrugStore.delete_date'  => null,
        );
        if ( $isEdit == null) {
            if ($isPre == null) {
                $condition['DrugStore.display_type'] = 1;
            } else {
                $condition['DrugStore.display_type'] = 0;
            }
        }
        // #106 End Luvina Modify
        // #107 End Luvina Modify
        $arrayDrugstore = $this->find('first', array('fields' => $aryFields, 'conditions' => $condition));
        return $arrayDrugstore;
    }
    // #102 End Luvina Modify

    /**
     * findDrugstoreForApi
     * @author Luvina
     * @access public
     * @param array $fields
     * @param array $conditions
     * @param array $orders
     * @return array
     */
    public function findDrugstoreForApi($fields, $conditions, $orders = array()) {
        $listDugStore = $this->find('all', array('fields' => $fields, 'conditions' => $conditions, 'order' => $orders));
        return $listDugStore;
    }

    /**
     * checkDrugstoreName
     * @author luvina
     * @access public
     * @param  string $drugstoreName
     * @return boolean
     */
    public function checkDrugstoreName($drugstoreName) {
        $condition = array(
            'delete_date'   => null,
            'delete_user'   => null,
            'drugstore_name =' => $drugstoreName
        );
        $aryFields = array(
            'drugstore_name'
        );
        $params = array(
            'conditions' => $condition,
            'fields'     => $aryFields
        );

        $count = $this->find('count', $params);
        return ($count > 1) ? true : false;
    }

    /**
     * sortArray
     * @author     Luvina
     * @access     private
     * @param      array $aryData
     * @param      array $aryFieldSort
     * @param      string  $model
     * @param      bool  $caseInSensitive
     * @return     array
     */
    private function sortArray($aryData, $aryFieldSort, $model = 'DrugStore', $caseInSensitive = true) {
        if( !is_array($aryData) || !is_array($aryFieldSort)) {
            return false;
        }
        $args = array();
        $i = 0;
        foreach($aryFieldSort as $fieldSort => $sortAttributes) {
            $colList = array();
            foreach ($aryData as $key => $row) {
                $row = $row['DrugStore'];
                $convertToLower = $caseInSensitive && (in_array(SORT_STRING, $sortAttributes) || in_array(SORT_REGULAR, $sortAttributes));
                $rowData = $convertToLower ? strtolower($row[$fieldSort]) : $row[$fieldSort];
                $colLists[$fieldSort][$key] = $rowData;
            }
            $args[] = &$colLists[$fieldSort];
            if(is_array($sortAttributes) && count($sortAttributes)) {
                foreach($sortAttributes as $sortAttribute) {
                    $tmp[$i] = $sortAttribute;
                    $args[] = &$tmp[$i];
                    $i++;
                }
            } else {
                $args[] = SORT_ASC;
                $i++;
            }
        }
        $args[] = &$aryData;
        call_user_func_array('array_multisort', $args);
        return end($args);
    }
    // #102 Start Luvina Modify
    /**
     * getListContentDrugStore
     * @author Luvina
     * @access public
     * @param array $condition
     * @return array
     */
    public function getListContentDrugStore($condition) {
        $drugstore = array();

        $aryFields = array(
            'id',
            'drugstore_CD',
            'adddetail.pref_CD'
        );

        $aryOrders = array(
            'drugstore_CD ASC'
        );
        $aryJoins = array(
            array(
                'table' => 't_drugstore_adddetail',
                'alias' => 'adddetail',
                'type' => 'LEFT',
                'conditions' => array(
                    "DrugStore.drugstore_CD = adddetail.drugstore_CD"
                )
            )
        );
        // list own
        if (isset($condition["OwnDrugStore.type"])) {
            //$aryFields[] = 'type';
            $aryJoins[] = array(
                                'table' => 'm_owndrugstore',
                                'alias' => 'OwnDrugStore',
                                'type' => 'INNER',
                                'conditions' => array(
                                    "DrugStore.drugstore_CD = OwnDrugStore.drugstore_CD"
                                )
                            );
        }
        $list = $this->find('all', array('conditions' => $condition, 'fields' => $aryFields, 'joins' => $aryJoins, 'order' => $aryOrders));
        $drugstore = array();
        foreach ($list as $key => $value) {
            $tmp = array();
            $tmp['id'] = $value['DrugStore']['id'];
            $tmp['drugstore_CD'] = $value['DrugStore']['drugstore_CD'];
            $tmp['pref_CD'] = $value['adddetail']['pref_CD'];
            $drugstore[] = $tmp;
        }
        return $drugstore;
    }
    // #102 End Luvina Modify
    // #107 Start Luvina Modify
    /**
     * Get cout data
     * @param array $condition
     */
    public function getCountDrugstoreIdAdmin ($condition) {
        $count = $this->find('count', array('conditions' => $condition));
        return $count;
    }

    /**
     * Get list drugsotre
     * @param unknown_type $condition
     */
    public function getListDrugstoreAdmin ($condition, $offset, $limit) {
        $aryFields = array(
            'id',
            'drugstore_CD',
            'drugstore_name'
        );

        $aryOrders = array(
            'IFNULL(DrugStore.update_date, DrugStore.entry_date) DESC',
            'DrugStore.id DESC',
        );
        $list = $this->find('all', array(
                                         'conditions' => $condition,
                                         'fields' => $aryFields,
                                         'order' => $aryOrders,
                                         'limit' => $limit,
                                         'offset' => $offset));
        $aryDrugstore = array();
        if (count($list) > 0) {
            foreach ($list as $key => $value) {
                $tmp = array();
                $tmp['id'] = $value['DrugStore']['id'];
                $tmp['drugstore_CD'] = $value['DrugStore']['drugstore_CD'];
                $tmp['drugstore_name'] = $value['DrugStore']['drugstore_name'];
                $aryDrugstore[] = $tmp;
            }
        }
        return $aryDrugstore;
    }
    // #106 Start Luvina Modify
    /**
     * Get list drugsotre
     * @param unknown_type $condition
     */
    public function getListPreDrugstoreAdmin ($condition, $offset, $limit) {
        $aryFields = array(
            'id',
            'drugstore_CD',
            'drugstore_name',
            'com_CD',
            'com_contact_CD',
        );

        $aryOrders = array(
            'IFNULL(DrugStore.update_date, DrugStore.entry_date) DESC',
            'DrugStore.id DESC',
        );
        $list = $this->find('all', array(
                                         'conditions' => $condition,
                                         'fields' => $aryFields,
                                         'order' => $aryOrders,
                                         'limit' => $limit,
                                         'offset' => $offset));
        $aryDrugstore = array();
        if (count($list) > 0) {
            foreach ($list as $key => $value) {
                $tmp = array();
                $tmp['id'] = $value['DrugStore']['id'];
                $tmp['drugstore_CD'] = $value['DrugStore']['drugstore_CD'];
                $tmp['drugstore_name'] = $value['DrugStore']['drugstore_name'];
                $tmp['com_CD'] = $value['DrugStore']['com_CD'];
                $tmp['com_contact_CD'] = $value['DrugStore']['com_contact_CD'];
                $aryDrugstore[] = $tmp;
            }
        }
        return $aryDrugstore;
    }
    // #106 End Luvina Modify
    /**
     * validate Data edit
     * @author luvina
     * @access public
     * @param array $aryData
     * @return boolean
     */
public function validateData($aryData, $exitAdddetail){
        $aryFieldValidateSkill = array();
        if(is_array($aryData) && (count($aryData) > 0)) {
            $this->set('update_med_inst_code', $aryData['med_inst_code']);
            $this->set('com_CD', $aryData['com_CD']);
            $this->set('com_contact_CD', $aryData['com_contact_CD']);
            $this->set('chain_drugstore_CD', $aryData['chain_drugstore_CD']);
            $this->set('update_drugstore_CD', $aryData['drugstore_CD']);
            $this->set('drugstore_mng_CD', $aryData['drugstore_mng_CD']);
            $this->set('drugstore_name', $aryData['drugstore_name']);
            $this->set('drugstore_name_yomi', $aryData['drugstore_name_yomi']);
            $this->set('title', $aryData['title']);
            $this->set('drugstore_expl', $aryData['drugstore_expl']);
            // #107 Start Luvina Fix Bug 356
            $this->set('pic_name', $aryData['pic_name']);
            $this->set('drugstore_address', $aryData['drugstore_address']);
            $this->set('drugstore_tel', $aryData['drugstore_tel']);
            $this->set('imple_area', $aryData['imple_area']);
            $this->set('visit_avail_date', $aryData['visit_avail_date']);
            $this->set('visit_guidance', $aryData['visit_guidance']);
            $this->set('pal_care', $aryData['pal_care']);
            $this->set('drug_storage', $aryData['drug_storage']);
            $this->set('aseptic_handle', $aryData['aseptic_handle']);
            $this->set('24_hour_aday', $aryData['24_hour_aday']);
            $this->set('business_hour', $aryData['business_hour']);
            $this->set('scope', $aryData['scope']);
            $this->set('latitude', $aryData['latitude']);
            $this->set('longitude', $aryData['longitude']);
            $this->set('kubun', $aryData['kubun']);
            $this->set('discharge_conf_join', $aryData['discharge_conf_join']);
            $this->set('med_product', $aryData['med_product']);
            $this->set('visit_pharmacist_num', $aryData['visit_pharmacist_num']);
            if($aryData['skill_1'] != '') {
                $this->set('skill_1', $aryData['skill_1']);
                $this->set('skill_1_pple_num', $aryData['skill_1_pple_num']);
                $aryFieldValidateSkill[] = 'skill_1';
                $aryFieldValidateSkill[] = 'skill_1_pple_num';
            }

            if($aryData['skill_2'] != '') {
                $this->set('skill_2', $aryData['skill_2']);
                $this->set('skill_2_pple_num', $aryData['skill_2_pple_num']);
                $aryFieldValidateSkill[] = 'skill_2';
                $aryFieldValidateSkill[] = 'skill_2_pple_num';
            }

            if($aryData['skill_3'] != '') {
                $this->set('skill_3', $aryData['skill_3']);
                $this->set('skill_3_pple_num', $aryData['skill_3_pple_num']);
                $aryFieldValidateSkill[] = 'skill_3';
                $aryFieldValidateSkill[] = 'skill_3_pple_num';
            }
            $this->set('pic_position', $aryData['pic_position']);
            $this->set('pic_name_yomi', $aryData['pic_name_yomi']);
            $this->set('drugstore_fax', $aryData['drugstore_fax']);
            $this->set('other_info', $aryData['other_info']);
            $this->set('shipping_sys', $aryData['shipping_sys']);
            $this->set('visit_real_record', $aryData['visit_real_record']);
            $this->set('staff_cm', $aryData['staff_cm']);
            $this->set('pal_care_real_record', $aryData['pal_care_real_record']);
            $this->set('drug_add', $aryData['drug_add']);
            $this->set('aseptic_add', $aryData['aseptic_add']);
            $this->set('24_hour_aday_add', $aryData['24_hour_aday_add']);
            $this->set('visit_day_add', $aryData['visit_day_add']);
            // adddetail
            if ($exitAdddetail == 1) {
                $this->set('child_spt', $aryData['child_spt']);
                $this->set('dementia_spt', $aryData['dementia_spt']);
                $this->set('pref_CD', $aryData['pref_CD']);
                $this->set('expiration_date_edit', $aryData['expiration_date']);
                $this->set('visit_subject', $aryData['visit_subject']);
                $this->set('visit_subject_add', $aryData['visit_subject_add']);
                $this->set('dementia_spt_add', $aryData['dementia_spt_add']);
                $this->set('child_spt_add', $aryData['child_spt_add']);
                $this->set('watch_team', $aryData['watch_team']);
            }
        }
        $aryfield = array(
            'update_med_inst_code',
            'com_CD',
            'com_contact_CD',
            'chain_drugstore_CD',
            'update_drugstore_CD',
            'drugstore_mng_CD',
            'drugstore_name',
            'drugstore_name_yomi',
            'title',
            'drugstore_expl',
            'pic_name',
            'drugstore_address',
            'drugstore_tel',
            'imple_area',
            'visit_avail_date',
            'visit_guidance',
            'pal_care',
            'drug_storage',
            '24_hour_aday',
            'business_hour',
            'scope',
            'latitude',
            'longitude',
            'kubun',
            'discharge_conf_join',
            'med_product',
            'pic_position',
            'pic_name_yomi',
            'drugstore_fax',
            'other_info',
            'visit_real_record',
            'staff_cm',
            'pal_care_real_record',
            'drug_add',
            'aseptic_add',
            '24_hour_aday_add',
            'visit_day_add',
            'child_spt',
            'dementia_spt',
            'pref_CD',
            'visit_subject',
            'visit_subject_add',
            'dementia_spt_add',
            'child_spt_add',
            'watch_team',
        );
        // #107 End Luvina Fix Bug 356
        $fieldList['fieldList'] = array_merge($aryfield, $aryFieldValidateSkill);
        $isValid = $this->validates($fieldList);
        if (!$isValid) {
            foreach ($this->validationErrors as $value) {
                foreach ($value as $itemValue) {
                    $this->errors[] = $itemValue;
                }
            }
            $this->validationErrors = null;
            return true;
        } else {
            return false;
        }
    }
     /**
     * checkValueDrugStorage
     * @author luvina
     * @access public
     * @param array $value
     */
    public function checkValueDrugStorage($value) {
        if ($value["drug_storage"]) {
            if ($value["drug_storage"] !== '000') {
                return true;
            } else {
                return false;
            }

        }
    }
/**
     * checkValueDrugStorage
     * @author luvina
     * @access public
     * @param array $value
     */
    public function checkValueAsepticHandle($value) {
        if ($value["aseptic_handle"]) {
            if ($value["aseptic_handle"] !== '00000') {
                return true;
            } else {
                return false;
            }
        }
    }
    /**
     * Enter description here ...
     * @param unknown_type $aryData
     */
    function editDrugstore ($aryData) {
        $db = $this->getDataSource();

        $aryCondition['drugstore_CD'] = $aryData['drugstore_CD'];
        $aryDataUpdate['com_CD'] = $db->value($aryData['com_CD']);
        $aryDataUpdate['com_contact_CD'] = $db->value($aryData['com_contact_CD']);
        $aryDataUpdate['chain_drugstore_CD'] = $db->value($aryData['chain_drugstore_CD']);
        $aryDataUpdate['drugstore_mng_CD'] = $db->value($aryData['drugstore_mng_CD']);
        $aryDataUpdate['drugstore_name'] = $db->value($aryData['drugstore_name']) ;
        $aryDataUpdate['drugstore_name_yomi'] = $db->value($aryData['drugstore_name_yomi']);
        $aryDataUpdate['title'] = $db->value($aryData['title']);
        $aryDataUpdate['drugstore_expl'] = $db->value($aryData['drugstore_expl']);
        $aryDataUpdate['drugstore_fax'] = $db->value($aryData['drugstore_fax']);
        $aryDataUpdate['discharge_conf_join'] = $db->value($aryData['discharge_conf_join']);
        $aryDataUpdate['shipping_sys'] = $db->value($aryData['shipping_sys']);
        $aryDataUpdate['drugstore_address'] = $db->value($aryData['drugstore_address']);
        $aryDataUpdate['drugstore_tel'] = $db->value($aryData['drugstore_tel']);
        $aryDataUpdate['imple_area'] = $db->value($aryData['imple_area']);
        $aryDataUpdate['visit_avail_date'] = $db->value($aryData['visit_avail_date']);
        $aryDataUpdate['visit_day_add'] = $db->value($aryData['visit_day_add']);
        $aryDataUpdate['visit_guidance'] = $db->value($aryData['visit_guidance']);
        $aryDataUpdate['pal_care'] = $db->value($aryData['pal_care']);
        $aryDataUpdate['pal_care_real_record'] = $db->value($aryData['pal_care_real_record']);
        $aryDataUpdate['drug_storage'] = $db->value($aryData['drug_storage']);
        $aryDataUpdate['drug_add'] = $db->value($aryData['drug_add']);
        $aryDataUpdate['aseptic_handle'] = $db->value($aryData['aseptic_handle']);
        $aryDataUpdate['aseptic_add'] = $db->value($aryData['aseptic_add']);
        $aryDataUpdate['24_hour_aday'] = $db->value($aryData['24_hour_aday']);
        $aryDataUpdate['24_hour_aday_add'] = $db->value($aryData['24_hour_aday_add']);
        $aryDataUpdate['visit_pharmacist_num'] = $db->value($aryData['visit_pharmacist_num']);
        $aryDataUpdate['skill_1_pple_num'] = $db->value($aryData['skill_1_pple_num']);
        $aryDataUpdate['skill_1'] = $db->value($aryData['skill_1']);
        $aryDataUpdate['skill_2_pple_num'] = $db->value($aryData['skill_2_pple_num']);
        $aryDataUpdate['skill_2'] = $db->value($aryData['skill_2']);
        $aryDataUpdate['skill_3_pple_num'] = $db->value($aryData['skill_3_pple_num']);
        $aryDataUpdate['skill_3'] = $db->value($aryData['skill_3']);
        $aryDataUpdate['business_hour'] = $db->value($aryData['business_hour']);
        $aryDataUpdate['med_product'] = $db->value($aryData['med_product']);
        $aryDataUpdate['latitude'] = $db->value($aryData['latitude']);
        $aryDataUpdate['longitude'] = $db->value($aryData['longitude']);
        $aryDataUpdate['scope'] = $db->value($aryData['scope']);
        $aryDataUpdate['visit_real_record'] = $db->value($aryData['visit_real_record']);
        $aryDataUpdate['other_info'] = $db->value($aryData['other_info']);
        $aryDataUpdate['pic_position'] = $db->value($aryData['pic_position']);
        $aryDataUpdate['pic_name'] = $db->value($aryData['pic_name']);
        $aryDataUpdate['pic_name_yomi'] = $db->value($aryData['pic_name_yomi']);
        $aryDataUpdate['staff_cm'] = $db->value($aryData['staff_cm']);
        $aryDataUpdate['update_date'] = $db->value(date('Y-m-d H:i:s'));
        // #106 Start Luvina Modify
        $aryDataUpdate['display_type'] = $db->value($aryData['display_type']);
        // #106 End Luvina Modify
        $this->updateAll($aryDataUpdate, $aryCondition);
    }
    // #107 End Luvina Modify
    // #106 Start Luvina Modify
    /**
     * updateDisplayType
     * @author Luvina
     * @access public
     * @param array $aryData
     */
    function updateDisplayType ($aryData) {
        $db = $this->getDataSource();
        $aryCondition['drugstore_CD'] = $aryData['drugstore_CD'];
        $aryDataUpdate['display_type'] = $db->value($aryData['display_type']);

        $this->updateAll($aryDataUpdate, $aryCondition);
    }
    // #106 End Luvina Modify
    // #117 Start Luvina Modify
    /**
     * getDetailLicense  m_license
     * @author Luvina
     * @access public
     * @param string $drugstoreCD
     */
    public function checkDrugsotreLicense ($skill) {
        $condition = array(
                'delete_date'  => null,
                'OR' => array('skill_1' => $skill,
                              'skill_2' => $skill,
                              'skill_3' => $skill
                            ),
        );
        $count = $this->find('count', array('conditions' => $condition));
        return $count;
    }
    // #117 End Luvina Modify
    // #130 Start Luvina Modify
    /**
     * Get all getDrugstoreCorporationNearest
     * @author Luvina
     * @access public
     * @param array param
     * @package init limit
     * Enter description here ...
     */
    public function getDrugstoreCorporationNearest ($param, $limit) {
        $condition = array(
                        'kubun'        => 1,
                        'delete_date'  => null,
                        'display_type'  => 1,
                        'com_CD' => $param['com_CD'],
                        'drugstore_CD != ' => $param['drugstore_CD'],
                       );

         $aryFields = array(
                        'drugstore_CD',
                        'drugstore_name',
                        'drugstore_address',
                        'latitude',
                        'longitude',
                       );

        $distance = '6371 * ACOS(COS(RADIANS('.$param["latitude"].')) * COS(RADIANS(latitude))* COS(RADIANS(longitude) - RADIANS('.$param["longitude"].')) + ';
        $distance .= 'SIN(RADIANS('.$param["latitude"].'))* SIN(RADIANS(latitude))) as distance';
        $aryFields[] = $distance;

        $aryOrders = array(
            'distance ASC'
        );

        $list = $this->find('all', array(
                                         'conditions' => $condition,
                                         'fields' => $aryFields,
                                         'order' => $aryOrders,
                                         'limit' => $limit,
                           ));
        $aryDrugstore = array();
        if (count($list) > 0) {
            foreach ($list as $key => $value) {
                $tmp = array();
                $tmp['drugstore_CD'] = $value['DrugStore']['drugstore_CD'];
                $tmp['drugstore_name'] = $value['DrugStore']['drugstore_name'];
                $tmp['drugstore_address'] = $value['DrugStore']['drugstore_address'];
                $aryDrugstore[] = $tmp;
            }
        }
        return $aryDrugstore;
    }
    // #130 End Luvina Modify
}