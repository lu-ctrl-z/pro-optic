<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
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
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
    Router::connect('/', array('controller'                     => 'home',     'action' => 'index'));
    Router::connect('/login', array('controller'                => 'adminUser','action' => 'login'));
    Router::connect('/logout', array('controller'               => 'adminUser','action' => 'logout'));
    Router::connect('/login/auth', array('controller'           => 'adminUser','action' => 'auth'));

    Router::connect('/area/:pref', array('controller'           => 'station',  'action' => 'area'));
    Router::connect('/area/:pref/:city', array('controller'     => 'station',  'action' => 'areaCity'));
    Router::connect('/list/', array('controller'                => 'station',  'action' => 'search'));
    Router::connect('/list/:address', array('controller'        => 'station',  'action' => 'search'));
    Router::connect('/list/:address/:page', array('controller'  => 'station',  'action' => 'search'));
    Router::connect('/detail/:id', array('controller'           => 'station',  'action' => 'detail'));

    Router::connect('/entry', array('controller'                => 'newentry', 'action' => 'entry'));
    Router::connect('/entry/preview', array('controller'        => 'newentry', 'action' => 'entryPreview'));
    Router::connect('/entry/do', array('controller'             => 'newentry', 'action' => 'entryDo'));
    Router::connect('/entry/send', array('controller'           => 'newentry', 'action' => 'entrySend'));
    //End Newentry
    //Start Singup
    Router::connect('/signup', array('controller'               => 'newentry', 'action' => 'signup'));
    Router::connect('/aadd', array('controller'                 => 'newentry', 'action' => 'newentryForm'));
    Router::connect('/aadd/preview', array('controller'         => 'newentry', 'action' => 'newentryPreview'));
    Router::connect('/aadd/do', array('controller'              => 'newentry', 'action' => 'newentryDo'));
    Router::connect('/aadd/send', array('controller'            => 'newentry', 'action' => 'newentrySend'));
    //End Singup
    //Start Portal manager user
    Router::connect('/portal', array('controller'               => 'adminStation', 'action' => 'sList'));
    Router::connect('/portal/ulist', array('controller'         => 'adminUser', 'action' => 'uList'));
    Router::connect('/portal/ulist/:page', array('controller'   => 'adminUser', 'action' => 'uList'));
    Router::connect('/portal/confirm', array('controller'       => 'portal',    'action' => 'confirmInState'));
    Router::connect('/portal/uadd', array('controller'          => 'adminUser', 'action' => 'uAdd'));
    Router::connect('/portal/uadd/preview', array('controller'  => 'adminUser', 'action' => 'uAddPreview'));
    Router::connect('/portal/uadd/do', array('controller'       => 'adminUser', 'action' => 'uAddDo'));
    Router::connect('/portal/uadd/send', array('controller'     => 'adminUser', 'action' => 'uAddSend'));
    Router::connect('/portal/uedit', array('controller'         => 'adminUser', 'action' => 'uEdit'));
    Router::connect('/portal/uedit/preview', array('controller' => 'adminUser', 'action' => 'uEditPreview'));
    Router::connect('/portal/uedit/do', array('controller'      => 'adminUser', 'action' => 'uEditDo'));
    Router::connect('/portal/uedit/send', array('controller'    => 'adminUser', 'action' => 'uEditSend'));
    Router::connect('/portal/udel/', array('controller'         => 'adminUser', 'action' => 'uDel'));
    Router::connect('/portal/udel/do', array('controller'       => 'adminUser', 'action' => 'uDelDo'));
    Router::connect('/portal/udel/send', array('controller'     => 'home', 'action' => 'uDelSend'));
    //End Portal manager user
    //Start Portal manager Station
    Router::connect('/portal/slist', array('controller'         => 'adminStation', 'action' => 'sList'));
    Router::connect('/portal/slist/:page', array('controller'   => 'adminStation', 'action' => 'sList'));
    Router::connect('/portal/sadd', array('controller'          => 'adminStation', 'action' => 'sAdd'));
    Router::connect('/portal/sadd/preview', array('controller'  => 'adminStation', 'action' => 'sAddPreview'));
    Router::connect('/portal/sadd/do', array('controller'       => 'adminStation', 'action' => 'sAddDo'));
    Router::connect('/portal/sadd/send', array('controller'     => 'adminStation', 'action' => 'sAddSend'));

    Router::connect('/portal/sedit', array('controller'         => 'adminStation', 'action' => 'sEdit'));
    Router::connect('/portal/sedit/preview', array('controller' => 'adminStation', 'action' => 'sEditPreview'));
    Router::connect('/portal/sedit/do', array('controller'      => 'adminStation', 'action' => 'sEditDo'));
    Router::connect('/portal/sedit/send', array('controller'    => 'adminStation', 'action' => 'sEditSend'));

    Router::connect('/portal/sdel', array('controller'          => 'adminStation', 'action' => 'sDel'));
    Router::connect('/portal/sdel/do', array('controller'       => 'adminStation', 'action' => 'sDelDo'));
    Router::connect('/portal/sdel/send', array('controller'     => 'adminStation', 'action' => 'sDelSend'));
    //End Portal manager Station
    //Start Portal manager Interview
    Router::connect('/portal/ilist', array('controller'         => 'adminInterview', 'action' => 'iList'));
    Router::connect('/portal/ilist/:page', array('controller'   => 'adminInterview', 'action' => 'iList'));
    Router::connect('/portal/iadd',  array('controller'         => 'adminInterview', 'action' => 'iAdd'));
    Router::connect('/portal/iadd/preview',  array('controller'         => 'adminInterview', 'action' => 'iAddPreview'));
    Router::connect('/portal/iadd/do', array('controller'       => 'adminInterview', 'action' => 'iAddDo'));
    Router::connect('/portal/iadd/send', array('controller'     => 'adminInterview', 'action' => 'iAddSend'));

    Router::connect('/portal/iedit',  array('controller'         => 'adminInterview', 'action' => 'iEdit'));
    Router::connect('/portal/iedit/preview', array('controller' => 'adminInterview', 'action' => 'iEditPreview'));
    Router::connect('/portal/iedit/do', array('controller'       => 'adminInterview', 'action' => 'iEditDo'));
    Router::connect('/portal/iedit/send', array('controller'     => 'adminInterview', 'action' => 'iEditSend'));

    Router::connect('/portal/idel', array('controller'          => 'adminInterview', 'action' => 'iDel'));
    Router::connect('/portal/idel/do', array('controller'       => 'adminInterview', 'action' => 'iDelDo'));
    Router::connect('/portal/idel/send', array('controller'     => 'adminInterview', 'action' => 'iDelSend'));
    //End Portal manager Interview
/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
    CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
    require CAKE . 'Config' . DS . 'routes.php';
