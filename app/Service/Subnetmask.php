<?php

class Subnetmask {
    private $input;

    private function setError($message, $debuginfo)
    {
        if (Configure::read('debug') > 0) {
            throw new Exception($message.' ('.$debuginfo.')');
        }
        else {
            throw new Exception($message);
        }

    }

    /**
     * Calculates subnet information
     *
     * @param string $input
     * @return array [ip, network, netmask, netmask_bits, netclass, host_first, host_last, host_count, broadcast]
     */
    public function getInfo($input)
    {
        $this->input = rtrim($input);

        if (! ereg('^([0-9]{1,3}\.){3}[0-9]{1,3}(( ([0-9]{1,3}\.){3}[0-9]{1,3})|(/[0-9]{1,2}))$', $this->input)){
            $this->setError("Invalid input format", $this->input);
        }

        if (ereg("/", $this->input)){  //if cidr type mask
            $dq_host = strtok($this->input, "/");
            $cdr_nmask = strtok("/");
            if (!($cdr_nmask >= 0 && $cdr_nmask <= 32)){
                $this->setError("Invalid CIDR value. Try an integer 0 - 32.", $this->input);
            }
            $bin_nmask = $this->cdrtobin($cdr_nmask);
            $bin_wmask = $this->binnmtowm($bin_nmask);
        } else { //Dotted quad mask?
            $dqs=explode(" ", $this->input);
            $dq_host=$dqs[0];
            $bin_nmask=$this->dqtobin($dqs[1]);
            $bin_wmask=$this->binnmtowm($bin_nmask);
            if (ereg("0", rtrim($bin_nmask, "0"))) {  //Wildcard mask then? hmm?
                $bin_wmask=$this->dqtobin($dqs[1]);
                $bin_nmask=$this->binwmtonm($bin_wmask);
                if (ereg("0", rtrim($bin_nmask, "0"))){ //If it's not wcard, whussup?
                    $this->setError("Invalid netmask", $this->input);
                }
            }
            $cdr_nmask=$this->bintocdr($bin_nmask);
        }

        //Check for valid $dq_host
        if (! ereg('^0.', $dq_host)){
            foreach( explode(".", $dq_host) as $octet ){
                if ($octet > 255){
                    $this->setError("Invalid ip address", $this->input);
                }

            }
        }

        $bin_host=$this->dqtobin($dq_host);
        $bin_bcast=(str_pad(substr($bin_host, 0, $cdr_nmask), 32, 1));
        $bin_net=(str_pad(substr($bin_host, 0, $cdr_nmask), 32, 0));
        $bin_first=(str_pad(substr($bin_net, 0, 31), 32, 1));
        $bin_last=(str_pad(substr($bin_bcast, 0, 31), 32, 0));
        $host_total=(bindec(str_pad("", (32-$cdr_nmask), 1)) - 1);

        //      if ($host_total <= 0){  //Takes care of 31 and 32 bit masks.
        //          $bin_first="N/A" ; $bin_last="N/A" ; $host_total="N/A";
        //          if ($bin_net === $bin_bcast) $bin_bcast="N/A";
        //      }

        // Dont use N/A
        if ($host_total <= -1){  //Takes care of 31 and 32 bit masks.
            $bin_first=$bin_host ; $bin_last=$bin_host ; $host_total=-1;
            $bin_bcast=$bin_host;
        }
        else if ($host_total == 0){  //Takes care of 31 and 32 bit masks.
            $bin_first=$bin_host ; $bin_last=$bin_host ; $host_total=0;
            $bin_bcast=$bin_host;
        }



        //Determine Class
        if (ereg('^0', $bin_net)){
            $class="A";
        }elseif (ereg('^10', $bin_net)){
            $class="B";
        }elseif (ereg('^110', $bin_net)){
            $class="C";
        }elseif (ereg('^1110', $bin_net)){
            $class="D";
        }else{
            $class="E";
        }

        $info = array();
        $info['ip'] = $dq_host;
        $info['network'] = $this->bintodq($bin_net);
        $info['netmask'] = $this->bintodq($bin_nmask);
        $info['netmask_bits'] = $cdr_nmask;
        $info['netclass'] = $class;
        $info['host_first'] = $this->bintodq($bin_first);
        $info['host_last'] = $this->bintodq($bin_last);
        $info['host_count'] = $host_total;
        $info['broadcast'] = $this->bintodq($bin_bcast);

        return $info;
    }

    public function getNetwork($input)
    {
        $info = $this->getInfo($input);

        return $info['network'];
    }

    public function getBroadcast($input)
    {
        $info = $this->getInfo($input);

        return $info['broadcast'];
    }

    public function getSize($input) {
        $info = $this->getInfo($input);

        return $info['host_count']+2;
    }

    public function getHostcount($input)
    {
        $info = $this->getInfo($input);

        return $info['host_count'];
    }

    // Helper functions -------------------------------------------------------
    private function binnmtowm($binin)
    {
        $binin=rtrim($binin, "0");
        if (!ereg("0", $binin) ){
            return str_pad(str_replace("1", "0", $binin), 32, "1");
        } else return "1010101010101010101010101010101010101010";
    }

    private function bintocdr($binin)
    {
        return strlen(rtrim($binin, "0"));
    }

    private function bintodq($binin)
    {
        if ($binin=="N/A") return $binin;
        $binin=explode(".", chunk_split($binin, 8, "."));
        for ($i=0; $i<4 ; $i++) {
            $dq[$i]=bindec($binin[$i]);
        }
        return implode(".", $dq) ;
    }

    private function bintoint($binin)
    {
        return bindec($binin);
    }

    private function binwmtonm($binin)
    {
        $binin=rtrim($binin, "1");
        if (!ereg("1", $binin)){
            return str_pad(str_replace("0", "1", $binin), 32, "0");
        } else return "1010101010101010101010101010101010101010";
    }

    private function cdrtobin($cdrin)
    {
        return str_pad(str_pad("", $cdrin, "1"), 32, "0");
    }

    private function dotbin($binin, $cdr_nmask)
    {
        // splits 32 bit bin into dotted bin octets
        if ($binin=="N/A") return $binin;
        $oct=rtrim(chunk_split($binin, 8, "."), ".");
        if ($cdr_nmask > 0){
            $offset=sprintf("%u", $cdr_nmask/8) + $cdr_nmask ;
            return substr($oct, 0, $offset ) . "&nbsp;&nbsp;&nbsp;" . substr($oct, $offset) ;
        } else {
            return $oct;
        }
    }

    private function dqtobin($dqin)
    {
        $dq = explode(".", $dqin);
        for ($i=0; $i<4 ; $i++) {
            $bin[$i]=str_pad(decbin($dq[$i]), 8, "0", STR_PAD_LEFT);
        }
        return implode("", $bin);
    }

    private function inttobin($intin)
    {
        return str_pad(decbin($intin), 32, "0", STR_PAD_LEFT);
    }

}