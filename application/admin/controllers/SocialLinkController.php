<?php

class Admin_SocialLinkController extends Zend_Controller_Action
{
	    protected $user_session = null;
        private $db = null;
        private $baseurl = null;
        private $authAdapter = null;
		private $social = null;
		
	public function init(){
		Zend_Layout::startMvc(
		array('layoutPath'=>  APPLICATION_PATH . '/admin/layouts',  'layout' => 'layout'));
		$this->db = Zend_Db_Table::getDefaultAdapter();
        $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
		$this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
		$this->user_session = new Zend_Session_Namespace("user_session");
				
		ini_set("max_execution_time",(60*300));
		$this->social = new Application_Model_SocialLinks();
		
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
}
	
	public function editSocialLinksAction(){
	
	if($this->user_session->msg!=null)
		{
			$this->view->msg = $this->user_session->msg;
			$this->user_session->msg = null;
		}
		$form = new Application_Form_SocialLinkForm();
		$links = $this->social->getSocialLinks();
		$form->facebook->setValue($links->facebook);
		$form->linkedin->setValue($links->linkedin);
		$form->twitter->setValue($links->twitter);
		$form->youtube->setValue($links->youtube);
		$form->instagram->setValue($links->instagram);
		$form->google_plus->setValue($links->google_plus);
		$form->tumblr->setValue($links->tumblr);
		$this->view->form = $form;
		if($this->user_session->msg!=null)
		{
			$this->view->msg = $this->user_session->msg;
			$this->user_session->msg = null;
		}
		
		if (!$this->_request->isPost())return;
		$formData = $this->_request->getPost();
		if (!$form->isValid($formData)) return;
		
		$this->social->updateSocailLinks($formData);
		$this->user_session->msg = "<div class='alert alert-success'> Social Links are Updated Successfully. </div>";
	$this->_redirect('/admin/Social-Link/edit-social-links');
	}
	
}