<?php
/**
 Author: Musavir Ifitkahr:
 Date: May 2015
 kuala lumpur Malaysia
 */
class Admin_VideosController extends Zend_Controller_Action
{
	    var $user_session = null;
        private $db = null;
        private $baseurl = null;
        private $authAdapter = null;
		private $applicant = null;
		private $user = null;
		private $video = null;
	
	public function init(){
		Zend_Layout::startMvc(
		array('layoutPath'=>  APPLICATION_PATH . '/admin/layouts',  'layout' => 'layout'));
		$this->db = Zend_Db_Table::getDefaultAdapter();
        $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
		$this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
		if(!isset($this->user_session->user_id)){
		$this->user_session = new Zend_Session_Namespace("user_session");
		}
		
		ini_set("max_execution_time",(60*300));
		$this->user = new Application_Model_User();
		$this->video = new Application_Model_Video();
		
		if(!isset($this->user_session->user_id)){
			$this->_redirect("/admin/login/admin-login");			
		}
		
		$auth = Zend_Auth::getInstance();
		//if not loggedin redirect to login page
		if (!$auth->hasIdentity()){
		$this->_redirect('/admin/login/admin-login');
        }
	}


	// this is default output function
	public function indexAction()
{

		$form = new Application_Form_VForm();
		$this->view->form = $form;
		if($this->user_session->msg!=null)
		{
			$this->view->msg = $this->user_session->msg;
			$this->user_session->msg = null;
		}
		

		if (!$this->_request->isPost())return;
		$formData = $this->_request->getPost();
print_r(addslashes($formData['video']));
print_r(addslashes($formData['url_video']));
		
		$this->view->msg = addslashes($formData['video']) ;
		
		$this->user_session->msg = '"' . $formData['video'] . '"';
	//   var_dump($formData);
	 //  $this->_redirect('admin/videos/');
	   return;

}


public function savevAction(){
$this->ajaxed();
echo "working";
return;
 $url_video = $this->getRequest()->getParam('url_video');
$data = array ("v_id" => 15,'url_video' => $url_video);
$results = $this->video->updateVideo($this->db, $data);

}

 public function ajaxed() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        if (!$this->_request->isXmlHttpRequest()
            )return; // if not a ajax request leave function

    }
}