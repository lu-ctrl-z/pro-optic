<?php
/**
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation,Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information,please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation,Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 2.4.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('AbstractPasswordHasher','Controller/Component/Auth');
App::uses('Security','Utility');

/**
 * Simple password hashing class.
 *
 * @package       Cake.Controller.Component.Auth
 */
class YkPasswordHasher extends AbstractPasswordHasher {

/**
 * Config for this object.
 *
 * @var array
 */
    protected $_config = array('hashType' => null,
                               'stretchCount' => 0,
                               'salt' => false
    );

/**
 * Generates password hash.
 *
 * @param string $password Plain text password to hash.
 * @return string Password hash
 */
    public function hash($password, $hashType = null, $stretchCount = null, $salt = null) {
        $ht = $this->_config['hashType'];
        $str_c = $this->_config['stretchCount'];
        $slt = $this->_config['salt'];
        if ($hashType != null){
            $ht = $hashType;
        }
        if ($stretchCount != null){
            $str_c = $stretchCount;
        }
        if ($salt != null) {
            $slt = $salt;
        }
        $password = Security::hash($password, $ht, $slt);
        if ($str_c >0){
            for($i=1; $i<=$str_c; $i++){
                $password = Security::hash($password, $ht,false);
            }}
        return $password;
    }

/**
 * Check hash. Generate hash for user provided password and check against existing hash.
 *
 * @param string $password Plain text password to hash.
 * @param string Existing hashed password.
 * @return boolean True if hashes match else false.
 */
    public function check($password, $hashedPassword) {
        return $hashedPassword === $this->hash($password);
    }
}
