<?php
/**
 *
 * @author Luvina
 * @access public
 * @see index()
 */
App::uses('AdminController', 'Controller\Base');
App::uses('Util', 'Service');

class AdminStationController extends AdminController {
    public $uses = array('ColetAgent', 'ColetStore');

    /**
     * index
     * @author Luvina
     * @access public
     */
    public function index() {
    }

    /**
     * add staion
     * @author Luvina
     * @access public
     */
    public function add() {
        $aryData = $this->data;
        $this->set('aryData', $aryData);
        // array data config for column int
        $this->setDataColumnInt();
    }

}