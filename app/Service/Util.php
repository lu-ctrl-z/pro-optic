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
    public function setPager($table, $total, $url_params = array(), $limit = 10, $modulus = 9,
        $textPaginate = '%s～%s件を表示 / 全%s件') {

        $pageCount = (int)ceil($total / $limit);
        self::getCurrentPage($pageCount);

        $this->set('url_params', $url_params);
        $this->set('modulus', $modulus);
        $offset = ($this->page - 1) * $limit;
        $this->set('indexFrom', $offset);
        $indexTo = $offset + $limit;
        $indexTo = ($indexTo > $total) ? $total : $indexTo;
        $this->set('indexTo', $indexTo);
        $textPaginate = sprintf($textPaginate, $offset + 1, $indexTo, $total);
        $this->set('textPaginate', $textPaginate);

        $this->paginate = array(
                "$table" => array(
                        'limit' => $limit,
                        'page' => $this->page,
                        'modulus' => $modulus
                )
        );

        $this->$table->recursive = 0;
        $this->Paginator->count = $total;
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
        return $this->page;
    }
}