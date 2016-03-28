<?php
/**
 *
 * @author Luvina
 * @access public
 * @see index()
 */

App::uses('AdminController', 'Controller');
App::uses('Util', 'Service');

class AdminColetAgentController extends AdminController {
    public $uses = array('ColetAgent', 'ColetStore');
    private $aryColumnVarchar = array(
            'agent_CD',
            'agent_kan_sei',
            'agent_kan_mei',
            'agent_kana_sei',
            'agent_kana_mei',
    );

    /**
     * index
     * @author Luvina
     * @access public
     */
    public function index() {
        $smsPersonName = $this->get('agent_sms');

        $params = array();
        if ($smsPersonName != '') {
            $params[] = 'agent_sms=' . $smsPersonName;
        }
        // get count record
        $countAgent = $this->ColetAgent->getCountAgent($smsPersonName);

        $aryAgent = array();
        if ($countAgent > 0) {
            // config for paginate
            $limitPerPage = 10;
            $modulus = 9;
            Util::configPaginate('ColetAgent', $countAgent, $this->page, $limitPerPage, $modulus);

            $pageCount = (int)ceil($countAgent / $limitPerPage);
            Util::getCurrentPage($pageCount);
            $offset = ($this->page - 1) * $limitPerPage;

            $aryAgent = $this->ColetAgent->getAllAgent($smsPersonName, $offset, $limitPerPage);
            $strParams = implode('&', $params);

            $url_params = array(
                    'controller' => 'adminColetAgent',
                    'action' => 'index',
                    '?' => $strParams,
            );
            $this->set('url_params', $url_params);
            $this->set('modulus', $modulus);
            $indexTo = $offset + $limitPerPage;
            $indexTo = ($indexTo > $countAgent) ? $countAgent : $indexTo;
            $this->set('indexFrom', $offset);
            $this->set('indexTo', $indexTo);
            $textPaginate = ($offset + 1) . '～' . ($indexTo). '件を表示 / 全' . $countAgent . '件';
            $this->set('textPaginate', $textPaginate);
            $this->set('countAgent', $countAgent);
        }
        $this->set('aryAgent', $aryAgent);
        $this->set('agent_sms', $smsPersonName);
        $this->set('showSideLeft', 1);
    }

    /**
     * add Agent SMS
     * @author Luvina
     * @access public
     */
    public function add() {
        $this->set('showSideLeft', 1);
        $aryData = $this->data;
        $this->set('aryData', $aryData);
    }

    /**
     * Confirm Add Contactor
     * @author Luvina
     * @access public
     */
    public function addConfirm() {
        $this->set('showSideLeft', 1);
        $aryData = $this->data;
        $aryData = Util::trimData($aryData,$this->aryColumnVarchar);

        $isError = $this->ColetAgent->validateData($aryData);
        $this->ColetAgent->checkExistAgentCD($aryData['agent_CD']);
        $aryError = $this->ColetAgent->errors;
        if (empty($aryError)) {
            $this->set('aryData', $aryData);
            $this->set('aryError', array());
            $this->Render('/AdminColetAgent/add_confirm');
        } else {
            $this->set('aryError', $aryError);
            $this->set('aryData', $aryData);
            $this->Render('/AdminColetAgent/add');
        }
    }

    /**
     * Complete Add Contactor
     * @author Luvina
     * @access public
     */
    public function addComplete() {
        if ($this->CsrfSecurity->validateCsrf($this) === false) {
            $this->Session->setFlash('不正なリクエストです。手順の始めからやり直してください', 'default', array('class' => "show_error"), 'message');
            $this->redirect('/admin/coletagent/index');
        }
        if ($this->CsrfSecurity->validateDuplicate($this) === false) {
            $this->Session->setFlash('不正なリクエストです。手順の始めからやり直してください', 'default', array('class' => "show_error"), 'message');
            $this->redirect('/admin/coletagent/index');
        }
        $this->set('showSideLeft', 1);
        $aryData = $this->data;
        $aryData = Util::trimData($aryData, $this->aryColumnVarchar);

        $isError = $this->ColetAgent->validateData($aryData);
        $this->ColetAgent->checkExistAgentCD($aryData['agent_CD']);
        $aryError = $this->ColetAgent->errors;

        if (empty($aryError)) {
            if ($this->ColetAgent->addAgent($aryData)) {
                $this->Session->setFlash('追加が完了しました。', 'default', array('class' => "show_message"), 'message');
            } else {
                $this->Session->setFlash('DBの登録でエラーがありました。', 'default', array('class' => "show_message"), 'message');
            }
            $this->redirect('/admin/coletagent/index');
        } else {
            $this->set('aryError', $aryError);
            $this->set('aryData', $aryData);
            $this->Render('/AdminColetAgent/add');
        }
    }

    /**
     * edit Contactor
     * @author Luvina
     * @access public
     */
    public function edit() {
        $aryData = array();
        $this->set('showSideLeft', 1);
        $idAgent = $this->get('id');
        $aryDataPost = $this->data;
        if ($idAgent != '' ) {
            $aryDataDetail = $this->ColetAgent->getDetailAgent($idAgent);
            if (count($aryDataDetail) > 0) {
                if (!isset($aryDataPost['action_back'])) {
                    $aryData['id'] = $aryDataDetail["ColetAgent"]["id"];
                    foreach ($this->aryColumnVarchar as $key) {
                        $aryData[$key] = $aryDataDetail["ColetAgent"][$key];
                    }
                } else {
                    $aryData = $aryDataPost;
                }
                $this->set('aryData', $aryData);
            } else {
                $this->Session->setFlash('IDが正しくありません。', 'default', array('class' => "show_error"), 'message');
                $this->redirect('/admin/coletagent/index');
            }
        } else {
            $this->Session->setFlash('IDが正しくありません。', 'default', array('class' => "show_error"), 'message');
            $this->redirect('/admin/coletagent/index');
        }
    }

    /**
     * Confirm Edit Agent
     * @author Luvina
     * @access public
     */
    public function editConfirm() {
        $this->set('showSideLeft', 1);
        $aryData = $this->data;
        $idAgent = $aryData['id'];
        if ($idAgent != '') {
            $aryDataDetail = $this->ColetAgent->getDetailAgent($idAgent);
            if (count($aryDataDetail) > 0) {
                $aryData['agent_CD'] = $aryDataDetail["ColetAgent"]["agent_CD"];
                $aryData = Util::trimData($aryData, $this->aryColumnVarchar);
                $isError = $this->ColetAgent->validateData($aryData);
                $aryError = $this->ColetAgent->errors;
                if (empty($aryError)) {
                    $this->set('aryData', $aryData);
                    $this->set('aryError', array());
                    $this->Render('/AdminColetAgent/edit_confirm');
                } else {
                    $this->set('aryError', $aryError);
                    $this->set('aryData', $aryData);
                    $this->Render('/AdminColetAgent/edit');
                }
            } else {
                $this->Session->setFlash('IDが正しくありません。', 'default', array('class' => "show_error"), 'message');
                $this->redirect('/admin/coletagent/index');
            }
        } else {
            $this->Session->setFlash('IDが正しくありません。', 'default', array('class' => "show_error"), 'message');
            $this->redirect('/admin/coletagent/index');
        }
    }

    /**
     * Complete Edit Contactor
     * @author Luvina
     * @access public
     */
    public function editComplete() {
        if ($this->CsrfSecurity->validateCsrf($this) === false) {
            $this->Session->setFlash('不正なリクエストです。手順の始めからやり直してください', 'default', array('class' => "show_error"), 'message');
            $this->redirect('/admin/coletagent/index');
        }
        if ($this->CsrfSecurity->validateDuplicate($this) === false) {
            $this->Session->setFlash('不正なリクエストです。手順の始めからやり直してください', 'default', array('class' => "show_error"), 'message');
            $this->redirect('/admin/coletagent/index');
        }
        $this->set('showSideLeft', 1);
        $aryData = $this->data;
        $aryData = Util::trimData($aryData, $this->aryColumnVarchar);
        $idAgent = $aryData['id'];
        if ($idAgent != '') {
            $aryDataDetail = $this->ColetAgent->getDetailAgent($aryData['id']);
            if (count($aryDataDetail) > 0) {
                $aryData['agent_CD'] = $aryDataDetail["ColetAgent"]["agent_CD"];
                $isError = $this->ColetAgent->validateData($aryData);
                $aryError = $this->ColetAgent->errors;
                if (empty($aryError)) {
                    if ($this->ColetAgent->updateAgent($aryData)) {
                        $this->Session->setFlash('更新が完了しました。', 'default', array('class' => "show_message"), 'message');
                    } else {
                        $this->Session->setFlash('DBの登録でエラーがありました。', 'default', array('class' => "show_error"), 'message');
                    }
                    $this->redirect('/admin/coletagent/index');
                } else {
                    $this->set('aryError', $aryError);
                    $this->set('aryData', $aryData);
                    $this->Render('/AdminColetAgent/edit');
                }
            }
        } else {
            $this->Session->setFlash('IDが正しくありません。', 'default', array('class' => "show_error"), 'message');
            $this->redirect('/admin/coletagent/index');
        }
    }

    /**
     * delete Agent
     * @author Luvina
     * @access public
     */
    public function delete() {
        if ($this->CsrfSecurity->validateCsrf($this) === false) {
            $this->Session->setFlash('不正なリクエストです。手順の始めからやり直してください', 'default', array('class' => "show_error"), 'message');
            $this->redirect('/admin/coletagent/index');
        }
        $id = $this->get('id');
        $conditions = array(
                'id' => $id
        );
        $list = $this->ColetAgent->find('first', array("conditions" => $conditions));

        if (count($list) <= 0) {
            $this->Session->setFlash('IDが正しくありません。', 'default', array('class' => "show_error"), 'message');
        } else {
            try {
                $dataSourceColetStore = $this->ColetStore->getDataSource();
                $dataSourceColetStore->begin();
                // delete in table t_colet_agent
                $this->ColetAgent->deleteAll($conditions, false, false);

                $conditionsColetStore = array('agent_CD' => $list['ColetAgent']['agent_CD']);
                // delete in table t_colet_store
                $this->ColetStore->deleteAll($conditionsColetStore, false, false);
                $dataSourceColetStore->commit();
                $this->Session->setFlash('削除が完了しました。', 'default', array('class' => "show_message"), 'message');
            } catch(Exception $e) {
                $this->Session->setFlash('DBの登録でエラーがありました。', 'default', array('class' => "show_error"), 'message');
                $dataSourceColetStore->rollback();
            }
        }
        $this->redirect('/admin/coletagent/index');
    }
}