<?php
class StationPic extends AppModel {
    public $useTable = 't_station_pic';
    public $isUploadFile = true;
    public $validate = array(
            'station_pic1'          => array(
                    'extension'           => array(
                            'rule' => array( 'extension2', array('gif', 'jpeg', 'png', 'jpg') ),
                            'message'   => '写真がJPEG、JPG、GIFまたはPNG形式ではありません。'
                    ),
                    'fileSize'      => array(
                            'rule' => array('fileSize', '<=', '2MB'),
                            'message' => '写真には2MB以下のファイルを指定してください。'
                    ),
                    'uploader'     => array(
                            'rule'   => array('uploaderImage', '_1'),
                            'message'   => '写真のアップロードに失敗しました。お手数をおかけいたしますが再度アップロードしてください'
                    )
            ),
            'station_pic2'          => array(
                    'extension'           => array(
                            'rule' => array( 'extension2', array('gif', 'jpeg', 'png', 'jpg') ),
                            'message'   => '写真がJPEG、JPG、GIFまたはPNG形式ではありません。'
                    ),
                    'fileSize'      => array(
                            'rule' => array('fileSize', '<=', '2MB'),
                            'message' => '写真には2MB以下のファイルを指定してください。'
                    ),
                    'uploader'     => array(
                            'rule'   => array('uploaderImage', '_2'),
                            'message'   => '写真のアップロードに失敗しました。お手数をおかけいたしますが再度アップロードしてください'
                    )
            ),
            'station_pic3'          => array(
                    'extension'           => array(
                            'rule' => array( 'extension2', array('gif', 'jpeg', 'png', 'jpg') ),
                            'message'   => '写真がJPEG、JPG、GIFまたはPNG形式ではありません。'
                    ),
                    'fileSize'      => array(
                            'rule' => array('fileSize', '<=', '2MB'),
                            'message' => '写真には2MB以下のファイルを指定してください。'
                    ),
                    'uploader'     => array(
                            'rule'   => array('uploaderImage', '_3'),
                            'message'   => '写真のアップロードに失敗しました。お手数をおかけいたしますが再度アップロードしてください'
                    )
            ),
    );
    /**
     * get list station picture
     * @param array $listCD
     * @return multitype:|Ambigous <multitype:, NULL>
     */
    public function getStationPic($listCD, $flg = true) {
        $return = array();
        $searchCD = array();
        foreach ($listCD as $k => $v) {
            if($v['ZONE']['kubun'] == 1) {
                $searchCD[] = $k;
            }
        }
        if(empty($searchCD)) return $return;

        $fields = array(
                'StationPic.id',
                'StationPic.station_CD',
                'StationPic.pic_id',
                'StationPic.pic_path',
        );
        $conditions['StationPic.delete_date'] = null;
        $conditions['StationPic.station_CD'] = $searchCD;
        if($flg == false) {
            $order = array( 'StationPic.pic_id ASC' );
            $group = array( 'StationPic.station_CD' );
            $ret = $this->find('all', array('conditions' => $conditions, 'fields' => $fields, 'order' => $order, 'group' => $group));
            foreach ($ret as $k => $v) {
                $return[$v['StationPic']['station_CD']][1] = $v;
            }
        } else {
            $ret = $this->find('all', array('conditions' => $conditions, 'fields' => $fields));
            foreach ($ret as $k => $v) {
                $return[$v['StationPic']['station_CD']][$v['StationPic']['pic_id']] = $v;
            }
        }

        return $return;
    }
}