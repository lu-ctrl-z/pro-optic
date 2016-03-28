<?php
App::import('Component','Security');

/**
 * CsrfSecurityComponent
 * @author Luvina
 * @access public
 * @see beforeRender()
 * @see validateCsrf()
 * @see validateDuplicate()
 * @see _validateCsrfAndDuplicate()
 * @see _validateToken()
 * @see _checkSessionIdIsBlank()
 */
class CsrfSecurityComponent extends SecurityComponent {

    /**
     * beforeRender
     * @author Luvina
     * @access public
     * @param Controller $controller
     * @return
     */
    public function beforeRender(Controller $controller) {
        $this->generateToken($controller->request);
        $duplicateKey = Security::generateAuthKey();
        $controller->Session->write('_TokenDuplicate.key', $duplicateKey);
        $controller->request->params['_TokenDuplicate']['key'] = $duplicateKey;
    }
    /**
     * validateCsrf
     * @author Luvina
     * @access public
     * @param Controller $controller
     * @return
     */
    public function validateDuplicate(Controller $controller) {
        $flg = $this->_validateCsrfAndDuplicate($controller);
        return ($flg === 'dup_error') ? false : true;
    }
    /**
     * validateCsrf
     * @author Luvina
     * @access public
     * @param Controller $controller
     * @return
     */
    public function validateCsrf(Controller $controller) {
        $flg = $this->_validateCsrfAndDuplicate($controller);
        return ($flg === 'csrf_error') ? false : true;
    }

    /**
     * _validateCsrfAndDuplicate
     * @author Luvina
     * @access private
     * @param Controller $controller
     * @return
     */
    private function _validateCsrfAndDuplicate(Controller $controller) {
        if(!is_null($this->_isValidated)) {
            return $this->_isValidated;
        }

        // step 1: check tokenId
        $requestToken = trim($controller->request->data("Csrf.key"));
        if(strlen($requestToken) == 0) {
            $this->_isValidated = 'csrf_error';
            return $this->_isValidated;
        }

        // step 2: check session id
        if($this->_checkSessionIdIsBlank($requestToken)) {
            $this->_isValidated = 'csrf_error';
            return $this->_isValidated;
        }

        return $this->_validateToken($requestToken);
    }

    /**
     * _validateCsrfAndDuplicate
     * @author Luvina
     * @access private
     * @param Controller $controller
     * @return
     */
    private function _validateToken($requestToken) {
        $csrfTokens = $this->Session->read('_Token.csrfTokens');
        // step 3: session data
        if (isset($csrfTokens[$requestToken]) && $csrfTokens[$requestToken] <= 0) {
            $this->_isValidated = 'dup_error';
            return $this->_isValidated;
        }

        // step 4:
        if (isset($csrfTokens[$requestToken]) && $csrfTokens[$requestToken] < time()) {
            $this->_isValidated = 'csrf_error';
            return $this->_isValidated;
        }

        if (isset($csrfTokens[$requestToken]) && $csrfTokens[$requestToken] >= time()) {
            $this->_isValidated = '';
            $csrfTokens[$requestToken] = 0;
            $this->Session->write('_Token.csrfTokens', $csrfTokens);
            return $this->_isValidated;
        }

        $this->_isValidated = 'csrf_error';
        return $this->_isValidated;
    }

    /**
     * _checkSessionIdIsBlank
     * @access private
     * @author Luvina
     * @param Controller $controller
     * @return
     */
    private function _checkSessionIdIsBlank($requestToken) {
        $csrfTokens = $this->Session->read('_Token.csrfTokens');
        if( isset($csrfTokens[$requestToken]) && strlen($csrfTokens[$requestToken]) > 0) {
            return false;
        }
        return true;
    }
}