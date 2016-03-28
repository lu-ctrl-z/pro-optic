<?php

App::uses('Subnetmask', 'Service');

/**
 * LocalAccess
 * @author Luvina
 * @access public
 * @see checkLocalAccess()
 */
class LocalAccess {

    /**
     * Automatically detects the type of device.
     */
    public function checkLocalAccess($remoteAddress = array()) {
        // Get client Ip address
        $clientIp = env('REMOTE_ADDR');
        if (empty($remoteAddress)) {
            $remoteAddress = Configure::read('remote_address');
        }

        if (!is_array($remoteAddress) && $remoteAddress) {
            $remoteAddress = array($remoteAddress);
        }

        if (empty($remoteAddress)) return false;

        $objSubnet = new Subnetmask();

        try {
            foreach ($remoteAddress as $address) {
                if (strpos($address, "/") === false) {
                    if ($clientIp == $address) return true;
                } else {
                    $info = $objSubnet->getInfo($address);

                    $first_ip = ip2long($info["host_first"]);
                    $last_ip = ip2long($info["host_last"]);
                    $long_client_ip = ip2long($clientIp);

                    if ($long_client_ip >= $first_ip && $long_client_ip <= $last_ip) {
                        return true;
                    }
                }
            }
        } catch (exception $ex) {
            return false;
        }

        return false;
    }
}