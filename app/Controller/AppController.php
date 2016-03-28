<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');
App::uses('LocalAccess', 'Service');
App::uses('CakeEmail', 'Network/Email');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    /**
     * @var PHPTal
     */
    public $viewClass = 'Taltal.Phptal';
    public $helpers = array('Form', 'Html', 'Session');

    protected $accessIpList = array();

    public $aryTopicPath = array();
    public $aryError = array();
    public $app_var = array('params' => array());
    /**
     * beforeFilter
     * @author Luvina
     * @access public
     * @return
     */
    public function beforeFilter() {
        if(isset($this->request->params) && is_array($this->request->params)) {
            $this->app_var['params'] = array_merge($this->app_var['params'], $this->request->params);
        }
        if(isset($this->request->data) && is_array($this->request['data'])) {
            $this->app_var['params'] = array_merge($this->app_var['params'], $this->request->data);
        }
        if(isset($this->request->query) && is_array($this->request['query'])) {
            $this->app_var['params'] = array_merge($this->app_var['params'], $this->request->query);
        }
        $this->set('app', $this->app_var);
        parent::beforeFilter();
    }

    /**
     * loadConfig
     * @author Luvina
     * @access public
     * @return
     */
    public function loadConfig($fileName) {
        include_once  APP . 'Configapp' . DS . $fileName;
    }

    /**
     * appError
     * @author Luvina
     * @access public
     * @return
     */
    public function appError($error) {
        $this->set('content_noindex', 1);
        $this->set('title_layout', 'お探しのページは見つかりません - 訪問薬局ナビ｜在宅・訪問薬局をお探しなら訪問薬局ナビ');
        $this->hkRender("error_view", "default");
    }

    /**
     * getKeyValuePair
     * @author Luvina
     * @access public
     * @param String $keyName
     * @param String $valueName
     * @param array $list
     * @param String $keyItem
     * @param array $value
     * @return array
     */
    public function getKeyValuePairList($keyName, $valueName, $list, $keyItem = null) {
        $pairList = array();
        foreach ($list as $key => $value) {
            if (isset($keyItem)) {
                $pairList[$value[$keyItem][$keyName]] = $value[$keyItem][$valueName];
            } else {
                $pairList[$value[$keyName]] = $value[$valueName];
            }
        }
        return $pairList;
    }

    /**
     * getConfigValue get value of item in file config
     * @author Luvina
     * @access public
     * @param string $item
     * @param string $default
     * @return string|array
     */
    public function getConfigValue($item, $default) {
        $value = Configure::read($item);
        return isset($value) ? $value : $default;
    }
     /**
     * hkRender : render view
     * @author Luvina
     * @access private
     * @param string $view
     * @param string $layout
     * @return
     */
    private function hkRender($view =null, $layout = null) {
        $this->render($view, $layout);
        $this->response->send();
        $this->_stop();
    }
    /**
     * sendMail
     * @author Luvina
     * @access public
     * @param string $to
     * @param string $from
     * @param string $subject
     * @param string $template
     * @param string $data
     * @return
     */
    public function sendMail($to, $from, $subject, $template = null, $data = null, $format = 'html') {
        $Email = new CakeEmail();
        if ($template != '') {
            $Email->template($template, 'default');
            $Email->emailFormat($format);
        }
        if ($data != '') {
            $Email->viewVars($data);
        }
        $Email->subject($subject);
        $envelopeFrom = Configure::read('mail_envelope_from');
        $Email->transportClass()->config(array('additionalParameters'=>"-f{$envelopeFrom}"));
        $Email->to($to);
        $Email->from($from);
        $Email->send();
    }
    /**
     * get params router , post, get
     * @author Luvina
     * @access public
     * @param String $name
     * @return multitype:|NULL
     */
    public function get($name) {
        if(isset($this->request->params[$name])) {
            return trim($this->request->params[$name]);
        } elseif (isset($this->request->data[$name])) {
            return trim($this->request->data[$name]);
        } elseif (isset($this->request->query[$name])) {
            return trim($this->request->query[$name]);
        }
        return null;
    }
    /**
     * 
     * @param array||string $error
     */
    public function addError($error, $two = false) {
        if($two) {
            $this->aryError[$error] = $two;
            return $this->set('aryError', $this->aryError);
        }
        if(is_array($error)) {
            foreach ($error as $key =>$value) {
                foreach ($value as $k =>$v) {
                    $this->aryError[$key] = $v;
                }
            }
        } else {
            $this->aryError[] = $error;
        }
        return $this->set('aryError', $this->aryError);
    }

    /**
     * errorCount
     * @return number
     */
    public function errorCount() {
        return count($this->aryError);
    }
    /**
     * set app var
     */
    public function setApp($name, $value) {
        $this->app_var[$name] = $value;
        return $this->set('app', $this->app_var);
    }
    /**
     * set app var
     */
    public function getApp($name) {
        if(isset($this->app_var[$name])) {
            return $this->app_var[$name];
        } else {
            return null;
        }
    }

}
