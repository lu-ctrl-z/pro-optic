/**
 * escapeRegExp
 * @author luvina
 * @acess public
 * @param string string
 * @returns string
 */
    function escapeRegExp(string) {
        return string.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "");
    }

var dtdGeocode ;
var dtdAddress ;
$(document).ready(function() {
    dtdGeocode = $.Deferred();
    dtdAddress = $.Deferred();
});
/*
 * mod 2015/04/30 uchiyama
 */

/*
 * Only when the smartphone : telephone No. link
 * uchiyama
 */
$(document).ready(function() {
    var ua = navigator.userAgent;
    if (ua.indexOf('iPhone') > 0 && ua.indexOf('iPod') == -1 || ua.indexOf('Android') > 0 && ua.indexOf('Mobile') > 0) {
        $(".tell").each(function(i) {
            tell = $(this).text();
            $(this).after('<a id="tell_a_' + i + '"></a>');
            $(this).appendTo("#tell_a_" + i);
            $("#tell_a_" + i).attr("href", 'tel:' + tell);
        });
    }
    $("a[href^='tel']").click(function() {
        ga('send', 'event', 'smartphone', 'phone-number-tap', 'main');
    });
});
/*
 * geocode
 * uchiyama
 */
$(document).ready(function() {
    $('.geo_btn').click(function() {
        getLocation();
    });
    // Modify search form click
    $(".hk-search-form").click(function() {
        var $form = $(this).parents('form');
        var address = $form.find("input[name='address']").val();
        if (address != "") {
            $.when(address2latlong(dtdGeocode,address)).done(function() {
                lat = $.cookie("geometry_location_lat");
                lng = $.cookie("geometry_location_lng");
                $.when(latlongaddress(dtdAddress,lat, lng)).done(function() {
                    $form.submit();
                }).fail(function() {
                    return true;
                });
            }).fail(function() {
            	alert(12);
                return true;
            });
            return false;
        }
        return true;
    });

    $("body").on("click", "[href^='/list/']", function() {
        link = $(this).attr('href');
        frmSearch = $('#search_area');
        address = link.replace(/^\/|\/$/g, '');
        address = address.split("/")[1];
        address = decodeURIComponent(address);
        frmSearch.attr('action', link);
        $.when(address2latlong(dtdGeocode,address)).done(function() {
            lat = $.cookie("geometry_location_lat");
            lng = $.cookie("geometry_location_lng");
            $.when(latlongaddress(dtdAddress,lat, lng)).done(function() {
                frmSearch.submit();
            }).fail(function() {
                return true;
            });
        }).fail(function() {
            return true;
        });
        return false;
    });
    /*
     * search link
     */
    function loc_href_list(address) {
        address = escapeRegExp(address);
        var base_url = "/list/";
        formURL = base_url + address + '?ref=box';
        location.href = formURL;
        return false;
    }

    /*
     * getLocation
     */
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(getLatLong, geo_error);
        } else {
            console.log('Geolocation is not supported by this browser.');
        }
    }

    function getLatLong(position) {
        Latitude = position.coords.latitude;
        Longitude = position.coords.longitude;
        address = latlong2address(Latitude, Longitude);
    }

    function geo_error(err) {
        console.log('ERROR(' + err.code + '): ' + err.message);
    };

    function latlong2address(Latitude, Longitude) {
        sublocality_level_1 = "";
        locality = "";
        administrative_area_level_1 = "";
        ward = "";
        colloquial_area = "";
        uri = "http://maps.googleapis.com/maps/api/geocode/json?latlng=" + Latitude + "," + Longitude + "&sensor=true&language=ja ";
        jQuery.getJSON(uri, function(json) {
            i_max = json.results[0].address_components.length;
            for ( i = 0; i < i_max; i++) {
                long_name = json.results[0].address_components[i].long_name;
                short_name = json.results[0].address_components[i].short_name;
                type = json.results[0].address_components[i].types[0];
                switch (type) {
                case "sublocality_level_1" :
                    sublocality_level_1 = long_name;
                    break;
                case "locality" :
                    locality = long_name;
                    break;
                case "administrative_area_level_1" :
                    administrative_area_level_1 = long_name;
                    break;
                case "ward" :
                    ward = long_name;
                    break;
                case "colloquial_area" :
                    colloquial_area = long_name;
                    break;
                }
            }
            $.cookie('sublocality_level_1', sublocality_level_1, {
                path : "/"
            });
            $.cookie('locality', locality, {
                path : "/"
            });
            $.cookie('administrative_area_level_1', administrative_area_level_1, {
                path : "/"
            });
            $.cookie('ward', ward, {
                path : "/"
            });
            $.cookie('colloquial_area', colloquial_area, {
                path : "/"
            });
            //geometry
            Latitude = json.results[0].geometry.location.lat;
            Longitude = json.results[0].geometry.location.lng;
            $.cookie('geometry_location_lat', Latitude, {
                path : "/"
            });
            $.cookie('geometry_location_lng', Longitude, {
                path : "/"
            });

            address = administrative_area_level_1 + colloquial_area + locality + ward + sublocality_level_1;
            loc_href_list(address);
            return address;
        });
    }

    function latlongaddress(dtdAddress, lat, lng) {

        var tasks = function() {
            var latlng = new google.maps.LatLng(lat, lng);
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({
                'latLng' : latlng,
                'language' : 'ja'
            }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0] && results[0].address_components) {
                        i_max = results[0].address_components.length;
                        sublocality_level_1 = "";
                        locality = "";
                        administrative_area_level_1 = "";
                        ward = "";
                        colloquial_area = "";

                        for ( i = 0; i < i_max; i++) {
                            long_name = results[0].address_components[i].long_name;
                            short_name = results[0].address_components[i].short_name;
                            type = results[0].address_components[i].types[0];
                            switch (type) {
                            case "sublocality_level_1" :
                                sublocality_level_1 = long_name;
                                break;
                            case "locality" :
                                locality = long_name;
                                break;
                            case "administrative_area_level_1" :
                                administrative_area_level_1 = long_name;
                                break;
                            case "ward" :
                                ward = long_name;
                                break;
                            case "colloquial_area" :
                                colloquial_area = long_name;
                                break;
                            }
                        }
                        $.cookie('sublocality_level_1', sublocality_level_1, {
                            path : "/"
                        });
                        $.cookie('locality', locality, {
                            path : "/"
                        });
                        $.cookie('administrative_area_level_1', administrative_area_level_1, {
                            path : "/"
                        });
                        $.cookie('ward', ward, {
                            path : "/"
                        });
                        $.cookie('colloquial_area', colloquial_area, {
                            path : "/"
                        });
                    }
                }
                dtdAddress.resolve();
            });
        };
        tasks();
        return dtdAddress;
    };

    function address2latlong(dtdGeocode,address) {
        var tasks = function() {
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({
                'address' : address
            }, function(results, status) {
            	console.log(results);
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0] && results[0].address_components) {
                        Latitude = results[0].geometry.location.lat();
                        Longitude = results[0].geometry.location.lng();
                        $.cookie('geometry_location_lat', Latitude, {
                            path : "/"
                        });
                        $.cookie('geometry_location_lng', Longitude, {
                            path : "/"
                        });
                    }
                }
                dtdGeocode.resolve();
            });
        };
        tasks();
        return dtdGeocode;
    };
    function removeCookieAddressApi() {
        $.removeCookie('sublocality_level_1', { path: '/' });
        $.removeCookie('locality', { path: '/' });
        $.removeCookie('administrative_area_level_1', { path: '/' });
        $.removeCookie('ward', { path: '/' });
        $.removeCookie('colloquial_area', { path: '/' });
        $.removeCookie('geometry_location_lat', { path: '/' });
        $.removeCookie('geometry_location_lng', { path: '/' });
    }
});

/*!
 * jQuery Cookie Plugin v1.4.1
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2006, 2014 Klaus Hartl
 * Released under the MIT license
 */
( function(factory) {
        if ( typeof define === 'function' && define.amd) {
            // AMD (Register as an anonymous module)
            define(['jquery'], factory);
        } else if ( typeof exports === 'object') {
            // Node/CommonJS
            module.exports = factory(require('jquery'));
        } else {
            // Browser globals
            factory(jQuery);
        }
    }(function($) {

        var pluses = /\+/g;

        function encode(s) {
            return config.raw ? s : encodeURIComponent(s);
        }

        function decode(s) {
            return config.raw ? s : decodeURIComponent(s);
        }

        function stringifyCookieValue(value) {
            return encode(config.json ? JSON.stringify(value) : String(value));
        }

        function parseCookieValue(s) {
            if (s.indexOf('"') === 0) {
                // This is a quoted cookie as according to RFC2068, unescape...
                s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
            }

            try {
                // Replace server-side written pluses with spaces.
                // If we can't decode the cookie, ignore it, it's unusable.
                // If we can't parse the cookie, ignore it, it's unusable.
                s = decodeURIComponent(s.replace(pluses, ' '));
                return config.json ? JSON.parse(s) : s;
            } catch(e) {
            }
        }

        function read(s, converter) {
            var value = config.raw ? s : parseCookieValue(s);
            return $.isFunction(converter) ? converter(value) : value;
        }

        var config = $.cookie = function(key, value, options) {

            // Write

            if (arguments.length > 1 && !$.isFunction(value)) {
                options = $.extend({}, config.defaults, options);

                if ( typeof options.expires === 'number') {
                    var days = options.expires,
                        t = options.expires = new Date();
                    t.setMilliseconds(t.getMilliseconds() + days * 864e+5);
                }

                return (document.cookie = [encode(key), '=', stringifyCookieValue(value), options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
                options.path ? '; path=' + options.path : '', options.domain ? '; domain=' + options.domain : '', options.secure ? '; secure' : ''].join(''));
            }

            // Read

            var result = key ? undefined : {},
            // To prevent the for loop in the first place assign an empty array
            // in case there are no cookies at all. Also prevents odd result when
            // calling $.cookie().
                cookies = document.cookie ? document.cookie.split('; ') : [],
                i = 0,
                l = cookies.length;

            for (; i < l; i++) {
                var parts = cookies[i].split('='),
                    name = decode(parts.shift()),
                    cookie = parts.join('=');

                if (key === name) {
                    // If second argument (value) is a function it's a converter...
                    result = read(cookie, value);
                    break;
                }

                // Prevent storing a cookie that we couldn't decode.
                if (!key && ( cookie = read(cookie)) !== undefined) {
                    result[name] = cookie;
                }
            }

            return result;
        };

        config.defaults = {};

        $.removeCookie = function(key, options) {
            // Must not alter options, thus extending a fresh object...
            $.cookie(key, '', $.extend({}, options, {
                expires : -1
            }));
            return !$.cookie(key);
        };

    })
);
