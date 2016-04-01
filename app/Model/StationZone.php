<?php
class StationZone extends AppModel {
    public $useTable = 't_station_zone';
    public $validate = array(
        'scope'         => array(
            'required'      => array(
                'rule'      => 'notEmpty',
                'message'   => '訪問実施範囲を入力して下さい。'
            ),
            'checkScope' => array(
                'rule'      => 'inScope',
                'message'   => '訪問実施範囲は存在しません。'
            ),
        ),
    );
    /**
     * check in m_scope
     * @param array $val
     * @return boolean
     */
    function inScope($val) {
        $scope_id = array_values($val);
        $scope = new Scope();
        $com = $scope->find('first', array(
                'conditions' => array('id' => $scope_id)));
        if(!$com) return false;
        return true;
    }

    /**
     * search station
     *
     */
    public function searchStationCD($latitude, $longitude, $limit = 10, $offset = 0) {
        $params = array($latitude, $longitude, $latitude,
                        $latitude, $longitude, $latitude,
                        $latitude, $longitude, $latitude);
        $DB = $this->getDataSource();
        $totalCount = 0;
        $sql_profile = "SELECT
                ZONE.station_CD, ZONE.latitude, ZONE.longitude, ZONE.scope,
                (
                    6371 * ACOS(
                        COS(RADIANS(?))* COS(RADIANS(ZONE.latitude))* COS(
                            RADIANS(ZONE.longitude)- RADIANS(?)
                        )+ SIN(RADIANS(?))* SIN(RADIANS(ZONE.latitude))
                    )
                )AS distance,
                ZONE.kubun,
                PROFILE.*
            FROM
                t_station_profile AS PROFILE
            INNER JOIN t_station_zone AS ZONE ON PROFILE.station_CD = ZONE.station_CD
            INNER JOIN m_scope AS SCOPE ON SCOPE.id = ZONE.scope
            WHERE
            PROFILE.delete_date IS NULL
            AND ZONE.delete_date IS NULL
            AND ZONE.kubun = 1
            AND ((
                    6371 * ACOS(
                        COS(RADIANS(?))* COS(RADIANS(ZONE.latitude))* COS(
                            RADIANS(ZONE.longitude)- RADIANS(?)
                        )+ SIN(RADIANS(?))* SIN(RADIANS(ZONE.latitude))
                    )
                ) IS NULL
                OR (
                    6371 * ACOS(
                        COS(RADIANS(?))* COS(RADIANS(ZONE.latitude))* COS(
                            RADIANS(ZONE.longitude)- RADIANS(?)
                        )+ SIN(RADIANS(?))* SIN(RADIANS(ZONE.latitude))
                    )
                )<= SCOPE.distance)
            GROUP BY ZONE.station_CD
            ORDER BY type DESC, distance ASC ";

        $retProfile = $DB->fetchAll($sql_profile, $params);
        $totalCount += count($retProfile);

        $mapList = $list = array();
        $off = -1;
        foreach ($retProfile as $k => $v) {
            $mapList[$v['ZONE']['station_CD']]['station_CD'] = $v['ZONE']['station_CD'];
            $mapList[$v['ZONE']['station_CD']]['latitude'] = $v['ZONE']['latitude'];
            $mapList[$v['ZONE']['station_CD']]['longitude'] = $v['ZONE']['longitude'];
            $mapList[$v['ZONE']['station_CD']]['station_name'] = $v['PROFILE']['station_name'];
            $off++;
            if($offset <= $off && $off < ($offset + $limit) ) {
                $list[$v['ZONE']['station_CD']] = $v;
            } else {
                continue;
            }
        }
        unset($retProfile);

        $sql_public = "SELECT ZONE.station_CD,
                        ZONE.latitude, ZONE.longitude, ZONE.scope,
                    (
                        6371 * ACOS(
                            COS(RADIANS(?))* COS(RADIANS(ZONE.latitude))* COS(
                                RADIANS(ZONE.longitude)- RADIANS(?)
                            )+ SIN(RADIANS(?))* SIN(RADIANS(ZONE.latitude))
                        )
                    )AS distance,
                    ZONE.kubun,
                    PROFILE.*
                FROM
                    t_station_public AS PROFILE
                INNER JOIN t_station_zone AS ZONE ON PROFILE.station_CD = ZONE.station_CD
                INNER JOIN m_scope AS SCOPE ON SCOPE.id = ZONE.scope
                WHERE
                    PROFILE.delete_date IS NULL
                AND ZONE.delete_date IS NULL
                AND ZONE.kubun = 0
                AND ((
                        6371 * ACOS(
                            COS(RADIANS(?))* COS(RADIANS(ZONE.latitude))* COS(
                                RADIANS(ZONE.longitude)- RADIANS(?)
                            )+ SIN(RADIANS(?))* SIN(RADIANS(ZONE.latitude))
                        )
                    ) IS NULL
                    OR (
                        6371 * ACOS(
                            COS(RADIANS(?))* COS(RADIANS(ZONE.latitude))* COS(
                                RADIANS(ZONE.longitude)- RADIANS(?)
                            )+ SIN(RADIANS(?))* SIN(RADIANS(ZONE.latitude))
                        )
                    )<= SCOPE.distance)
            GROUP BY ZONE.station_CD
            ORDER BY distance ASC ";

        $limit_public = $limit - count($list);
        $offset_public = ($offset >= $totalCount)? ($offset - $totalCount) : 0;
        $retPublic = $DB->fetchAll($sql_public, $params);
        $totalCount += count($retPublic);
        if($totalCount <= 0) {
            return array(0, array(), array());
        }

        $off = -1;
        foreach ($retPublic as $k => $v) {
            $mapList[$v['ZONE']['station_CD']]['station_CD'] = $v['ZONE']['station_CD'];
            $mapList[$v['ZONE']['station_CD']]['latitude'] = $v['ZONE']['latitude'];
            $mapList[$v['ZONE']['station_CD']]['longitude'] = $v['ZONE']['longitude'];
            $mapList[$v['ZONE']['station_CD']]['station_name'] = $v['PROFILE']['station_name'];
            $off++;
            if($offset_public <= $off && $off < ($offset_public + $limit_public) ) {
                $list[$v['ZONE']['station_CD']] = $v;
            } else {
                continue;
            }
        }
        unset($retPublic);
        return array($totalCount, $list, $mapList);
    }
}