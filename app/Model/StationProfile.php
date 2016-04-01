<?php
class StationProfile extends AppModel {
    public $useTable = 't_station_profile';
    public $validate = array (
            'station_name' => array (
                    'required' => array (
                            'rule' => 'notEmpty',
                            'message' => '事業所名を入力してください。' 
                    ),
                    'max' => array (
                            'rule' => array ( 'between', 0, 128  ),
                            'message' => '事業所名は128文字以下で入力してください。' 
                    ) 
            ),
            'station_name_yomi' => array (
                    'required' => array (
                            'rule' => 'notEmpty',
                            'message' => '事業所名（カナ）を入力してください。' 
                    ),
                    'max' => array (
                            'rule' => array ( 'between', 0, 128  ),
                            'message' => '事業所名（カナ）は128文字以下で入力してください。' 
                    ),
                    'checkKana' => array(
                            'rule' => array('checkKatakana'),
                            'message' => '事業所名（カナ）にカナ以外の文字が入力されています。',
                    ),
            ),
            'trust_number' => array (
                    'required' => array (
                            'rule' => 'notEmpty',
                            'message' => '指定事業所番号を入力してください。' 
                    ),
                    'max' => array (
                            'rule' => array ( 'between', 0, 16  ),
                            'message' => '指定事業所番号は16文字以下で入力してください。' 
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
                            'message' => '都道府県市区町村～番地を入力してください。' 
                    ),
                    'max' => array (
                            'rule' => array ( 'between', 0, 128  ),
                            'message' => '都道府県市区町村～番地は32文字以下で入力してください。' 
                    ) 
            ),
            'building' => array (
                    'max' => array (
                            'rule' => array ( 'between', 0, 128  ),
                            'message' => '建物名・階数などは128文字以下で入力してください。' 
                    ) 
            ),
            'title' => array (
                    'required' => array (
                            'rule' => 'notEmpty',
                            'message' => '事業所説明見出しを入力してください。' 
                    ),
                    'max' => array (
                            'rule' => array ( 'between', 0, 50  ),
                            'message' => '事業所説明見出しは50文字以下で入力してください。' 
                    )
            ),
            'station_expl' => array (
                    'required' => array (
                            'rule' => 'notEmpty',
                            'message' => '事業所詳細説明を入力してください。' 
                    ),
                    'max' => array (
                            'rule' => array ( 'between', 0, 150  ),
                            'message' => '事業所詳細説明150文字以下で入力してください。' 
                    )
            ),
            'imple_area' => array (
                    'required' => array (
                            'rule' => 'notEmpty',
                            'message' => '訪問実施地域を入力してください。' 
                    ),
                    'max' => array (
                            'rule' => array ( 'between', 0, 150  ),
                            'message' => '店舗コードは150文字以下で入力してください。' 
                    )
            ),
            'visit_guidance' => array (
                    'required' => array (
                            'rule' => 'notEmpty',
                            'message' => '訪問実施状況を入力してください。'
                    ),
                    'regex' => array (
                            'rule'    => array('inConfig', 'visit_guidance'),
                            'message' => '訪問実施状況が0～3のいずれかであること。' 
                    ) 
            ),
            'station_function' => array (
                    'required' => array (
                            'rule' => 'notEmpty',
                            'message' => '事業所の機能を入力してください。' 
                    ),
                    'regex' => array (
                            'rule'    => array('inConfig', 'station_function'),
                            'message' => '事業所の機能の値が0～1のいずれかであること。' 
                    ) 
            ),
            'hospital_link' => array (
                    'required' => array (
                            'rule' => 'notEmpty',
                            'message' => '訪問診療算定要件を入力してください。' 
                    ),
                    'regex' => array (
                            'rule'    => array('inConfig', 'hospital_link'),
                            'message' => '訪問診療算定要件の有無の値が0～1のいずれかであること。' 
                    ) 
            ),
            '24_hour_aday' => array (
                    'required' => array (
                            'rule' => 'notEmpty',
                            'message' => '24時間体制を入力してください。' 
                    ),
                    'regex' => array (
                            'rule'    => array('inConfig', '24_hour_aday'),
                            'message' => '24時間体制の値が0～2のいずれかであること。' 
                    ) 
            ),
            'pal_care' => array (
                    'required' => array (
                            'rule' => 'notEmpty',
                            'message' => '緩和ケア対応を入力してください。' 
                    ),
                    'regex' => array (
                            'rule'    => array('inConfig', 'pal_care'),
                            'message' => '緩和ケア対応の値が0～2のいずれかであること。' 
                    ) 
            ),
            'mental_care' => array (
                    'required' => array (
                            'rule' => 'notEmpty',
                            'message' => '精神疾患対応を入力してください。' 
                    ),
                    'regex' => array (
                            'rule'    => array('inConfig', 'mental_care'),
                            'message' => '精神疾患対応の値が0～2のいずれかであること。' 
                    ) 
            ),
            'kids_care' => array (
                    'required' => array (
                            'rule' => 'notEmpty',
                            'message' => '小児対応を入力してください。' 
                    ),
                    'regex' => array (
                            'rule'    => array('inConfig', 'kids_care'),
                            'message' => '小児対応の値が0～3のいずれかであること。' 
                    ) 
            ),
            'dementia_care' => array (
                    'required' => array (
                            'rule' => 'notEmpty',
                            'message' => '認知症対応を入力してください。' 
                    ),
                    'regex' => array (
                            'rule'    => array('inConfig', 'dementia_care'),
                            'message' => '認知症対応の値が0～3のいずれかであること。' 
                    ) 
            ),
            'handicapped_care' => array (
                    'required' => array (
                            'rule' => 'notEmpty',
                            'message' => '障がい者対応を入力してください。' 
                    ),
                    'regex' => array (
                            'rule'    => array('inConfig', 'handicapped_care'),
                            'message' => '障がい者対応の値が0～3のいずれかであること。' 
                    ) 
            ),
            'nurse_fulltime' => array (
                    'required' => array (
                            'rule' => 'notEmpty',
                            'message' => '看護師常勤人数を入力してください。' 
                    ),
                    'regex' => array (
                            'rule' => array('range', -1, 51),
                            'message' => '看護師常勤人数 には数字を入力してください。' 
                    ),
            ),
            'nurse_parttime' => array (
                    'required' => array (
                            'rule' => 'notEmpty',
                            'message' => '看護師非常勤人数を入力してください。' 
                    ),
                    'regex' => array (
                            'rule' => array('range', -1, 51),
                            'message' => '看護師非常勤人数には数字を入力してください。' 
                    )
            ),
            'pt_count' => array (
                    'required' => array (
                            'rule' => 'notEmpty',
                            'message' => 'PT：理学療法士の人数を入力してください。' 
                    ),
                    'regex' => array (
                            'rule' => array('range', -1, 51),
                            'message' => 'PT：理学療法士の人数 には数字を入力してください。' 
                    ) 
            ),
            'ot_count' => array (
                    'required' => array (
                            'rule' => 'notEmpty',
                            'message' => 'OT：作業療法士の人数を入力してください。' 
                    ),
                    'regex' => array (
                            'rule' => array('range', -1, 51),
                            'message' => 'OT：作業療法士の人数 には数字を入力してください。' 
                    ) 
            ),
            'st_count' => array (
                    'required' => array (
                            'rule' => 'notEmpty',
                            'message' => 'ST：言語聴覚士の人数を入力してください。' 
                    ),
                    'regex' => array (
                            'rule' => array('range', -1, 51),
                            'message' => 'ST：言語聴覚士の人数 には数字を入力してください。' 
                    ) 
            ),
    );
    /**
     * Checks isExited
     *
     * @param $station_CD
     * @author Luvina
     * @return bool Success.
     */
     public function isExited($station_CD) {
        $isExited = $this->find('first', array('conditions' => array('station_CD' => $station_CD, 'delete_date' => null)));
        if(empty($isExited)) return false;
        return true;
    }
    /**
     * getListStation
     * @param array $listCD
     */
    public function getListStation($listCD, $conditions = array()) {
        $return = array();
        $searchCD = array();
        foreach ($listCD as $k => $v) {
            if($v['kubun'] == 1) {
                $searchCD[] = $k;
            }
        }
        if(empty($searchCD)) return $return;

        $condImportant['StationProfile.delete_date'] = null;
        $condImportant['StationProfile.station_CD'] = $searchCD;
        $conditions = array_merge($conditions, $condImportant);

        $joins = array( array(
                        'table' => 't_station_detail',
                        'alias' => 'StationDetail',
                        'type' => 'INNER',
                        'conditions' => array(
                                "StationDetail.station_CD = StationProfile.station_CD"
                        )
                 ));
        $fields = array(
            'StationProfile.*',
            'StationDetail.*',
        );
        $ret = $this->find('all', array('conditions' => $conditions, 'fields' => $fields, 'joins' => $joins));
        foreach ($ret as $k => $v) {
            $return[$v['StationProfile']['station_CD']] = $v;
        }
        return $return;
    }
    /**
     * deleteStationByComCD
     * @param String $com_CD
     */
    public function deleteStationByComCD($com_CD, $updateData) {
        $listStationCD = $this->find('all', array(
                'conditions' => array( 'com_CD' => $com_CD, 'delete_date' => null),
                'fields'     => array('id', 'station_CD')
        ));
        if(empty($listStationCD)) {
            return true;
        }
        $listCD = array();
        foreach ($listStationCD as $val) {
            $listCD[] = $val['StationProfile']['station_CD'];
        }
        $conditions['station_CD'] = $listCD;
        $conditions['delete_date'] = null;
        $ok = $this->updateAll($updateData, $conditions);
        if($ok === false) {
            return false;
        }

        App::import('model', 'StationZone');
        $stationZoneObj = new StationZone();
        $ok = $stationZoneObj->updateAll($updateData, $conditions);
        if($ok === false) {
            return false;
        }

        App::import('model', 'StationDetail');
        $stationDetailObj = new StationDetail();
        $ok = $stationDetailObj->updateAll($updateData, $conditions);
        if($ok === false) {
            return false;
        }

        App::import('model', 'StationPic');
        $stationPicObj = new StationPic();
        $ok = $stationPicObj->updateAll($updateData, $conditions);
        if($ok === false) {
            return false;
        }

        App::import('model', 'Interviewee');
        $intervieweeObj = new Interviewee();
        $listInterviewCD = $intervieweeObj->find('all', array(
                'conditions' => array( 'com_CD' => $com_CD, 'delete_date' => null),
                'fields'     => array('id', 'interviewee_id')
        ));
        if(empty($listInterviewCD)) {
            return true;
        }

        foreach ($listInterviewCD as $val) {
            $interviewIds[] = $val['Interviewee']['interviewee_id'];
        }

        $ok = $intervieweeObj->deleteInterviewByListIds($interviewIds, $updateData);

        return $ok;
    }
    /**
     * deleteStationByUserId
     * @param int $user_id
     */
    /* public function deleteStationByUserId($user_id, $updateData) {
        $listStationCD = $this->find('all', array(
                'conditions' => array( 'entry_user' => $user_id, 'delete_date' => null),
                'fields'     => array('id', 'station_CD')
        ));
        if(empty($listStationCD)) {
            return true;
        }

        $listCD = array();
        foreach ($listStationCD as $val) {
            $listCD[] = $val['StationProfile']['station_CD'];
        }
        $conditions['station_CD'] = $listCD;
        $conditions['delete_date'] = null;
        $ok = $this->updateAll($updateData, $conditions);
        if($ok === false) {
            return false;
        }

        App::import('model', 'StationZone');
        $stationZoneObj = new StationZone();
        $ok = $stationZoneObj->updateAll($updateData, $conditions);
        if($ok === false) {
            return false;
        }

        App::import('model', 'StationDetail');
        $stationDetailObj = new StationDetail();
        $ok = $stationDetailObj->updateAll($updateData, $conditions);
        if($ok === false) {
            return false;
        }

        App::import('model', 'StationPic');
        $stationPicObj = new StationPic();
        $ok = $stationPicObj->updateAll($updateData, $conditions);
        if($ok === false) {
            return false;
        }

        App::import('model', 'Interviewee');
        $intervieweeObj = new Interviewee();
        $listInterviewCD = $intervieweeObj->find('all', array(
                'conditions' => array( 'station_CD' => $listCD, 'delete_date' => null),
                'fields'     => array('id', 'interviewee_id')
        ));
        if(empty($listInterviewCD)) {
            return true;
        }

        foreach ($listInterviewCD as $val) {
            $interviewIds[] = $val['Interviewee']['interviewee_id'];
        }
        $ok = $intervieweeObj->deleteInterviewByListIds($interviewIds, $updateData);

        return $ok;
    } */
    // #147 Start Luvina Modify
    /**
     * get station name
     * @param string $sation_CD
     * @return string
     */
    public function getStationName($sation_CD) {
        $stationName = '';
        $aryStation = $this->find('first', array('conditions' => array('station_CD' => $sation_CD, 
                                                                       'delete_date' => null),
                                                 'fields' => array('station_name')));
        
        if(!empty($aryStation)) {
            $stationName = $aryStation['StationProfile']['station_name'];
        }
        return $stationName;
    }
    // #147 End Luvina Modify
}