<?php
/**
 * config key of google geocode api
 */
Configure::write(
    'geocode_key', array(
        'AIzaSyCzrs4116JVwRkB2Zu7BILCa9VurApRxqw', // LU-HYN-01 LDEV
        'AIzaSyD-8t2AFFDTWkSZMjjbGwjXx8pc4iV0UNo', // LU-HYN-02 LDEV
        'AIzaSyAXnpclvpw6wPffymQOKffEos12w4GKjWQ', // LU-HYN-03 LDEV
        //'AIzaSyA9oP9YgRiJ3FBAYQ_BmqPktidJ8-rumbU', // HYN-01 STG-PRD
        //'AIzaSyBzM0GyUuFCtmImYw2eG-Iq3U5NYC8wAyg', // HYN-02 STG-PRD
        //'AIzaSyAbMGcmoVR2laNp6Cx8ZlvhJaN3jT2ofP8', // HYN-03 STG-PRD
        //'AIzaSyAucUUfRs96j4XZAtjLVReESiefrLri-V0', // HYN-04 STG-PRD
        //'AIzaSyDZXJxK09AJPRkzx7-sFbhhUgHCH9V6kpw', // HYN-05 STG-PRD
    )
);
/**
 * config geocode url
 */
Configure::write(
    'geocode_url' , 'https://maps.googleapis.com/maps/api/geocode/json?'
);
/**
 * config key of staticmap
 */
Configure::write(
    'staticmap_key', array(
        'AIzaSyBfAdrcfVb7jybeJikCAMeUUCh1VFUyMu4',
    )
);
