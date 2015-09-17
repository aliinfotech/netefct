<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    public  $frontController = null;
	public  $root = '';
	public  $config = null;
	public  $registry = null;
	public  $lang_file = '';
	public  $lang = '';
	public  $language_id = '';
	public  $translator = null;
	public  $layout = null;

	public function run()
	{
		$this->setupEnvironment();
		$this->prepare();
		$response = $this->frontController->dispatch();
		$this->sendResponse($response);
        }

	public function setupEnvironment()
	{
		error_reporting(E_ALL|E_STRICT);
		ini_set('display_errors', true);
		date_default_timezone_set('Asia/Kuala_Lumpur');
		$this->root = dirname(dirname(__FILE__));
	}

	public function prepare()
	{
		$this->setupEnvironment();
		$this->setupRegistry();
        $this->setupConfiguration();
		$this->setupFrontController();
		$this->startSession();
		$this->setupView('default');//current theme direcotry
		$this->setupLanguage();
 		     }

        	public function setupRegistry()
	{
		$this->registry = Zend_Registry::getInstance();
		$this->registry->set('config', $this->config);
		$this->registry->set('frontController',$this->frontController);
	}

        public function setupConfiguration(){
		$this->config = new Zend_Config_Ini($this->root.'/application/configs/application.ini', 'production');
	}

	public function setupFrontController()
	{
            Zend_Loader::loadClass('Zend_Controller_Front');
        $this->frontController = Zend_Controller_Front::getInstance();
		$this->frontController->throwExceptions(true);
		$this->frontController->returnResponse(true);
		$this->frontController ->setParam('noErrorHandler', false);
		$this->frontController->setControllerDirectory(array(
		'default' => $this->root .'/application/default/controllers/',
		'admin' => $this->root .'/application/admin/controllers/'
		));

//$router = $this->frontController->getRouter();
//    $router->addRoute('route-name1',
//                new Zend_Controller_Router_Route('/:urlkey', array('controller'=>'product', 'action'=>'custom-url'))
//                );//one router is added 
//       //// this rounter makes index controller and index function invisible from user 
//         $router->addRoute('route-name2',
//                new Zend_Controller_Router_Route('/', array('controller'=>'index', 'action'=>'index'))
//                );
}

	public function startSession(){
			Zend_Session::start();
	}

	public function setupView($crt_theme)
	{
		$view = new Zend_View;
		$view->setEncoding('UTF-8');
		$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer($view);
		Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
		$view->setScriptPath($this->root .'/application/'.$crt_theme.'/scripts/');
		$view->setHelperPath($this->root .'/application/'.$crt_theme.'/helpers');
		$this->layout = Zend_Layout::startMvc(
		array(
                'layoutPath' => $this->root . '/application/'.$crt_theme.'/layouts',
                'layout' => 'layout'
                )
                );
   }

	public function setupLanguage()
	{
		if(!isset($_COOKIE['lang_file'])){ $this->lang_file = 'english.xml'; }else{ $this->lang_file = $_COOKIE['lang_file'];}
		if(!isset($_COOKIE['lang'])){ $this->lang = 'en'; }else{ $this->lang = $_COOKIE['lang'];}
		if(!isset($_COOKIE['language_id'])){ $this->language_id = 1 ; }else{ $this->lang = $_COOKIE['language_id'];}

		//set lang and lang_file for user language
		$this->registry->set('lang_file',$this->lang_file);
		$this->registry->set('lang',$this->lang);
		$this->registry->set('lang_id',$this->language_id);

		$this->translator = new Zend_Translate('Zend_Translate_Adapter_Tmx', $this->root.'/application/language/' . $this->lang_file, $this->lang);
		Zend_Registry::set('Zend_Translate', $this->translator);
	}

	// We are not using this method any more
        public function sendResponse(Zend_Controller_Response_Http $response)
	{
	 $response->setHeader('Content-Type', 'text/html; charset=UTF-8', true);
	$response->sendResponse();
                 
        }

    public function _initCache() {
    $cache = Zend_Cache::factory(
                    'Core', 'File', array(
                'lifetime' => 3600 * 24 * 7, /*caching time is 7 days*/                            
                'automatic_serialization' => true
                    ), array('cache_dir' => APPLICATION_PATH . '/cache' /* This is caching folder where caching data will be stored and it must be writable by apache **/
                )
    );
    
    Zend_Registry::set('Cache', $cache); /* set the cache object in zend_registery so that you can globally access*/
}
}