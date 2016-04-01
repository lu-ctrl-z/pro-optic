<?php
class AutoLogin extends AppModel {
    public $useTable = 't_auto_login';

    /**
     * add autologin
     * @param Int $user_id
     * @return string|boolean
     */
    public function addAutoLogin($user_id) {
        while (true) {
            $random = Util::getRandom(60);
            $ret = $this->find('first', array('conditions' => array('auto_login_key' => $random)));
            if(empty($ret)) break;
        }

        $expires_day = Configure::read('auto_login_expires');
        if(!$expires_day) $expires_day = 30; //30 days default

        $this->create();
        $this->set('id', null);
        $this->set('auto_login_key', $random);
        $this->set('user_id', $user_id);
        $this->set('expire', Date('Y-m-d H:i:s', strtotime("+$expires_day days")));
        if($this->save()) {
            return $random;
        } else {
            return false;
        }
    }
    /**
     * disable Auto Login by user_id
     * @param String $auto_login_key
     */
    public function disableAutoLogin($user_id) {
        return $this->deleteAll(array('AutoLogin.user_id' => $user_id, 'AutoLogin.expire <' => Date('Y-m-d h:i:s')), false);
    }
}