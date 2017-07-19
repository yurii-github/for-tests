<?php

namespace Test;

class Helpers {
    static public function getRealIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }


    /**
     * returns refer URL if set, otherwise returns direct source link
     * @return string
     */
    static public function getRequestUrl() {
        if (empty($url = $_SERVER['HTTP_REFERER'])) {
            $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        }
        return $url;
    }


    static public function getIPVersion($ip) {
        if (\filter_var($ip, FILTER_VALIDATE_IP,FILTER_FLAG_IPV4) !== false) {
            return 4;
        }


        throw new \Exception('NOT IMPLEMENTED IPv6 SUPPORT');

        if (filter_var($ip, FILTER_VALIDATE_IP,FILTER_FLAG_IPV6) !== false) {
            return 6;
        }

        return false;
    }


    static public function getUserAgent() {
        return $_SERVER["HTTP_USER_AGENT"];
    }

}