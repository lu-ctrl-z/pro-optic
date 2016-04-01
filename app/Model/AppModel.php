<?php
define('GEOCODE_OVER_QUERY_LIMIT', 'OVER_QUERY_LIMIT');
define('GEOCODE_ZERO_RESULTS',     'ZERO_RESULTS');
define('GEOCODE_OK',               'OK');
include_once  APP . 'Configapp' . DS . 'Geocode.ini.php';

/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
    var $isUploadFile = false;
    var $uploader = array();
    /**
     * afterFind : write log sql
     * @author luvina
     * @return
     */
    public function afterFind($results, $primary = false) {
        $log = $this->getDataSource()->getLog(false, false);
        CakeLog::write('sql', print_r($log, true));

        return parent::afterFind($results, $primary);
    }

    /**
     * afterFind : write log sql
     * @author luvina
     * @return
     */
    public function afterSave($results, $primary = false) {
        $log = $this->getDataSource()->getLog(false, false);
        CakeLog::write('sql', print_r($log, true));

        return parent::afterSave($results, $primary);
    }

    /**
     * afterFind : write log sql
     * @author luvina
     * @return
     */
    public function afterDelete() {
        $log = $this->getDataSource()->getLog(false, false);
        CakeLog::write('sql', print_r($log, true));

        return parent::afterDelete();
    }

    /**
     * check number and half size
     * @author luvina
     * @access public
     * @param array $field
     * @param string $field_name
     * @return string|boolean
     */
    public function checkNumberHalfsize($field, $field_name) {
        $string = isset($field[$field_name]) ? $field[$field_name] : '';
        if(!is_numeric($string)) {
            return false;
        }
        return true;
    }
    /**
     * check column file csv
     * @param array $ary
     * @param int $row
     * @return boolean
     */
    public function checkColumnCsv($ary, $row){
        if(count($ary) >= $row) {
            return true;
        }
        return false;
    }
    /**
     * checkUnique : check unique single and multi field
     * @author luvina
     * @access public
     * @param array $data
     * @param array $fields
     * @return boolean
     */
    public function checkUnique($data, $fields) {
        if (!is_array($fields)) {
            $fields = array($fields);
        }
        foreach($fields as $key) {
            $unique[$key] = $this->data[$this->name][$key];
        }
        return $this->isUnique($unique, false);
    }
    /**
     * Checks katakana
     *
     * @param array $check
     * @author Luvina
     * @return bool Success.
     */
    public function checkKatakana($check) {
        $value = array_values($check);
        $str = $value[0];
        mb_regex_encoding("UTF-8");
        if (!empty($str)) {
            if (mb_ereg('^[ァ-ヶーｱ-ﾝﾞﾟ・]+$', $str) !== 1) {
                return false;
            }
        }
        return true;
    }
    //for validate tool
    /**
     * get all field validate
     * @return array:
     */
    public function getField() {
        return array_flip( array_keys($this->validate) );
    }
    /**
     * isValidate Data
     * @param array $aryData
     * @author Luvina
     * @return boolean
     */
    public function isValidate($aryData, $aryField = false){
        if($aryField === false) {
            $aryField = $this->getField();
        }

         foreach ($aryField as $key => $val) {
            if($this->isUploadFile == true) {
                @$this->setFile($key, $aryData);
            } else {
                @$this->set($key, $aryData[$key]);
            }
        }

        return $this->validates($aryField);
    }
    /**
     * setFile
     * @param String $name
     * @param Data $data
     */
    public function setFile($name, $data) {
        if(isset($data[$name])) {
            if(!is_array($data[$name])) {
                return $this->set($name, $data[$name]);
            } else {
                $value = $data[$name];
                if($value['size'] == 0 || $value['error'] !== 0) {
                    if(isset($data['tmpUploader']) && isset($data['tmpUploader'][$name])){
                        $uploaded = unserialize($data['tmpUploader'][$name]);
                        if(!file_exists($uploaded['tmp_name'])) {
                            return $this->set($name, $data[$name]);
                        }
                        return $this->set($name, $uploaded);
                    } else {
                        return $this->set($name, $data[$name]);
                    }
                }
           }
        } elseif(isset($data['tmpUploader']) && isset($data['tmpUploader'][$name])) {
            $uploaded = unserialize($data['tmpUploader'][$name]);
            if(!file_exists($uploaded['tmp_name'])) {
                return $this->set($name, null);
            }
            return $this->set($name, $uploaded);
        }
        return $this->set($name, $data[$name]);
    }
    /**
     * requiredUpload
     * @param array $file
     */
    public function requiredUpload($file) {
        $myName = array_keys($file);
        $myName = $myName[0];
        $uploadData = array_shift($file);
        if ($uploadData['size'] == 0 || $uploadData['error'] !== 0) {
            return false;
        }
        if(!file_exists($uploadData['tmp_name'])) {
            return false;
        }
        return true;
    }
    /**
     * uploaderImage
     * @param array $file
     * @param string $key
     */
    public function uploaderImage($file, $key) {
        if($this->requiredUpload($file) == false) return true;
        $myName = array_keys($file);
        $myName = $myName[0];
        $uploadData = $file[$myName];
        $parh = '/tmp/uploaded/img/';
        $named = md5(microtime(true)) . $key;
        $filename = ROOT.$parh.$named.'.png';
        if (copy($uploadData['tmp_name'], $filename)) {
            $file[$myName]['tmp_name'] = $filename;
            $this->setUploaderData($myName, $file[$myName]);
            @unlink($uploadData['tmp_name']);
            return true;
        }
        return false;
    }
    /**
     * setUploaderData
     * @param unknown $name
     * @param unknown $value
     */
    public function setUploaderData($name, $value) {
        $this->uploader[$name] = array('file' => serialize($value));
        if(file_exists($value['tmp_name'])) {
            $this->uploader[$name]['base64'] = base64_encode(file_get_contents($value['tmp_name']));
        }
    }
    /**
     * getUploaderData
     * @param unknown $name
     * @return string
     */
    public function getUploaderData($name) {
        if(isset($this->uploader[$name])) {
            return $this->uploader[$name];
        }
        return null;
    }
    /*
     * check phone number
     */
    /**
     * check Password
     * @param unknown $data
     * @param unknown $compareField
     * @return boolean
     */
    public function inConfig($data, $configName){
        $value = array_values($data);
        $value = $value[0];
        if(empty($value)) return true;
        $configValue = Configure::read($configName);
        return isset($configValue[$value]);
    }
    /**
     * 
     * @param array $val
     */
    public function isPhoneNumber($val) {
        $value = array_values($val);
        $value = $value[0];
        $value = str_replace('-', '', $value);
        if(empty($value)) return true;
        return preg_match('/^0(([5789]0[0-9]{4}[0-9]{4})|([5789]0[0-9]{8})|([0-4|6]{1}[0-9]{4}[0-9]{4})|([0-4|6]{1}[0-9]{8})|([7-9|5]{1}[1-9]{1}[0-9]{3}[0-9]{4})|([7-9|5]{1}[1-9]{1}[0-9]{7}))$/', "" . $value);
    }

    /**
     * extension2 not required upload
     * @param unknown $check
     * @param unknown $extensions
     * @return boolean
     */
    public function extension2($check, $extensions) {
        if($this->requiredUpload($check) == false) return true;
        $check = array_shift($check);
        $extension = strtolower(pathinfo($check['name'], PATHINFO_EXTENSION));
        foreach ($extensions as $value) {
            if ($extension === strtolower($value)) {
                return true;
            }
        }
        return false;
    }
    /**
     * check mail address
     * @param unknown $check
     * @return boolean
     */
    public function checkMailaddress($check) {
        $v = array_values($check);
        $v = $v[0];
        if (!empty($v) && Configure::read('mailaddress_dns_check') && (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')) {
            $check_mailaddress = substr(strstr(trim(chop($v)), "@"), 1, strlen(strstr(trim(chop($v)), "@")));
            // MXレコード、Aレコード検索
            if (!(checkdnsrr($check_mailaddress) || checkdnsrr($check_mailaddress, 'A'))) {
                return false;
            }
        }
        return true;
    }
}
