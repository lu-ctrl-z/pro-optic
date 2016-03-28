<?php
include_once  APP . 'Configapp' . DS . 'Geocode.ini.php';
define('GEOCODE_OVER_QUERY_LIMIT', 'OVER_QUERY_LIMIT');
define('GEOCODE_ZERO_RESULTS',     'ZERO_RESULTS');
define('GEOCODE_OK',               'OK');
/**
 * GeocodeUtil
 * @author Luvina
 * @access public
 */
class GeocodeUtil {
    /**
     * get latitue, lngtitue, full_address from address
     * @param string $address
     * @return NULL|FALSE|ARRAY
     */
    function getLatLngFromAddress($address) {
        if( !$address ) return null;
        $address = urlencode($address);
        $google_api_key = Configure::read('geocode_key');
        $google_api_url = Configure::read('geocode_url');
        $max_rand = count($google_api_key);
        $random_keys = (array) array_rand($google_api_key , $max_rand);
        foreach($random_keys as $i) {
            $use_google_api_key = $google_api_key[$i];
            $get_google_api_url = $google_api_url . 'key=' . $use_google_api_key . '&sensor=false&region=jp&address=' . $address . '&language=ja';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $get_google_api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $geocode = curl_exec($ch);
            $output = json_decode($geocode);
            curl_close($ch);

            if( $output->status == GEOCODE_OK) {
                $return = array();
                $result = $output->results[0];
                $pref = $city = $ward = '';
                foreach ($result->address_components as $type) {
                    if($type->types[0] == 'administrative_area_level_1') {
                        $pref = $type->long_name;
                    } else if ($type->types[0] == 'locality') {
                        $city = $type->long_name;
                    } else if ($type->types[0] == 'ward') {
                        $ward = $type->long_name;
                    }
                }
                $return['full_addess'] = $pref . $city . $ward;
                $return['lat'] = $result->geometry->location->lat;
                $return['lng'] = $result->geometry->location->lng;
                return $return;
            } elseif($output->status == GEOCODE_ZERO_RESULTS ) {
                return false;
            } elseif($output->status == GEOCODE_OVER_QUERY_LIMIT) {
                sleep(0.2);
            }
        }
        return null;
    }
    /**
     * @param float $lat
     * @param float $lng
     * @return multitype:string NULL |boolean|NULL
     */
    function getFulladdressFromLatLng($lat, $lng) {
        $google_api_key = Configure::read('geocode_key');
        $google_api_url = Configure::read('geocode_url');
        $max_rand = count($google_api_key);
        $random_keys = (array) array_rand($google_api_key , $max_rand);
        foreach($random_keys as $i) {
            $use_google_api_key = $google_api_key[$i];
            $get_google_api_url = $google_api_url . 'key=' . $use_google_api_key . '&sensor=false&latlng=' . $lat . ',' . $lng . '&language=ja';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $get_google_api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $geocode = curl_exec($ch);
            $output = json_decode($geocode);
            curl_close($ch);

            if( $output->status == GEOCODE_OK) {
                $return = array();
                $result = $output->results[0];
                $pref = $city = $ward = '';
                foreach ($result->address_components as $type) {
                    if($type->types[0] == 'administrative_area_level_1') {
                        $pref = $type->long_name;
                    } else if ($type->types[0] == 'locality') {
                        $city = $type->long_name;
                    } else if ($type->types[0] == 'ward') {
                        $ward = $type->long_name;
                    }
                }
                $return['full_addess'] = $pref . $city . $ward;
                $return['pref'] = $pref;
                $return['lat'] = $result->geometry->location->lat;
                $return['lng'] = $result->geometry->location->lng;
                return $return;
            } elseif($output->status == GEOCODE_ZERO_RESULTS ) {
                return false;
            } elseif($output->status == GEOCODE_OVER_QUERY_LIMIT) {
                sleep(0.2);
            }
        }
        return null;
    }
}