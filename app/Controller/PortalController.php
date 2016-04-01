<?php
App::uses('AdminController', 'Controller/Base');

class PortalController extends AdminController {
    /**
     * URL: /portal/confirm
     * show portal page
     */
    function confirmInState() {
        $this->loadModel('User');
        $next = $this->get('next');
        if(!$next) {
            $this->redirect('/portal/');
        }
        $instate_id = $this->get('instate_id');

        if($instate_id) {
            $instateUser = $this->User->find('first', array(
                    'conditions' => array('id' => $instate_id, 'user_mode' => 2, 'delete_date' => null)));
            if($instateUser) {
                $url_parts = parse_url($next);
                if(isset($url_parts['query'])) {
                    parse_str($url_parts['query'], $params);
                }
                $base_ssl_url = Configure::read('base_ssl_url');
                $params['instate_id'] = $instateUser['User']['id'];
                $url_parts['query'] = http_build_query($params);
                $next = $base_ssl_url . $url_parts['path'] . '?' . $url_parts['query'];
                $this->redirect($next);
                return true;
            }
        }

        $aryFields = array('User.id, User.name_sei, User.name_mei, User.com_CD, Corporation.corporation_name');
        $aryJoins = array(
                array(
                    'table' => 't_corporation',
                    'alias' => 'Corporation',
                    'type' => 'INNER',
                    'conditions' => array(
                        "User.com_CD = Corporation.com_CD"
                    )
                )
            );
        $instateUser = $this->User->find('all', array(
                'conditions' => array('User.user_mode' => 2, 'User.delete_date' => null, 'Corporation.delete_date' => null),
                'fields' => $aryFields,
                'joins' => $aryJoins));
        foreach ( $instateUser as $key => $value) {
           $instateUser[$key]['User'] = array_merge($value['User'], $value['Corporation']);
           unset($instateUser[$key]["Corporation"]);
        }
        $this->setApp('instateUser', $instateUser);
        $this->hkRender('confirm_instate', 'portal');
    }
}