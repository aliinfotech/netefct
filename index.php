<?php
// Create connection
/*$con=mysqli_connect("localhost","root","8R7P43xdyx","test");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}else{
	echo "connected";
	}
exit();
*/

umask(0);
ini_set('session.save_path',realpath(dirname(__FILE__).'/tmp'));
ini_set('session.gc_maxlifetime', 864000);
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
defined('SYSTEM_PATH')
    || define('SYSTEM_PATH', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);


// Ensure library/ is on include_path
$lib_paths= array();
$lib_paths[]=realpath(dirname(__FILE__) ."/library");

$inc_path= implode(PATH_SEPARATOR,$lib_paths);
set_include_path($inc_path);

/** Zend_Application */
require_once 'Zend/Application.php';
// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
//code for using third party libraries 
require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('Core_');
$application->bootstrap()->run();
