<?php
/**
 * AppShell file
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
 * @since         CakePHP(tm) v 2.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Shell', 'Console');

/**
 * Application Shell
 *
 * Add your application-wide methods in the class below, your shells
 * will inherit them.
 *
 * @package       app.Console.Command
 */
class AppShell extends Shell {

    /**
     * sendMail
     * @author Luvina
     * @access public
     * @param string $message
     * @return
     */
    public function sendMail($message, $subject = null, $to = null, $from = null) {
        $mailBatch = Configure::read('mail_admin');
        $mailBatch['subject'] = (empty($subject)) ? $mailBatch['subject'] : $subject;
        $mailBatch['to']      = (empty($to))      ? $mailBatch['to']      : $to;
        $mailBatch['from']    = (empty($from))    ? $mailBatch['from']    : $from;
        $email = new CakeEmail();
        $email->from($mailBatch['from']);
        // #128 Start Luvina Modify
        $envelopeFrom = Configure::read('mail_envelope_from');
        $email->transportClass()->config(array('additionalParameters'=>"-f{$envelopeFrom}"));
        // #128 End Luvina Modify
        $email->to($mailBatch['to']);
        $email->subject($mailBatch['subject']);
        $email->send($message);
    }
    // #129 Start Luvina Modify
    /**
     * sendMailConfirmation
     * @author Luvina
     * @access public
     * @param string $to
     * @param string $from
     * @param string $subject
     * @param string $template
     * @param string $data
     * @return
     */
    public function sendMailConfirmation($to, $from, $subject, $template = null, $data = null) {
        $Email = new CakeEmail();
        if ($template != '') {
            $Email->template($template, 'default');
            $Email->emailFormat('html');
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
    // #129 End Luvina Modify
    /**
     * loadConfig
     * @author Luvina
     * @access public
     * @return
     */
    public function loadConfig($fileName) {
        include_once  APP . 'Configapp' . DS . $fileName;
    }
}
