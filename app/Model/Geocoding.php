<?php

/**
 * Geocoding
 * @author luvina
 * @access public
 *
 */
class Geocoding extends AppModel{
    public $useTable = 't_geocoding';
    /**
     * check exits address
     * @author Luvina
     * @access public
     * @param string $address
     */
    public function getAdderess ($addressMd5) {
        $aryFiles = array('address_md5',
                          'X(latlng) as lat',
                          'Y(latlng) as lng',
                          'address_api');
        $conditions = array('address_md5' => $addressMd5);
        $list = $this->find('first',array("fields" => $aryFiles, "conditions" => $conditions));
        $aryData = array();
        if (count($list) > 0) {
            $aryData = array_merge($list['Geocoding'],$list[0]);
        }

        return $aryData;
    }
    /**
     * add t_geocoding
     * @author Luvina
     * @access public
     * @param string $address
     * @param array $aryCookie
     * @param string $latitude
     * @param string $longitude
     * @param array $aryAddressServer
     */
    public function addGeocoding ($address, $aryDataGeocode) {

        $this->create();
        $db = ConnectionManager::getDataSource('default');
        $latlng = $db->expression("GeomFromText('POINT(". $aryDataGeocode['geometry_location_lat'] ." " . $aryDataGeocode['geometry_location_lng'] .")')");

        unset($aryDataGeocode['geometry_location_lat']);
        unset($aryDataGeocode['geometry_location_lng']);
        $addressApi = serialize($aryDataGeocode);

        $this->set('address_md5', $address);
        $this->set('latlng', $latlng);
        $this->set('address_api', $addressApi);
        $this->save();
    }
}