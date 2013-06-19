<?php
/**
 * Created by JetBrains PhpStorm.
 * User: quangvu
 * Date: 6/4/13
 * Time: 1:35 PM
 * To change this template use File | Settings | File Templates.
 */
//define paths
define('DS', DIRECTORY_SEPARATOR );
define('SITE_ROOT', dirname(__FILE__) . DS );
define('VENDOR', SITE_ROOT . 'vendor' .DS) ;
define('APP', SITE_ROOT . 'app' .DS );
define('ASSETS', SITE_ROOT . 'assets' .DS );
define('APP_VIEWS_PATH', APP. 'views' . DS);
//Theme config
define('LAYOUTS_PATH', APP. 'views' . DS . 'layouts' . DS );
define('VIEWS_SCRIPTS_PATH', APP_VIEWS_PATH . 'scripts' .DS  );
define('MASTER_LAYOUT', 'master');
//create AutoloadManager object
require_once(VENDOR .'Coo' .DS. 'Helper' .DS. 'Coo_Helper_AutoloadManager.php');
$autoload = new Coo_Helper_AutoloadManager(null, Coo_Helper_AutoloadManager::SCAN_ALWAYS);
$autoload->addFolder(VENDOR);
$autoload->addFolder(APP);
$autoload->register();