<?php
/**
 * Util
 * @author Luvina
 * @access public
 * @see isAllowedHost()
 * @see trimData()
 * @see getRandom()
 * @see configPaginate()
 */
class Util {

    public function isAllowedHost($url) {
        $isAllowed = false;
        $aryAllowHost = Configure::read('allowed_host');
        foreach ($aryAllowHost as $host) {
            if( preg_match('/^(https?:)\/\/(www\.)?(' . $host . ')([\/\?&#]{1}|$)/i', $url) ) {
                $isAllowed = true;
            }
        }

        return $isAllowed;
    }
    /**
     * Trim column
     * @author Luvina
     * @access public
     * @param array $aryData
     * @param array $aryColumn
     */
    public function trimData ($aryData, $aryColumn) {
        foreach ($aryColumn as $value) {
            if (isset($aryData[$value])) {
                $aryData[$value] = trim($aryData[$value]);
            }
        }
        return $aryData;
    }
    /**
     * Get random
     * @author Luvina
     * @access public
     * @param int $length
     */
    function getRandom($length = 64) {

        static $srand = false;

        if ($srand == false) {
            list($usec, $sec) = explode(' ', microtime());
            mt_srand((float) $sec + ((float) $usec * 100000) + getmypid());
            $srand = true;
        }

        $value = "";
        for ($i = 0; $i < 2; $i++) {
            // for Linux
            if (file_exists('/proc/net/dev')) {
                $rx = $tx = 0;
                $fp = fopen('/proc/net/dev', 'r');
                if ($fp != null) {
                    $header = true;
                    while (feof($fp) === false) {
                        $s = fgets($fp, 4096);
                        if ($header) {
                            $header = false;
                            continue;
                        }
                        $v = preg_split('/[:\s]+/', $s);
                        if (is_array($v) && count($v) > 10) {
                            $rx += $v[2];
                            $tx += $v[10];
                        }
                    }
                }
                $platform_value = $rx . $tx . mt_rand() . getmypid();
            } else {
                $platform_value = mt_rand() . getmypid();
            }
            $now = strftime('%Y%m%d %T');
            $time = gettimeofday();
            $v = $now . $time['usec'] . $platform_value . mt_rand(0, time());
            $value .= md5($v);
        }

        if ($length < 64) {
            $value = substr($value, 0, $length);
        }
        return $value;
    }
    /**
     * configPaginate
     * @author Luvina
     * @access public
     * @param string $table
     * @param int $count
     * @return
     */
    public function configPaginate($table, $count, $curPage, $limitPerPage = 10, $modulus) {

        $this->paginate = array(
                "$table" => array(
                        'limit' => $limitPerPage,
                        'page' => $curPage,
                        'modulus' => $modulus
                )
        );

        $this->$table->recursive = 0;
        $this->Paginator->count = $count;
        $this->paginate($table);
    }
    /**
     * getCurrentPage
     * @author Luvina
     * @access public
     * @param int $pageCount
     * @return
     */
    public function getCurrentPage($pageCount) {
        if (!is_numeric($this->page) || $this->page <= 0) {
            $this->page = 1;
        } else if ($this->page > $pageCount) {
            $this->page = $pageCount;
        }
    }
}