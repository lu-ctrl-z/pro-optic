<?php
include_once  APP . 'Configapp' . DS . 'Geocode.ini.php';
if(!defined('GEOCODE_OVER_QUERY_LIMIT'))
    define('GEOCODE_OVER_QUERY_LIMIT',      'OVER_QUERY_LIMIT');

if(!defined('GEOCODE_ZERO_RESULTS'))
    define('GEOCODE_ZERO_RESULTS',          'ZERO_RESULTS');

if(!defined('GEOCODE_OK'))
    define('GEOCODE_OK',                    'OK');

if(!defined('SUBLOCALITY_LEVEL_1'))
    define('SUBLOCALITY_LEVEL_1',           'sublocality_level_1');

if(!defined('LOCALITY'))
    define('LOCALITY',                      'locality');

if(!defined('ADMINISTRATIVE_AREA_LEVEL_1'))
    define('ADMINISTRATIVE_AREA_LEVEL_1',   'administrative_area_level_1');

if(!defined('GEOMETRY_LOCATION_LAT'))
    define('GEOMETRY_LOCATION_LAT',         'geometry_location_lat');

if(!defined('GEOMETRY_LOCATION_LNG'))
    define('GEOMETRY_LOCATION_LNG',         'geometry_location_lng');

if(!defined('ADDRESS'))
    define('ADDRESS',                       'address');
/**
 * GeocodeUtil
 * @author Luvina
 * @access public
 */
class GeocodeUtil {
    /**
     * function check trust or don't trust cookie data
     * @param string $address
     * @return boolean
     */
    function isTrustCookie($address) {
        $geocode = isset($_COOKIE['geocode']) ? @json_decode($_COOKIE['geocode'], true) : null;
        return !is_null($geocode) && isset($geocode[ADDRESS]) && ($geocode['status'] == "OK") && $geocode[ADDRESS] == $address;
    }
    /**
     * getLatLngFromCookie
     */
    function getLatLngFromCookie($address) {
        $ret['latitude'] = null;
        $ret['longitude'] = null;
        if(self::isTrustCookie($address)) {
            $geocode = @json_decode($_COOKIE['geocode'], true);
            $ret['latitude'] = $geocode[GEOMETRY_LOCATION_LAT];
            $ret['longitude'] = $geocode[GEOMETRY_LOCATION_LNG];
            return $ret;
        }
        $callAPI = self::getLatLngFromAddress($address);
        if($callAPI !== false) {
            $ret['latitude']  = $callAPI['lat'];
            $ret['longitude'] = $callAPI['lng'];
        }
        return $ret;
    }
    /**
     * get latitue, lngtitue from address
     * @param string $address
     * @return NULL|FALSE|ARRAY
     */
    function getLatLngFromAddress($address) {
        $aryOutput['lat'] = null;
        $aryOutput['lng'] = null;
        if(!empty($address)) {
            $address = urlencode($address);
            $google_api_key = Configure::read('geocode_key');
            $google_api_url = Configure::read('geocode_url');
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
                    return $aryOutput;
                } elseif($output->status == GEOCODE_OVER_QUERY_LIMIT) {
                    usleep(200000);
                }
            }
        } else {
            return false;
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
    function getFulladdressFromLatLng($lat, $lng) {
        $aryOutput['full_addess'] = null;
        $aryOutput['administrative_area_level_1'] = null;
        $aryOutput['sublocality_level_1'] = null;
        $aryOutput['locality'] = null;
        $aryOutput['ward'] = null;
        $aryOutput['colloquial_area'] = null;

        $google_api_key = Configure::read('geocode_key');
        $google_api_url = Configure::read('geocode_url');
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
                return null;
            } elseif($output->status == GEOCODE_OVER_QUERY_LIMIT) {
                usleep(200000);
            }
        }
        return $aryOutput;
    }
}