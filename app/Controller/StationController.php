<?php
App::uses('SiteController', 'Controller/Base');
App::uses('GeocodeUtil', 'Service');
App::uses('Util', 'Service');
/**
 * HomeController
 * @author Luvina
 * @access public
 * @see index()
 */
class StationController extends SiteController {
    public $uses = array('Address', 'Geocoding', 'StationZone', 'StationProfile', 'StationPic', 'StationDetail', 'StationPublic');
    public $per_page = 10;
    /**
     * URL: /list/:address
     */
    public function search() {
        $this->set('body_id', 'list');
        $address = $this->get('address');
        $this->setApp('address', $address);
        $this->set('title_layout' , $address .'| 訪問看護ステーションナビ' );
        $this->set('description_layout' , $address .'の訪問看護ステーション' );
        $this->set('keywords_layout' ,$address . ',訪問看護ステーションナビ,ほかナビ,訪看ナビ,検索,所在地,定休日,営業日時,看護師,PT,理学療法士,OT,作業療法士,ST,言語聴覚士' );

        if(empty($address)) {
            $this->addError('error', '住所が入力されていません');
            return $this->hkRender('search', 'default');
        }

        $store_db_flg = true;
        $geocode_data = array();
        Security::setHash('md5');
        $address_md5 = Security::hash($address);
        //get google api data from cookie
        if(GeocodeUtil::isTrustCookie($address)) {
            $store_db_flg = false;
            $cookie = @json_decode($_COOKIE['geocode'], true);
            $geocode_data = $cookie;
        } else {
            $geocode_data_db = $this->Geocoding->getAdderess($address_md5);
            if(!empty($geocode_data_db)) {
                $store_db_flg = false;
                $geocode_data = unserialize($geocode_data_db['address_api']);
                $geocode_data['geometry_location_lat'] = $geocode_data_db['lat'];
                $geocode_data['geometry_location_lng'] = $geocode_data_db['lng'];
            } else {
                $latLngAPI = GeocodeUtil::getLatLngFromAddress($address);
                if(!$latLngAPI || ( is_null($latLngAPI['lat']) && is_null($latLngAPI['lng']) ) ) {
                    $this->addError('error', '場所が特定できませんでした。再検索してください。');
                    return $this->hkRender('search', 'default');
                }

                $fullAPI = GeocodeUtil::getFulladdressFromLatLng($latLngAPI['lat'], $latLngAPI['lng']);
                if(is_null($fullAPI)) {
                    $store_db_flg = false;
                }
                $geocode_data = $fullAPI;
                $geocode_data['geometry_location_lat']       = $latLngAPI['lat'];
                $geocode_data['geometry_location_lng']       = $latLngAPI['lng'];
            }
        }

        $latitude = $geocode_data['geometry_location_lat'];
        $longitude = $geocode_data['geometry_location_lng'];
        // set config for map
        $this->loadConfig('Station.ini.php');
        $map = $this->getConfigValue('map','');
        $this->configMap($latitude, $longitude, $address, $map['zoom']);

        $page = $this->page;
        $limit = $this->per_page;
        $offset = ($this->page-1)*$limit;
        list($total, $listCD, $mapList) = $this->StationZone->searchStationCD($latitude, $longitude, $limit, $offset);
        $this->setApp('page', $page);
        $this->setApp('totalPage', (int)ceil($total / $limit));
        $this->setApp('total', $total);
        $this->setApp('list', $listCD);
        $this->setApp('mapList', json_encode($mapList));
        $this->setApp('offset', $offset);

        $url_params = array(
                'controller' => 'station',
                'action' => 'search',
                'address' => $address,
        );
        Util::setPager('StationZone', $total, $url_params, $limit, 10, '');
        if($total > 0) {
            $listPic = $this->StationPic->getStationPic($listCD, false);
            $this->setApp('listPic', $listPic);
            $this->setConfigStation();
            $this->loadModel('Interviewee');
            $listInterview = $this->Interviewee->getInterviewList($listCD);
            $this->setApp('listInterview', $listInterview);
        }

        $ref = $this->get('ref');
        if($store_db_flg && $ref != 'box') {
            $this->Geocoding->addGeocoding ($address_md5, array(
                    'geometry_location_lat'       => $geocode_data['geometry_location_lat'],
                    'geometry_location_lng'       => $geocode_data['geometry_location_lng'],
                    'administrative_area_level_1' => $geocode_data['administrative_area_level_1'],
                    'sublocality_level_1'         => $geocode_data['sublocality_level_1'],
                    'locality'                    => $geocode_data['locality'],
                    'ward'                        => $geocode_data['ward'],
                    'colloquial_area'             => $geocode_data['colloquial_area'],
            ));
        }
        $this->hkRender('search', 'default');
    }
    /**
     * URL: /detail/:id
     * detail
     * @author Luvina
     * @access public
     */
    public function detail() {
        $this->set('body_id', 'detail');
        $station_CD = $this->get('id');
        $conditions['delete_date'] = null;
        $conditions['station_CD'] = $station_CD;

        $profile_flg = true;
        $profile = $this->StationProfile->find('first', array('conditions' => $conditions));
        if(empty($profile)) {
            $public = $this->StationPublic->find('first', array('conditions' => $conditions));
            $profile_flg = false;
        }

        $this->setConfigStation();

        if($profile_flg) {
            $title_layout = $profile['StationProfile']['station_name'];
            $this->setApp('profile', $profile['StationProfile']);
            $detail = $this->StationDetail->find('first', array('conditions' => $conditions));
            $this->setApp('detail', $detail['StationDetail']);
            $pics = $this->StationPic->find('all', array('conditions' => $conditions));
            foreach ($pics as $pic) {
                $this->setApp('pic' . $pic['StationPic']['pic_id'], $pic['StationPic']);
            }
            // #150 Start Luvina Modify
            if (count($pics) > 0) {
                $this->setApp('count_pic' , count($pics));
            }
            // #150 End Luvina Modify
            if($profile['StationProfile']['type'] == 1) {
                $this->loadModel('Interviewee');
                $listInterview = $this->Interviewee->getDetailInterview($station_CD);
                $this->setApp('interview', $listInterview);
            }
        } elseif(!empty($public)) {
            $title_layout = $public['StationPublic']['station_name'];
            $this->setApp('public', $public['StationPublic']);
        } else {
            $this->addError('error', 'こちらの訪問看護ステーションは見つかりませんでした。 お手数をおかけいたしますが、いま一度検索していただけますでしょうか。 ');
            return $this->hkRender('detail', 'default');
        }
		if(!empty($public)) {
            $this->set('title_layout' , $title_layout .'| 登録無料の訪問看護ステーションナビ' );
        } else {
            $this->set('title_layout' , $title_layout .'| 訪問看護ステーションナビ' );
        }
		/*
        $this->set('description_layout' , $title_layout .'の詳細情報。登録無料の訪問看護ステーション検索サイト' );
        */
        $this->set('keywords_layout' , $title_layout .',訪問看護ステーションナビ,ほかナビ,訪看ナビ,検索,所在地,定休日,営業日時,看護師,PT,理学療法士,OT,作業療法士,ST,言語聴覚士' );
        if($profile_flg) {
            return $this->hkRender('detail', 'default');
        }
        return $this->hkRender('detail_public', 'default');
    }
    /**
     * URL: /area/:pref
     * index
     * @author Luvina
     * @access public
     */
    public function area() {
        $pref = $this->get('pref');
        $this->setApp('pref', $pref);

        $this->loadConfig('Common.ini.php');
        $japanese_chars = Configure::read('japanese_chars');
        $this->setApp('japanese_chars', $japanese_chars);

        $aryCities = $this->Address->findCityByPref($pref);
        $listCity = $this->sortByCharsJp($aryCities, 'kana_city');
        $this->setApp('listCity', $listCity);

        $charsInList = array_keys($listCity);
        $this->setApp('charsInList', $charsInList);

        $this->set('title_layout' , $pref . ' | 訪問看護ステーションナビ' );
		/*
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        */
        $this->set('keywords_layout' , $pref . ',訪問看護ステーションナビ,ほかナビ,訪看ナビ,検索,所在地,定休日,営業日時,看護師,PT,理学療法士,OT,作業療法士,ST,言語聴覚士' );
        $this->hkRender('area', 'default');
    }

    /**
     * URL: /area/:pref/:city
     */
    public function areaCity() {
        $pref = $this->get('pref');
        $this->setApp('pref', $pref);
        $city = $this->get('city');
        $this->setApp('city', $city);

        $this->loadConfig('Common.ini.php');
        $japanese_chars = Configure::read('japanese_chars');
        $this->setApp('japanese_chars', $japanese_chars);

        $aryCities = $this->Address->findAreaByCity($pref, $city);
        $listCity = $this->sortByCharsJp($aryCities, 'kana_area');
        $this->setApp('listCity', $listCity);

        $charsInList = array_keys($listCity);
        $this->setApp('charsInList', $charsInList);

        $this->set ('title_layout' , $city .  ' | 訪問看護ステーションナビ' );
/*
        $this->set ('description_layout' , '訪問看護ステーションナビ' );
 */
        $this->set ('keywords_layout' , $city .  ',訪問看護ステーションナビ,ほかナビ,訪看ナビ,検索,所在地,定休日,営業日時,看護師,PT,理学療法士,OT,作業療法士,ST,言語聴覚士' );
        $this->hkRender('area_city', 'default');
    }

    /**
     * sortByCharsJp
     * @author Luvina
     * @access private
     * @param  array $list
     * @param  array $chars
     * @param  string $name
     * @return array $listSort
     */
    private function sortByCharsJp($list, $name) {
        //list character japan
        $this->loadConfig('Common.ini.php');
        $japanese_chars = Configure::read('japanese_chars');

        $reVal = array();
        foreach ($japanese_chars as $k => $v) {
            foreach ($v as $vdeep) {
                $reVal[$vdeep] = $k;
            }
        }

        $arySort = array();
        $listSort = array();
        foreach ($list as $value) {
            if (!empty($value['Address'][$name])) {
                $char = mb_substr($value['Address'][$name], 0, 1);
                //convert char to halfsize
                $char = mb_convert_kana ($char, "K");
                if(isset($reVal[$char])) {
                    $arySort[$reVal[$char]][] = $value['Address'];
                }
            }
        }

        //resort list
        foreach ($japanese_chars as $k => $v) {
            if (isset($arySort[$k])) {
                $listSort[$k] = $arySort[$k];
            }
        }

        return $listSort;
    }
    /**
     * set config station
     */
    private function setConfigStation() {
        $this->loadConfig('Station.ini.php');
        $this->setApp('visit_guidance', Configure::read('visit_guidance'));
        $this->setApp('24_hour_aday', Configure::read('24_hour_aday'));
        $this->setApp('pal_care', Configure::read('pal_care'));
        $this->setApp('mental_care', Configure::read('mental_care'));
        $this->setApp('kids_care', Configure::read('kids_care'));
        $this->setApp('dementia_care', Configure::read('dementia_care'));
        $this->setApp('handicapped_care', Configure::read('handicapped_care'));
        $this->setApp('hospital_link', Configure::read('hospital_link'));
        for($i = 0; $i < 50; $i++) {
            $count[$i] = $i.'人';
        }
        $count[$i] = $i.'人以上';
        $this->setApp('count', $count);
    }
}