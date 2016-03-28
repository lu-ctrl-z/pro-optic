<?php
define('LATITUDE_JAPAN' , 35.673343);
define('LONGITUDE_JAPAN', 139.710388);
App::uses('SiteController', 'Controller/Base');
App::uses('CakeEmail', 'Network/Email');
/**
 * StationController
 * @author Luvina
 * @access public
 * @see index()
 */
class StationController extends SiteController {
    public $uses = array('DrugStore', 'Geocoding',);

    /**
     * index
     * @author Luvina
     * @access public
     */
    public function index() {
        $address = '';
        $address = $this->get('address');
        if (!empty($address)) {
            Security::setHash('md5');
            $addressMd5 = Security::hash($address);
            $aryDataGeocode = array();
            $saveDB = 1;
            if (isset($this->request->query['ref'])) {
                $saveDB = 0;
            }
            $aryCookie = $_COOKIE;
            if(isset($aryCookie['geometry_location_lat']) && isset($aryCookie['geometry_location_lng']) ) {
                $aryDataGeocode['geometry_location_lat'] = $aryCookie['geometry_location_lat'];
                $aryDataGeocode['geometry_location_lng'] = $aryCookie['geometry_location_lng'];
                $aryDataGeocode['administrative_area_level_1'] = $aryCookie['administrative_area_level_1'];
                $aryDataGeocode['sublocality_level_1'] = $aryCookie['sublocality_level_1'];
                $aryDataGeocode['locality'] = $aryCookie['locality'];
                $aryDataGeocode['ward'] = $aryCookie['ward'];
                $aryDataGeocode['colloquial_area'] = $aryCookie['colloquial_area'];
                $saveDB = 0;
            } else {
                $addressGeocoding = $this->Geocoding->getAdderess($addressMd5);
                if(count($addressGeocoding) > 0) {
                    $aryDataGeocode['geometry_location_lat'] = $addressGeocoding['lat'];
                    $aryDataGeocode['geometry_location_lng'] = $addressGeocoding['lng'];
                    $geoInfo = unserialize($addressGeocoding['address_api']);
                    $aryDataGeocode['administrative_area_level_1'] = $geoInfo['administrative_area_level_1'];
                    $aryDataGeocode['sublocality_level_1'] = $geoInfo['sublocality_level_1'];
                    $aryDataGeocode['locality'] = $geoInfo['locality'];
                    $aryDataGeocode['ward'] = $geoInfo['ward'];
                    $aryDataGeocode['colloquial_area'] = $geoInfo['colloquial_area'];
                    $saveDB = 0;
                } else {
                    $arrLatLng = $this->DrugStore->getLatAndLngByAddress($address);
                    $aryDataGeocode['geometry_location_lat'] = $arrLatLng['lat'];
                    $aryDataGeocode['geometry_location_lng'] = $arrLatLng['lng'];

                    $output = $this->DrugStore->getFulladdressByLatLng($arrLatLng['lat'], $arrLatLng['lng']);
                    $aryDataGeocode['administrative_area_level_1'] = $output['administrative_area_level_1'];
                    $aryDataGeocode['sublocality_level_1'] = $output['sublocality_level_1'];
                    $aryDataGeocode['locality'] = $output['locality'];
                    $aryDataGeocode['ward'] = $output['ward'];
                    $aryDataGeocode['colloquial_area'] = $output['colloquial_area'];
                }
            }
            // check latitude and longitude to get all drugstories
            $latitude = $aryDataGeocode['geometry_location_lat'];
            $longitude = $aryDataGeocode['geometry_location_lng'];
        } else {
            $this->set('content_noindex', 1);
            $this->set('errors', '住所が入力されていません');
            $address_meta = '';
        }

        $this->hkRender('list', 'site_default');
    }
}