<?php
/**
 *
 * @author Luvina
 * @access public
 * @see index()
 */
App::uses('AdminController', 'Controller/Base');
App::uses('Util', 'Service');
App::uses('GeocodeUtil', 'Service');

class AdminStationController extends AdminController {
    public $upload_folder = '/images/station/';
    public $unlink = array();
    public $uses = array('StationProfile', 'StationDetail', 'StationZone', 'StationPublic', 
                         'StationPic', 'User', 'Scope', 'Corporation');
    /**
     * URL: /portal/slist/
     * @author Luvina
     * @access public
     */
    public function sList() {
        $user = $this->getUser();
        $com_CD = null;
        // #146 Start Luvina Modify
        $conditions = array('StationProfile.delete_date' => null);
        $aryJoins = array();
        $keyWord = $this->get('keyword');
        $params = array();
        if($keyWord != '') {
            $params[] = 'keyword=' . $keyWord;
        }
        // #135 Start Luvina Fix Bug 449
        $order = array('StationProfile.update_date DESC', 'StationProfile.entry_date DESC', 'StationProfile.id DESC');
        // #135 End Luvina Fix Bug 449
        $text_placeholder = 'ステーション名を入力';
        if($this->isAdmin() || $this->isMember()) {
            $com_CD = $user['com_CD'];
            $conditions['StationProfile.com_CD'] = $com_CD;
            if ($keyWord != '') {
                $conditions['StationProfile.station_name like'] = '%'. $keyWord .'%';
            }
        } elseif($this->isSupperAdmin()) {
            $conditions['OR'] = array('StationProfile.station_name like ' => '%'. $keyWord .'%', 
                                      'Corporation.corporation_name like ' => '%'. $keyWord .'%');
            $conditions['Corporation.delete_date'] = null;
            $aryJoins = array(
                array(
                    'table' => 't_corporation',
                    'alias' => 'Corporation',
                    'type' => 'INNER',
                    'conditions' => array(
                        "StationProfile.com_CD = Corporation.com_CD"
                    )
                )
            );
            $text_placeholder = 'ステーション名、法人を入力';
        }

        $limit = 10;
        $strParams = implode('&', $params);
        $url_params = array(
                'controller' => 'adminStation',
                'action' => 'sList',
                '?' => $strParams,
        );

        $aryFields = array('StationProfile.id', 
                            'StationProfile.station_CD',
                            'StationProfile.com_CD',
                            'StationProfile.station_name',
                            'StationProfile.trust_number',
                            'StationProfile.type',
                            'StationProfile.entry_date',
                            'StationProfile.update_date',
        );
        $sCount = $this->StationProfile->find('count' , array('conditions' => $conditions, 'joins' => $aryJoins,));
        if ($sCount > 0) {
            Util::setPager('StationProfile', $sCount, $url_params, $limit, 9);
        }
        $offset = ($this->page - 1) * $limit;

        $sList = $this->StationProfile->find('all' , array( 'conditions' => $conditions,
                                                            'fields' => $aryFields,
                                                            'joins' => $aryJoins,
                                                            'order' => $order,
                                                            'limit' => $limit,
                                                            'offset' => $offset));
        $this->set('text_placeholder' , $text_placeholder);
        // #146 End Luvina Modify
        $this->setApp('sList', $sList);
        $this->set('title_layout' , '会員ポータル | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );
        $this->hkRender('slist', 'portal');
    }

    /**
     * URL: /portal/sadd/
     * add staion
     * @author Luvina
     * @access public
     */
    public function sAdd() {
        $this->nextUrl = '/portal/sadd/';
        $this->permissionAdd();
        $this->setConfigStation();
        $back = $this->get('back');
        if(isset($back)) {
            $data = $this->data;
            $isOK = $this->StationPic->isValidate($data);
            $this->setApp('uploaded_station_pic1', $this->StationPic->getUploaderData('station_pic1'));
            $this->setApp('uploaded_station_pic2', $this->StationPic->getUploaderData('station_pic2'));
            $this->setApp('uploaded_station_pic3', $this->StationPic->getUploaderData('station_pic3'));
        }
        $this->set('title_layout' , 'ステーション情報：登録 | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );
        $this->hkRender('sadd', 'portal');
    }

    /**
     * URL: /portal/sadd/preview
     * confirm staion
     * @author Luvina
     * @access public
     */
    public function sAddPreview() {
        $this->nextUrl = '/portal/sadd/';
        $data = $this->data;
        $this->permissionAdd();
        $this->setConfigStation();

        $this->set('title_layout' , 'ステーション情報：登録確認 | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );

        $OK = @$this->validateStation();
        if(!$OK) {
            return $this->hkRender('sadd', 'portal');
        }

        return $this->hkRender('sadd_preview', 'portal');
    }
    /**
     * URL: /portal/sadd/do
     */
    public function sAddDo() {
        $this->set('title_layout' , 'ステーション情報：登録 | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );
        $this->nextUrl = '/portal/sadd/';
        $mail_to = array();
        $data = $this->data;
        $this->permissionAdd();
        $this->setConfigStation();
        if($this->CsrfSecurity->validateCsrf($this) === false) {
            $this->Session->setFlash('不正なリクエストです。手順の始めからやり直してください', 'default', array('class' => "form-error-message"), 'message');
            return $this->redirect('/portal/');
        }

        $OK = @$this->validateStation();
        if(!$OK) {
            return $this->hkRender('sadd', 'portal');
        }

        $stationProfile = $this->StationProfile;
        $DB = $stationProfile->getDataSource();
        $DB->begin();
        unset($data['station_CD']);
        $stationProfile->create();
        $stationProfile->set($data);
        $stationProfile->set('id', null);
        $com_CD = null;
        if($this->isSupperAdmin()) {
            $com_CD = $this->instateCom_CD;
        } else {
            $userLogin = $this->User->find('first', array('conditions' => array('id' => $this->getUserId(), 'delete_date' => null)));
            if(!$userLogin) {
                $DB->rollback();
                $this->addError('error', 'DBの登録でエラーがありました。');
                return $this->hkRender('sadd', 'portal');
            } else {
                $com_CD = $userLogin['User']['com_CD'];
            }
        }
        $stationProfile->set('com_CD', $com_CD);
        $stationProfile->set('entry_date', Date('Y-m-d H:i:s'));
        $stationProfile->set('entry_user', $this->getUserId());
        $stationProfile->set('update_date', Date('Y-m-d H:i:s'));
        $stationProfile->set('update_user', $this->getUserId());
        $stationProfile->set('flag_set', 1);
        $stationProfile->set('type', 0);
        $stationProfile->set('station_CD', 0);
        $saved = $stationProfile->save();
        if(!$saved) {
            $DB->rollback();
            $this->addError('error', 'DBの登録でエラーがありました。');
            return $this->hkRender('sadd', 'portal');
        }

        $station_CD = sprintf("%05s%06s", $com_CD, $saved['StationProfile']['id']);
        $stationProfile->set('station_CD', $station_CD);
        $saved = $stationProfile->save();
        if(!$saved) {
            $DB->rollback();
            $this->addError('error', 'DBの登録でエラーがありました。');
            return $this->hkRender('sadd', 'portal');
        }

        $stationDetail = $this->StationDetail;
        $stationDetail->create();
        $stationDetail->set($data);
        $stationDetail->set('id', null);
        $stationDetail->set('entry_date', Date('Y-m-d H:i:s'));
        $stationDetail->set('entry_user', $this->getUserId());
        $stationDetail->set('update_date', Date('Y-m-d H:i:s'));
        $stationDetail->set('update_user', $this->getUserId());
        $stationDetail->set('station_CD', $station_CD);
        $saved = $stationDetail->save();
        if(!$saved) {
            $DB->rollback();
            $this->addError('error', 'DBの登録でエラーがありました。');
            return $this->hkRender('sadd', 'portal');
        }

        $stationZone = $this->StationZone;
        $stationZone->create();
        $stationZone->set($data);
        $stationZone->set('id', null);
        $latLng = GeocodeUtil::getLatLngFromCookie($data['address']);
        $stationZone->set('latitude', $latLng['latitude']);
        $stationZone->set('longitude', $latLng['longitude']);
        $stationZone->set('flag_set', 1);
        $stationZone->set('kubun', 1);
        $stationZone->set('entry_date', Date('Y-m-d H:i:s'));
        $stationZone->set('entry_user', $this->getUserId());
        $stationZone->set('update_date', Date('Y-m-d H:i:s'));
        $stationZone->set('update_user', $this->getUserId());
        $stationZone->set('station_CD', $station_CD);
        $saved = $stationZone->save();
        if(!$saved) {
            $DB->rollback();
            $this->addError('error', 'DBの登録でエラーがありました。');
            return $this->hkRender('sadd', 'portal');
        }

        $stationPic = $this->StationPic;
        $stationPic->create();
        if(!is_null($this->getApp('uploaded_station_pic1'))) {
            $stationPic->set('id', null);
            $stationPic->set('station_CD', $station_CD);
            if(!$this->_saveStationPic($stationPic, 1)) {
                $DB->rollback();
                $this->addError('error', 'DBの登録でエラーがありました。');
                return $this->hkRender('sadd', 'portal');
            }
        }
        if(!is_null($this->getApp('uploaded_station_pic2'))) {
            $stationPic->set('id', null);
            $stationPic->set('station_CD', $station_CD);
            if(!$this->_saveStationPic($stationPic, 2)) {
                $DB->rollback();
                $this->addError('error', 'DBの登録でエラーがありました。');
                return $this->hkRender('sadd', 'portal');
            }
        }
        if(!is_null($this->getApp('uploaded_station_pic3'))) {
            $stationPic->set('id', null);
            $stationPic->set('station_CD', $station_CD);
            if(!$this->_saveStationPic($stationPic, 3)) {
                $DB->rollback();
                $this->addError('error', 'DBの登録でエラーがありました。');
                return $this->hkRender('sadd', 'portal');
            }
        }

        foreach ($this->unlink as $v) {
            @unlink($v);
        }

        $DB->commit();

        $dataUser = $this->getUser();
        $mail_to[$dataUser['id']] = $dataUser;

        $userAdmin = $this->User->find('first', array(
                'conditions' => array('com_CD' => $com_CD, 'user_mode' => 2, 'delete_date' => null)));
        if($userAdmin) {
            $mail_to[$userAdmin['User']['id']] = $userAdmin['User'];
        }
        $subject = "[ho-kan.jp] ステーション情報登録完了のお知らせ（訪問看護ステーションナビ）";
        $mailAdmin = Configure::read('config_mail_admin');
        $base_url = Configure::read('base_url');
        $mailFrom = $mailAdmin['from'];
        // #144 Start Luvina Modify
        $mail_to = $this->setArrayMailConfig($mail_to);
        // #144 End Luvina Modify
        // #147 Start Luvina Modify
        $com_name = $this->Corporation->getCorporationName($com_CD);

        foreach ($mail_to as $userInfo) {
            try {
            $this->sendMail( $userInfo['user_mail'], $mailFrom, $subject, 'sadd_do',
                    array('site_name' => $base_url,
                            'name_sei' => $userInfo['name_sei'],
                            'name_mei' => $userInfo['name_mei'],
                            'com_name' => $com_name,
                            'station_CD' => $station_CD,
                            'station_name' => $data['station_name']), 
                            'text');
            } catch (Exception $e) {}
        }
        // #147 End Luvina Modify

        return $this->redirect('/portal/sadd/send');
    }
    /**
     * URL: /portal/sadd/send/
     */
    public function sAddSend() {
        $this->set('title_layout' ,'ステーション情報：登録送信 | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );
        return $this->hkRender('sadd_send', 'portal');
    }
    /**
     * saveStationPic
     * @param Object $stationPic
     * @param array $file_info
     * @param int $id
     * @return boolean
     */
    private function _saveStationPic($stationPic, $id) {
        $uploaded_pic = $this->getApp('uploaded_station_pic'.$id);
        $file_info = unserialize($uploaded_pic['file']);
        $stationPic->set('entry_date', Date('Y-m-d H:i:s'));
        $stationPic->set('entry_user', $this->getUserId());
        $stationPic->set('update_date', Date('Y-m-d H:i:s'));
        $stationPic->set('update_user', $this->getUserId());
        $stationPic->set('flag_set', 1);
        $stationPic->set('pic_id', $id);
        $dest = WWW_ROOT . $this->upload_folder;
        if($file_info) {
            $source = $file_info['tmp_name'];
            $filename = basename($source);
            if (copy($source, $dest.$filename)) {
                $stationPic->set('pic_path', $this->upload_folder.$filename);
                $saved = $stationPic->save();
                if(!$saved) return false;
                $this->unlink[] = $source;
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    /**
     * validateStation
     */
    private function validateStation() {
        $data = $this->data;
        $isOK = $this->StationDetail->isValidate($data);
        if(!$isOK) {
            $this->addError($this->StationDetail->validationErrors);
        }

        $isOK = $this->StationProfile->isValidate($data);
        if(!$isOK) {
            $this->addError($this->StationProfile->validationErrors);
        } 
        if(!empty($data['address'])) {
            $latLng = GeocodeUtil::getLatLngFromCookie($data['address']);
            if(is_null($latLng['latitude']) || is_null($latLng['longitude'])) {
                $this->addError('address', '所在地の確認に失敗いたしました。恐れ入りますがご記入いただきました所在地をご確認いただけますでしょうか。');
            }
        }

        $isOK = $this->StationZone->isValidate($data);
        if(!$isOK) {
            $this->addError($this->StationZone->validationErrors);
        }

        $isOK = $this->StationPic->isValidate($data);
        $this->setApp('uploaded_station_pic1', $this->StationPic->getUploaderData('station_pic1'));
        $this->setApp('uploaded_station_pic2', $this->StationPic->getUploaderData('station_pic2'));
        $this->setApp('uploaded_station_pic3', $this->StationPic->getUploaderData('station_pic3'));
        if(!$isOK) {
            $this->addError($this->StationPic->validationErrors);
        }

        if($this->errorCount() > 0) {
            return false;
        } else {
            return true;
        }
    }
    /**
     * check permission Add Station
     * @return void|boolean
     */
    private function permissionAdd() {
        if($this->isMember() || $this->isAdmin()) {
            return true;
        } elseif($this->isSupperAdmin()) {
            $instate_id = $this->get('instate_id');
            if($instate_id) {
                $instateUser = $this->User->find('first', array(
                        'conditions' => array('id' => $instate_id, 'user_mode' => 2, 'delete_date' => null)));
                if($instateUser) {
                    $this->instateCom_CD = $instateUser['User']['com_CD'];
                    return true;
                } else {
                    return $this->redirect('/portal/confirm/?next=' . rawurlencode($this->nextUrl));
                }
            } else {
                return $this->redirect('/portal/confirm/?next=' . rawurlencode($this->nextUrl));
            }
        }
        return true;
    }

    /**
     * URL: /portal/sedit
     */
    public function sEdit() {
        $station_CD = $this->get('id');
        $this->setConfigStation();
        $this->set('title_layout' , 'ステーション情報：編集 | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' ); 

        $yes = $this->permissionEdit();
        if(!$yes) {
            $this->addError('error', 'こちらの訪問看護ステーションは見つかりませんでした。お手数をおかけいたしますが、いま一度URLをご確認ください。');
            return $this->hkRender('sedit', 'portal');
        }

        $conditions['station_CD'] = $station_CD;
        $conditions['delete_date'] = null;
        $stationProfile = $this->StationProfile->find('first', array('conditions' => $conditions));
        $stationProfile = $stationProfile['StationProfile'];
        $this->exportParams($stationProfile);

        $stationDetail = $this->StationDetail->find('first', array('conditions' => $conditions));
        $stationDetail = $stationDetail['StationDetail'];
        $this->exportParams($stationDetail);

        $stationPic = $this->StationPic->find('all', array('conditions' => $conditions));
        foreach ($stationPic as $k => $val) {
            $this->setApp('stationPic'.$val['StationPic']['pic_id'], $val['StationPic']);
        }

        $stationZone = $this->StationZone->find('first', array('conditions' => $conditions));
        $stationZone = $stationZone['StationZone'];
        $this->exportParams($stationZone);

        $back = $this->get('back');
        if(isset($back)) {
            $data = $this->data;
            if(isset($data['tmpUploader']) && isset($data['tmpUploader']['station_pic1'])) {
                $this->StationPic->setUploaderData('station_pic1', unserialize($data['tmpUploader']['station_pic1']));
                $this->setApp('uploaded_station_pic1', $this->StationPic->getUploaderData('station_pic1'));
            }
            if(isset($data['tmpUploader']) && isset($data['tmpUploader']['station_pic2'])) {
                $this->StationPic->setUploaderData('station_pic2', unserialize($data['tmpUploader']['station_pic2']));
                $this->setApp('uploaded_station_pic2', $this->StationPic->getUploaderData('station_pic2'));
            }
            if(isset($data['tmpUploader']) && isset($data['tmpUploader']['station_pic3'])) {
                $this->StationPic->setUploaderData('station_pic3', unserialize($data['tmpUploader']['station_pic3']));
                $this->setApp('uploaded_station_pic3', $this->StationPic->getUploaderData('station_pic3'));
            }
            // #151 Start Luvina Modify
            if(!isset($data['tmpDelUploader']['pic1'])) {
               $this->setApp('stationPic1',array());
            }
            if(!isset($data['tmpDelUploader']['pic2'])) {
               $this->setApp('stationPic2',array());
            }
            if(!isset($data['tmpDelUploader']['pic3'])) {
               $this->setApp('stationPic3',array());
            }
            // #151 End Luvina Modify
        }
        $this->hkRender('sedit', 'portal');
    }
    /**
     * URL: /portal/sedit/preview
     */
    public function sEditPreview() {
        $this->setConfigStation();

        $this->set('title_layout' , 'ステーション情報：編集確認 | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );

        $yes = $this->permissionEdit();
        if(!$yes) {
            $this->addError('error', 'こちらの訪問看護ステーションは見つかりませんでした。お手数をおかけいたしますが、いま一度URLをご確認ください。');
            return $this->hkRender('sedit', 'portal');
        }

        $isOK = $this->validateStationEdit();
        if(!$isOK) {
            return $this->hkRender('sedit', 'portal');
        }

        return $this->hkRender('sedit_preview', 'portal');
    }
    /**
     * URL: /portal/sedit/do
     */
    public function sEditDo() {
        $this->set('title_layout' , 'ステーション情報：編集確認 | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );
        if($this->CsrfSecurity->validateCsrf($this) === false) {
            $this->Session->setFlash('不正なリクエストです。手順の始めからやり直してください', 'default', array('class' => "form-error-message"), 'message');
            return $this->redirect('/portal/');
        }

        $this->setConfigStation();
        $yes = $this->permissionEdit();
        if(!$yes) {
            $this->addError('error', 'こちらの訪問看護ステーションは見つかりませんでした。お手数をおかけいたしますが、いま一度URLをご確認ください。');
            return $this->hkRender('sedit', 'portal');
        }
        $isOK = $this->validateStationEdit();
        if(!$isOK) {
            return $this->hkRender('sedit', 'portal');
        }

        $station_CD = $this->get('id');
        $conditions['station_CD'] = $station_CD;
        $conditions['delete_date'] = null;

        $Profile = $this->StationProfile->find('first', array('conditions' => $conditions));
        $Detail = $this->StationDetail->find('first', array('conditions' => array('station_CD' => $station_CD)));
        $Zone = $this->StationZone->find('first', array('conditions' => $conditions));
        // #151 Start Luvina Modify
        $data = $this->data;
        $stationProfile = $this->StationProfile;
        $DB = $stationProfile->getDataSource();
        $DB->begin();
        $stationProfile->create();
        $stationProfile->set($data);
        $stationProfile->set('id', $Profile['StationProfile']['id']);
        $stationProfile->set('station_CD', $Profile['StationProfile']['station_CD']);
        $stationProfile->set('update_date', Date('Y-m-d H:i:s'));
        $stationProfile->set('update_user', $this->getUserId());
        $stationProfile->set('flag_set', $Profile['StationProfile']['flag_set']);
        $stationProfile->set('com_CD', $Profile['StationProfile']['com_CD']);
        $stationProfile->set('type', $Profile['StationProfile']['type']);
        $saved = $stationProfile->save();
        if(!$saved) {
            $DB->rollback();
            $this->addError('error', 'DBの登録でエラーがありました。');
            return $this->hkRender('sedit', 'portal');
        }

        $stationDetail = $this->StationDetail;
        $stationDetail->create();
        $stationDetail->set($data);
        $stationDetail->set('id', $Detail['StationDetail']['id']);
        $stationDetail->set('station_CD', $Detail['StationDetail']['station_CD']);
        $stationDetail->set('update_date', Date('Y-m-d H:i:s'));
        $stationDetail->set('update_user', $this->getUserId());
        $saved = $stationDetail->save();
        if(!$saved) {
            $DB->rollback();
            $this->addError('error', 'DBの登録でエラーがありました。');
            return $this->hkRender('sedit', 'portal');
        }

        $stationZone = $this->StationZone;
        $stationZone->create();
        $stationZone->set($data);
        $stationZone->set('id', $Zone['StationZone']['id']);
        $stationZone->set('station_CD', $Zone['StationZone']['station_CD']);
        $stationZone->set('flag_set', $Zone['StationZone']['flag_set']);
        $stationZone->set('kubun', $Zone['StationZone']['kubun']);
        $stationZone->set('update_date', Date('Y-m-d H:i:s'));
        $stationZone->set('update_user', $this->getUserId());
        $latLng = GeocodeUtil::getLatLngFromCookie($data['address']);
        $stationZone->set('latitude', $latLng['latitude']);
        $stationZone->set('longitude', $latLng['longitude']);
        $saved = $stationZone->save();
        if(!$saved) {
            $DB->rollback();
            $this->addError('error', 'DBの登録でエラーがありました。');
            return $this->hkRender('sedit', 'portal');
        }

        $stationPic = $this->StationPic;
        $stationPic->create();

        $aryPicNotDelete = array();
        $listPic = $this->StationPic->find('all', array('conditions' => $conditions));
        if (count($listPic) > 0) {
            if (isset($data['tmpDelUploader']['pic1']) && $data['tmpDelUploader']['pic1'] != '') {
                $aryPicNotDelete[] = $data['tmpDelUploader']['pic1'];
            }
            if (isset($data['tmpDelUploader']['pic2']) && $data['tmpDelUploader']['pic2'] != '') {
                $aryPicNotDelete[] = $data['tmpDelUploader']['pic2'];
            }
            if (isset($data['tmpDelUploader']['pic3']) && $data['tmpDelUploader']['pic3'] != '') {
                $aryPicNotDelete[] = $data['tmpDelUploader']['pic3'];
            }
            if(!$this->_deleteStationPic($stationPic, $aryPicNotDelete, $station_CD)) {
                $DB->rollback();
                $this->addError('error', 'DBの登録でエラーがありました。');
                return $this->hkRender('sedit', 'portal');
            }
        }

        $listPic = $this->StationPic->find('all', array('conditions' => $conditions));
        foreach ($listPic as $val) {
            $idsPic[$val['StationPic']['pic_id']] = $val;
        }
        // #151 End Luvina Modify
        //$stationPic->set($data);
        if(!is_null($this->getApp('uploaded_station_pic1'))) {
            $picInfo = isset($idsPic[1]) ? $idsPic[1]['StationPic']: array();
            if(!$this->_editStationPic($stationPic, 1, $station_CD, $picInfo)) {
                $DB->rollback();
                $this->addError('error', 'DBの登録でエラーがありました。');
                return $this->hkRender('sedit', 'portal');
            }
        }
        if(!is_null($this->getApp('uploaded_station_pic2'))) {
            $picInfo = isset($idsPic[2]) ? $idsPic[2]['StationPic']: array();
            if(!$this->_editStationPic($stationPic, 2, $station_CD, $picInfo)) {
                $DB->rollback();
                $this->addError('error', 'DBの登録でエラーがありました。');
                return $this->hkRender('sedit', 'portal');
            }
        }
        if(!is_null($this->getApp('uploaded_station_pic3'))) {
            $picInfo = isset($idsPic[3]) ? $idsPic[3]['StationPic']: array();
            if(!$this->_editStationPic($stationPic, 3, $station_CD, $picInfo)) {
                $DB->rollback();
                $this->addError('error', 'DBの登録でエラーがありました。');
                return $this->hkRender('sedit', 'portal');
            }
        }

        foreach ($this->unlink as $v) {
            @unlink($v);
        }

        $DB->commit();

        $mail_to = array();
        $dataUser = $this->getUser();
        $mail_to[$dataUser['id']] = $dataUser;
        $com_CD = $Profile['StationProfile']['com_CD'];
        $userAdmin = $this->User->find('first', array(
                'conditions' => array('com_CD' => $com_CD, 'user_mode' => 2, 'delete_date' => null)));
        if($userAdmin) {
            $mail_to[$userAdmin['User']['id']] = $userAdmin['User'];
        }
        $subject = "[ho-kan.jp] ステーション情報変更完了のお知らせ（訪問看護ステーションナビ）";
        $mailAdmin = Configure::read('config_mail_admin');
        $base_url = Configure::read('base_url');
        $mailFrom = $mailAdmin['from'];
        // #144 Start Luvina Modify
        $mail_to = $this->setArrayMailConfig($mail_to);
        // #144 End Luvina Modify
        // #147 Start Luvina Modify
        $com_name = $this->Corporation->getCorporationName($com_CD);

        foreach ($mail_to as $userInfo) {
            try {
            $this->sendMail( $userInfo['user_mail'], $mailFrom, $subject, 'sedit_do', 
                        array('site_name' => $base_url, 
                              'name_sei' => $userInfo['name_sei'],
                              'name_mei' => $userInfo['name_mei'],
                              'com_name' => $com_name,
                              'station_CD' => $station_CD,
                              'station_name' => $data['station_name']),
                              'text');
            } catch (Exception $e) {}
        }
        // #147 End Luvina Modify
        return $this->redirect('/portal/sedit/send');
    }
    /**
     * URL: /portal/sedit/send
     */
    public function sEditSend() {
        $this->set('title_layout' , 'ステーション情報：編集送信 | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );
        return $this->hkRender('sedit_send', 'portal');
    }
    /**
     * _editStationPic
     * @param Object $stationPic
     * @param int $id 1|2|3
     * @param String $station_CD
     * @param array $picInfo
     * @return boolean
     */
    private function _editStationPic($stationPic, $id, $station_CD, $picInfo = false) {
        $isInsert = false;
        if(empty($picInfo)) $isInsert = true;
        $uploaded_pic = $this->getApp('uploaded_station_pic'.$id);
        $file_info = unserialize($uploaded_pic['file']);
        if($isInsert) {
            $stationPic->set('id', null);
            $stationPic->set('entry_date', Date('Y-m-d H:i:s'));
            $stationPic->set('entry_user', $this->getUserId());
            $stationPic->set('flag_set', 1);
            $stationPic->set('pic_id', $id);
            $stationPic->set('station_CD', $station_CD);
        } else {
            $stationPic->set('id', $picInfo['id']);
            $stationPic->set('flag_set', $picInfo['flag_set']);
            $stationPic->set('pic_id', $picInfo['pic_id']);
            $stationPic->set('station_CD', $picInfo['station_CD']);
        }
        $stationPic->set('update_date', Date('Y-m-d H:i:s'));
        $stationPic->set('update_user', $this->getUserId());
        $dest = WWW_ROOT . $this->upload_folder;
        if($file_info) {
            $source = $file_info['tmp_name'];
            $filename = basename($source);
            if (copy($source, $dest.$filename)) {
                $stationPic->set('pic_path', $this->upload_folder.$filename);
                $saved = $stationPic->save();
                if($saved) {
                    $this->unlink[] = $source;
                    if(isset($picInfo['pic_path'])) {
                        $this->unlink[] = WWW_ROOT . $picInfo['pic_path'];
                    }
                    return true;
                }
                return false;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    // #151 Start Luvina Modify
    /**
     * _deleteStationPic
     * @param Object $stationPic
     * @param String $station_CD
     * @param array $aryPicNotDelete
     * @return boolean
     */
    private function _deleteStationPic($stationPic, $aryPicNotDelete, $station_CD) {
        $stationPic->getDataSource;
        $conditions['station_CD'] = $station_CD;
        if(count($aryPicNotDelete) > 0) {
            $conditions["NOT"]["pic_path"] = $aryPicNotDelete;
        }

        $updateData['delete_date'] = "'" . date('Y-m-d H:i:s') ."'";
        $updateData['delete_user'] = "'" . $this->getUserId(). "'" ;
        $ok = $stationPic->updateAll($updateData, $conditions);
        if($ok === false) {
            return false;
        }
        $dest = WWW_ROOT . $this->upload_folder;
        foreach($aryPicNotDelete as $file){
            $picInfo = $dest. $file;
            if($picInfo) {
                @unlink($picInfo);
            }
        }
        return true;
    }
    // #151 End Luvina Modify
    /**
     * validate Station
     * @return boolean
     */
    private function validateStationEdit() {
        $data = $this->data;

        $sdFields = $this->StationDetail->getField();
        unset($sdFields['station_CD']);
        $isOK = $this->StationDetail->isValidate($data, $sdFields);
        if(!$isOK) {
            $this->addError($this->StationDetail->validationErrors);
        }

        $spFields = $this->StationProfile->getField();
        unset($spFields['station_CD']);
        $isOK = $this->StationProfile->isValidate($data, $spFields);
        if(!$isOK) {
            $this->addError($this->StationProfile->validationErrors);
        }

        $szFields = $this->StationZone->getField();
        unset($szFields['station_CD']);
        $isOK = $this->StationZone->isValidate($data, $szFields);
        if(!$isOK) {
            $this->addError($this->StationZone->validationErrors);
        }
        if(!empty($data['address'])) {
            $latLng = GeocodeUtil::getLatLngFromCookie($data['address']);
            if(is_null($latLng['latitude']) || is_null($latLng['longitude'])) {
                $this->addError('address', '所在地の確認に失敗いたしました。恐れ入りますがご記入いただきました所在地をご確認いただけますでしょうか。');
            }
        }

        $station_CD = $this->get('id');
        $conditions['station_CD'] = $station_CD;
        $conditions['delete_date'] = null;
        $stationPic = $this->StationPic->find('all', array('conditions' => $conditions));
        $id = null;
        foreach ($stationPic as $k => $val) {
            $this->setApp('stationPic'.$val['StationPic']['pic_id'], $val['StationPic']);
            $id = $val['StationPic']['id'];
        }

        $spiFields = $this->StationPic->getField();
        unset($spiFields['station_CD']);
        $this->StationPic->set('id', $id);
        $isOK = $this->StationPic->isValidate($data, $spiFields);
        $this->setApp('uploaded_station_pic1', $this->StationPic->getUploaderData('station_pic1'));
        $this->setApp('uploaded_station_pic2', $this->StationPic->getUploaderData('station_pic2'));
        $this->setApp('uploaded_station_pic3', $this->StationPic->getUploaderData('station_pic3'));
        // #151 Start Luvina Modify
        if(!isset($data['tmpDelUploader']['pic1'])) {
           $this->setApp('stationPic1',array());
        }
        if(!isset($data['tmpDelUploader']['pic2'])) {
           $this->setApp('stationPic2',array());
        }
        if(!isset($data['tmpDelUploader']['pic3'])) {
           $this->setApp('stationPic3',array());
        }
        // #151 End Luvina Modify
        $picErrors = $this->StationPic->validationErrors;
        if(!$isOK) {
            $this->addError($this->StationPic->validationErrors);
        }

        if($this->errorCount() > 0) {
            return false;
        } else {
            return true;
        }
    }
    /**
     * permissionEdit
     * @return boolean
     */
    private function permissionEdit() {
        $station_CD = $this->get('id');
        if(!$station_CD) return false;

        $conditions['delete_date'] = null;
        $conditions['station_CD'] = $station_CD;
        if($this->isAdmin() || $this->isMember()) {
            $user = $this->getUser();
            $conditions['com_CD'] = $user['com_CD'];
        }

        $list = $this->StationProfile->find('first', array('conditions' => $conditions));
        if(empty($list)) return false;

        return true;
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
        $this->setApp('station_function', Configure::read('station_function'));
        for($i = 0; $i < 50; $i++) {
            $count[$i] = $i;
        }
        $count[$i] = $i.'人以上';
        $this->setApp('count', $count);
        $scope = $this->Scope->find('all');
        $this->setApp('scope', $scope);
    }

    /**
     * URL: /portal/sdel/
     */
    public function sDel() {
        $station_CD = $this->get('id');
        $this->setConfigStation();

        $this->set('title_layout' , 'ステーション削除 | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );

        $yes = $this->permissionEdit();
        if(!$yes) {
            $this->addError('error', 'こちらの訪問看護ステーションは見つかりませんでした。お手数をおかけいたしますが、いま一度URLをご確認ください。');
            return $this->hkRender('sdel', 'portal');
        }

        $conditions['station_CD'] = $station_CD;
        $conditions['delete_date'] = null;
        $stationProfile = $this->StationProfile->find('first', array('conditions' => $conditions));
        $stationProfile = $stationProfile['StationProfile'];
        $this->exportParams($stationProfile);

        $stationDetail = $this->StationDetail->find('first', array('conditions' => array('station_CD' => $station_CD)));
        $stationDetail = $stationDetail['StationDetail'];
        $this->exportParams($stationDetail);

        $stationPic = $this->StationPic->find('all', array('conditions' => $conditions));
        foreach ($stationPic as $k => $val) {
            $this->setApp('stationPic'.$val['StationPic']['pic_id'], $val['StationPic']);
        }

        $stationZone = $this->StationZone->find('first', array('conditions' => $conditions));
        $stationZone = $stationZone['StationZone'];
        $this->exportParams($stationZone);

        $this->hkRender('sdel', 'portal');
    }
    /**
     * URL: /portal/sdel/do
     */
    public function sDelDo() {
        $this->set('title_layout' , 'ステーション削除 | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );
        $station_CD = $this->get('id');
        $this->setConfigStation();
        $yes = $this->permissionEdit();
        if(!$yes) {
            $this->addError('error', 'こちらの訪問看護ステーションは見つかりませんでした。お手数をおかけいたしますが、いま一度URLをご確認ください。');
            return $this->hkRender('sdel', 'portal');
        }
        $Profile = $this->StationProfile->find('first', array('conditions' => array('station_CD' => $station_CD, 'delete_date' => null)));

        $DB = $this->StationProfile->getDataSource();
        $DB->begin();
        $aryUpdate['delete_date'] = $DB->value(Date('Y-m-d H:i:s'));
        $aryUpdate['delete_user'] = $this->getUserId();
        $aryUpdateConditions['station_CD'] = $station_CD;
        $aryUpdateConditions['delete_date'] = null;

        $ok = $this->StationProfile->updateAll($aryUpdate, $aryUpdateConditions);
        if($ok === false) {
            $DB->rollback();
            $this->addError('error', 'DBの登録でエラーがありました。');
            return $this->hkRender('sdel', 'portal');
        }

        $ok = $this->StationDetail->updateAll($aryUpdate, $aryUpdateConditions);
        if($ok === false) {
            $DB->rollback();
            $this->addError('error', 'DBの登録でエラーがありました。');
            return $this->hkRender('sdel', 'portal');
        }

        $ok = $this->StationZone->updateAll($aryUpdate, $aryUpdateConditions);
        if($ok === false) {
            $DB->rollback();
            $this->addError('error', 'DBの登録でエラーがありました。');
            return $this->hkRender('sdel', 'portal');
        }

        $ret = $this->StationPic->updateAll($aryUpdate, $aryUpdateConditions);
        if($ret === false) {
            $DB->rollback();
            $this->addError('error', 'DBの登録でエラーがありました。');
            return $this->hkRender('sdel', 'portal');
        }

        $DB->commit();

        $mail_to = array();
        $dataUser = $this->getUser();
        $mail_to[$dataUser['id']] = $dataUser;
        $com_CD = $Profile['StationProfile']['com_CD'];
        $userAdmin = $this->User->find('first', array(
                'conditions' => array('com_CD' => $com_CD, 'user_mode' => 2, 'delete_date' => null)));
        if($userAdmin) {
            $mail_to[$userAdmin['User']['id']] = $userAdmin['User'];
        }
        $subject = "[ho-kan.jp] ステーション情報削除完了のお知らせ（訪問看護ステーションナビ）";
        $mailAdmin = Configure::read('config_mail_admin');
        $base_url = Configure::read('base_url');
        $mailFrom = $mailAdmin['from'];
        // #144 Start Luvina Modify
        $mail_to = $this->setArrayMailConfig($mail_to);
        // #144 End Luvina Modify
        // #147 Start Luvina Modify
        $com_name = $this->Corporation->getCorporationName($com_CD);
        foreach ($mail_to as $userInfo) {
            try {
                $this->sendMail( $userInfo['user_mail'], $mailFrom, $subject, 'sdel_do',
                        array('site_name' => $base_url,
                                'name_sei' => $userInfo['name_sei'],
                                'name_mei' => $userInfo['name_mei'],
                                'com_name' => $com_name,),
                        'text');
            } catch (Exception $e) {}
        }
        // #147 End Luvina Modify
        return $this->redirect('/portal/sdel/send');
    }
    /**
     * URL: /portal/sdel/send
     */
    public function sDelSend() {
        $this->set('title_layout' , 'ステーション削除 | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );
        $this->hkRender('sdel_send', 'portal');
    }
}