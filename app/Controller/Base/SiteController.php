<?php
App::uses('AppController', 'Controller');

/**
 * SiteController: check smartphone and select view
 * @author Luvina
 * @access public
 * @see hkRender()
 * @see getCurrentPage()
 * @see configPaginate()
 */

class SiteController extends AppController{
    public $CsrfSecurity = null;
    public $device = 'pc';
    public $page = 1;
    public $Paginator;

    /**
     * beforeFilter
     * @author Luvina
     * @access public
     * @return
     */
    public function beforeFilter() {
        $this->disableCsrf();
        parent::beforeFilter();

        if (isset($this->request->query['page'])) {
            $this->page = $this->request->query['page'];
        } else if (isset($this->request->params['page'])) {
            $this->page = $this->request->params['page'];
        }

        if (Mobile_Util::isSmartPhone()) {
            $this->device = 'sp';
        }
        $this->set('request', $this->request);
    }
    /**
     * disableCsrf
     * @author Luvina
     * @access public
     * @return
     */
    public function disableCsrf() {
        $this->CsrfSecurity->validatePost = false;
        $this->CsrfSecurity->csrfCheck = false;
        $this->CsrfSecurity->unlockedActions = array($this->request->action);
    }
    /**
     * enableCsrf
     * @author Luvina
     * @access public
     * @param string $csrfFunction
     * @return
     */
    public function enableCsrf($csrfFunction = 'csrf') {
        $this->CsrfSecurity->csrfCheck = true;
        $this->CsrfSecurity->csrfUseOnce = true;
        $this->CsrfSecurity->unlockedActions = array();
        $this->CsrfSecurity->blackHoleCallback = $csrfFunction;
    }
    /**
     * hkRender : render view
     * @author Luvina
     * @access public
     * @param string $view
     * @param string $layout
     * @return
     */
    
    function hkRender($view =null, $layout = null) {
        try {
            parent::render($view, $layout);
        } catch (Exception $e){
            parent::render('/Errors/error_view');
        }
    }
    /**
     * configMap
     * @author Luvina
     * @access public
     * @param int $latitude
     * @param int $longitude
     * @param string $address
     * @param boolean $zoomJapan
     * @param boolean $isDetail
     * @return
     */
    public function configMap($latitude, $longitude, $address, $zoom) {
        $mapConfig = array();
        $address = str_replace(array("\r\n", "\n"), '\\n', $address);
        $mapConfig['address'] = $address;
        $mapConfig['latitude'] = $latitude;
        $mapConfig['longitude'] = $longitude;
        $mapConfig['zoom'] = $zoom;
        $this->set('map', $mapConfig);
    }
}