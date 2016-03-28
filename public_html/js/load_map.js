
/**
 * map object
 */
var map;

/**
 * Key show in icon google map
 */
var key = 0;
// #94 Start Luvina Modify
var aryInfowindown = new Array();
// #94 End Luvina Modify
/**
 * loadCenterMap
 * @author luvina
 * @acess public
 * @param string map_area_display
 * @param int latitude
 * @param int longitude
 * @param int zoom_map
 * @parram string address
 * @param bool isDetail
 * @returns
 */
function loadCenterMap(map_area_display, latitude, longitude, zoom_map, address, isDetail) {
    var latlng = new google.maps.LatLng(latitude, longitude);
    var mapOptions = {
        zoom: parseInt(zoom_map),
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map($("body").find('div#' + map_area_display)[0], mapOptions);

    if(address){
        if(isDetail){
            var icon = '/img/pc/icon_map_red.png';
        } else{
            var icon = '/img/pc/icon_mappoint.png';
        }
        var marker = new google.maps.Marker({
            position: latlng,
            map: map,
            icon: icon,
            title: address
        });
    }
}

/**
 * loadAddressToMap
 * @author luvina
 * @acess public
 * @param int latitude
 * @param int longitude
 * @param string drugstore_name
 * @param int drugstore_CD
 * @param bool isHyn
 * @returns
 */
function loadAddressToMap(latitude, longitude, drugstore_name, drugstore_CD, isHyn) {
    key++;

    var image = 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=' + key + '|f7584c|FFFFFF';
    var latlng = new google.maps.LatLng(latitude, longitude);
    var marker = new google.maps.Marker({
        position: latlng,
        map: map,
        icon: image,
        title: drugstore_name
    });

    // link url to detail
    if (isHyn) {
        var contentString = '<div style="min-height: 30px"><a href="/detail/' + drugstore_CD + '">' + drugstore_name + '</a></div>';
        var infowindow = new google.maps.InfoWindow({
            content: contentString
        });
        // #94 Start Luvina Modify
        aryInfowindown.push(infowindow);
        google.maps.event.addListener(marker, 'click', function() {
            if (aryInfowindown) {
                for(var i=0; i< aryInfowindown.length; i++) {
                    aryInfowindown[i].close();
                }
            }
        // #94 End Luvina Modify
            infowindow.open(map,marker);
        });
    }
}