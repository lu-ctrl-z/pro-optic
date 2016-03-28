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

    public $components = array(
            'Session',
            'Cookie',
            'CsrfSecurity' => array(
                    'csrfExpires' => '+1 hour'),
            'Paginator',
    );
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
     *
     * @return boolean
     */
    public function isLogined() {
        $this->userInfo = $this->Session->read(Configure::read('ss_auth'));
        if (!empty($this->userInfo)) {
            $this->set('isLogin', true);
            $this->set('userInfo', $this->userInfo);
            return true;
        }
        return false;
    }
    
    /**
     * set data còig
     * @author Luvina
     * @access public
     */
    public function setDataColumnInt() {
        $this->loadConfig('Station.ini.php');

        $aryVisitGuidance = Configure::read('visit_guidance');
        $aryStationFunction = Configure::read('station_function');
        $aryHospitalLink = Configure::read('hospital_link');
        $aryHourAday = Configure::read('24_hour_aday');
        $aryPalCare = Configure::read('pal_care');
        $aryMentalCare = Configure::read('mental_care');
        $aryKidsCare = Configure::read('kids_care');
        $aryDementiaCare = Configure::read('dementia_care');
        $aryHandicappedCare = Configure::read('handicapped_care');

        $this->set('aryVisitGuidance', $aryVisitGuidance);
        $this->set('aryStationFunction', $aryStationFunction);
        $this->set('aryHospitalLink', $aryHospitalLink);
        $this->set('aryHourAday', $aryHourAday);
        $this->set('aryPalCare', $aryPalCare);
        $this->set('aryMentalCare', $aryMentalCare);
        $this->set('aryKidsCare', $aryKidsCare);
        $this->set('aryDementiaCare', $aryDementiaCare);
        $this->set('aryHandicappedCare', $aryHandicappedCare);
    }
}