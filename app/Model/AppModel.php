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
     *getLatAndLngByAddress : get geo location
     * @author luvina
     * @access public
     * @param string $address
     * @return location
     */
    function getLatAndLngByAddress($address) {
        $aryOutput = array();
        $aryOutput['lat'] = null;
        $aryOutput['lng'] = null;
        if(!empty($address)) {
            $address = urlencode($address);
            $google_api_key = Configure::read('geocode_key');
            $google_api_url = Configure::read('geocode_url');
            $max_rand = count($google_api_key);
            shuffle($google_api_key);
            foreach($google_api_key as $key) {
                $use_google_api_key = $key;
                $get_google_api_url = $google_api_url . 'key=' . $use_google_api_key . '&sensor=false&region=jp&address=' . $address . '&language=ja';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $get_google_api_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $geocode = curl_exec($ch);
                //error_log ("\nIP: ". $_SERVER['REMOTE_ADDR'] . "\t" . date('Y-m-d H:i:s'), 3, "/var/www/hyn/public_html/logAPI.log");
                $output = json_decode($geocode);
                curl_close($ch);

                if( $output->status == GEOCODE_OK) {
                    $result = $output->results[0];
                    $aryOutput['lat'] = $result->geometry->location->lat;
                    $aryOutput['lng'] = $result->geometry->location->lng;
                    return $aryOutput;
                } elseif($output->status == GEOCODE_ZERO_RESULTS ) {
                    $this->warning[] = $this->index . '行目 の薬局の住所を確認してください。';
                    return $aryOutput;
                } elseif($output->status == GEOCODE_OVER_QUERY_LIMIT) {
                    sleep(0.2);
                }
            }
        } else {
            $this->warning[] = $this->index . '行目 の薬局の住所を確認してください。';
        }
        return $aryOutput;
    }

    /**
     *getFulladdressByLatLng : get address by geocode
     * @author luvina
     * @access public
     * @param float $lat
     * @param float $lng
     * @return location
     */
    function getFulladdressByLatLng($lat, $lng) {
        $aryOutput['full_addess'] = null;
        $aryOutput['administrative_area_level_1'] = null;
        $aryOutput['sublocality_level_1'] = null;
        $aryOutput['locality'] = null;
        $aryOutput['ward'] = null;
        $aryOutput['colloquial_area'] = null;

        $google_api_key = Configure::read('geocode_key');
        $google_api_url = Configure::read('geocode_url');
        $max_rand = count($google_api_key);
        shuffle($google_api_key);
        foreach($google_api_key as $key) {
            $use_google_api_key = $key;
            $get_google_api_url = $google_api_url . 'key=' . $use_google_api_key . '&sensor=false&latlng=' . $lat . ',' . $lng . '&language=ja';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $get_google_api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $geocode = curl_exec($ch);
            //error_log ("\nIP: ". $_SERVER['REMOTE_ADDR'] . "\t" . date('Y-m-d H:i:s'), 3, "/var/www/hyn/public_html/logAPI.log");
            $output = json_decode($geocode);
            curl_close($ch);

            if( $output->status == GEOCODE_OK) {
                $result = $output->results[0];
                $administrative_area_level_1 = $sublocality_level_1 = $locality = $ward = $colloquial_area = '';
                foreach ($result->address_components as $type) {
                    switch ($type->types[0]) {
                    case "administrative_area_level_1" :
                        $administrative_area_level_1 = $type->long_name;
                        break;
                    case "sublocality_level_1" :
                        $sublocality_level_1 = $type->long_name;
                        break;
                    case "locality" :
                        $locality = $type->long_name;
                        break;
                    case "ward" :
                        $ward = $type->long_name;
                        break;
                    case "colloquial_area" :
                        $colloquial_area = $type->long_name;
                        break;
                    }
                }
                $aryOutput['full_addess'] = $administrative_area_level_1 . $colloquial_area . $locality . $ward . $sublocality_level_1;
                $aryOutput['administrative_area_level_1'] = $administrative_area_level_1;
                $aryOutput['sublocality_level_1'] = $sublocality_level_1;
                $aryOutput['locality'] = $locality;
                $aryOutput['ward'] = $ward;
                $aryOutput['colloquial_area'] = $colloquial_area;
                return $aryOutput;
            } elseif($output->status == GEOCODE_ZERO_RESULTS ) {
                return $aryOutput;
            } elseif($output->status == GEOCODE_OVER_QUERY_LIMIT) {
                usleep(200000);
            }
        }
        return $aryOutput;
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
            if (mb_ereg('^[ぁ-ゞー]+$', $str) !== 1) {
                return false;
            }
        }
        return true;
    }
}
