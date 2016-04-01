<?php
/**
 * config map of  list
 */
Configure::write(
    'map', array(
                'zoom'  => 13,
                'zoomJapan' => 4,
                'zoom_detail'  => 15,
                )
);
/**
 * config t_station_profile
 */
Configure::write(
    'visit_guidance', array(
        0 => '積極的に受け入れ可',
        1 => 'ある程度受け入れ可',
        2 => 'ご相談ください',
    )
);
/**
 * config station_function
 */
Configure::write(
    'station_function', array(
        0 => 'なし',
        1 => 'あり（機能強化型1、機能強化型2）',
    )
);
/**
 * config 24_hour_aday
 */
Configure::write(
    '24_hour_aday', array(
        0 => '24時間出動',
        1 => '24時間連絡',
        2 => '営業時間のみ',
    )
);
/**
 * config pal_care
 */
Configure::write(
    'pal_care', array(
        0 => 'ご対応可',
        1 => 'ご相談ください',
        2 => 'ご対応不可',
    )
);
/**
 * config mental_care
 */
Configure::write(
    'mental_care', array(
        0 => 'ご対応可',
        1 => 'ご相談ください',
        2 => 'ご対応不可',
    )
);
/**
 * config kids_care
 */
Configure::write(
    'kids_care', array(
        0 => 'ご対応可',
        1 => 'ご相談ください',
        2 => 'ご対応不可',
    )
);
/**
 * config kids_care
 */
Configure::write(
    'dementia_care', array(
        0 => 'ご対応可',
        1 => 'ご相談ください',
        2 => 'ご対応不可',
    )
);
/**
 * config handicapped_care
 */
Configure::write(
    'handicapped_care', array(
        0 => 'ご対応可',
        1 => 'ご相談ください',
        2 => 'ご対応不可',
    )
);
/**
 * config hospital_link
 */
Configure::write(
    'hospital_link', array(
        1 => '病院併設',
        2 => '診療所併設',
        3 => '病院、および施設併設',
        4 => '診療所、および施設併設',
        5 => '施設併設',
        0 => '単独開設',
    )
);