<?php
class Mobile_Util {
    // スマートフォンの定義
    function isSmartPhone($userAgent = null) {
        if (is_null($userAgent)) {
            $userAgent = @$_SERVER['HTTP_USER_AGENT'];
        }
        if(self::isIPhone($userAgent)){
            return true;
        }elseif(self::isIPod($userAgent)){
            return true;
        }elseif(self::isIPad($userAgent)){
            return false;       // iPadはPC版の方が見やすい
        }elseif(self::isAndroid($userAgent)){
            return true;
        }elseif(self::isWindowsMobile($userAgent)){
            return true;
        }elseif(self::isBlackBerry($userAgent)){
            return true;
        }elseif(self::isSymbian($userAgent)){
            return true;
        }elseif(self::isPalmWebOS($userAgent)){
            return true;
        }
        return false;
    }

    function isIPhone($userAgent = null) {
        if (is_null($userAgent)) {
            $userAgent = @$_SERVER['HTTP_USER_AGENT'];
        }
        return (bool)preg_match('/iPhone;/', $userAgent);
    }
    function isIPod($userAgent = null) {
        if (is_null($userAgent)) {
            $userAgent = @$_SERVER['HTTP_USER_AGENT'];
        }
        return (bool)preg_match('/iPod;/',$userAgent);
    }
    function isIPad($userAgent = null) {
        if (is_null($userAgent)) {
            $userAgent = @$_SERVER['HTTP_USER_AGENT'];
        }
        return (bool)preg_match('/iPad;/',$userAgent);
    }
    function isAndroid($userAgent = null) {
        if (is_null($userAgent)) {
            $userAgent = @$_SERVER['HTTP_USER_AGENT'];
        }
        return (bool)preg_match('/Android/', $userAgent);
    }

    // 以下は対応する必要がある場合に適宜定義してください
    function isWindowsMobile($userAgent = null){
        if (is_null($userAgent)) {
            $userAgent = @$_SERVER['HTTP_USER_AGENT'];
        }
        return (bool)preg_match('/Windows Phone/', $userAgent);
    }
    function isBlackBerry($userAgent = null){return false;}
    function isSymbian($userAgent = null){return false;}
    function isPalmWebOS($userAgent = null){return false;}
}
