<?php

class Admin_StripBannerController extends Zend_Controller_Action
{
	    protected $user_session = null;
        private $db = null;
        private $baseurl = null;
        private $authAdapter = null;
		private $stripBanner = null;
		
	public function init(){ 
		Zend_Layout::startMvc(
		array('layoutPath'=>  APPLICATION_PATH . '/admin/layouts',  'layout' => 'layout'));
		$this->db = Zend_Db_Table::getDefaultAdapter();
        $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
		$this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
		$this->user_session = new Zend_Session_Namespace("user_session");
		
		ini_set("max_execution_time",(60*300));
		$this->stripBanner = new Application_Model_StripBanner();
		
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

public function newStripBannerAction() 
	{
		$form = new Application_Form_AddStripBannerForm();
		$this->view->form = $form;
		if($this->user_session->msg!=null)
		{
			$this->view->msg = $this->user_session->msg;
			$this->user_session->msg = null;
		}
		
		if (!$this->_request->isPost())return;
		$formData = $this->_request->getPost();
		
		if (!$form->isValid($formData)) return;
		
		//For Images
		$file_name = NULL;
		 try {
			$banner_img = $_FILES['banner_img']['name'];
			$random = rand(10,10000);
			$time = time() + (7 * 24 * 60 * 60);
			$file_name = $time . $random . $banner_img;
			$formData["banner_img"] = $file_name;
	 
			move_uploaded_file($_FILES["banner_img"]['tmp_name'], SYSTEM_PATH."/images/admin/strip-banner-images/original/".$file_name);
			$thumb = new Application_Model_Thumbnail(SYSTEM_PATH."/images/strip-banner-images/original/".$file_name);
			$thumb->resize(200,200);
			$thumb->save(SYSTEM_PATH.'/images/admin/strip-banner-images/200X200/'.$file_name);
		}
		 
		catch (Zend_File_Transfer_Exception $e)
		{
			throw new Exception('Bad data: '.$e->getMessage());
		}

 		$result = $this->stripBanner->addStripBanner($formData);
		$this->view->msg = $result;
		//clear all form fields 

	$form->reset();

	}
	
	public function stripBannerListAction(){

	if(isset($this->user_session->msg)){
	$this->view->msg = $this->user_session->msg;
	unset($this->user_session->msg);
	}
	
	$results = $this->stripBanner->getAllStripBanners();
       if (count($results) > 0) {
		 $this->Paginator($results);
        } else {
        $this->view->empty_rec = true;
		}
}

	public function editStripBannerAction(){
	
	$id = $this->_request->getParam('sb_id');
	$form = new Application_Form_AddStripBannerForm();
	$this->view->sb_id = $id;
	
if(isset($id)){
	$this->user_session->sb_id = $id;
}
  
if(isset($id) || isset($this->user_session->sb_id)){
  	$result = $this->stripBanner->getStripBanner($this->user_session->sb_id);	
	
	//var_dump($result);
	//return;
	$this->view->sb_id = $result->sb_id;
    $form->banner_img->setValue($result->banner_img);
	$form->target_url->setValue($result->target_url);
	//$form->message->setValue($result->message);
	
	$form->is_main->setValue($result->is_main);
	
    $this->user_session->banner_img = $result->banner_img; 

    $this->view->form = $form;
}
     if (!$this->_request->isPost()) {
			$this->view->form = $form;
			return;
        } 
        
        $formData = $this->_request->getPost();

	   if (!$form->isValid($formData)) {
			$this->view->form = $form;
			return;
        }

		//For Image upload
	$file_name = NULL;
	
$image_name= $_FILES["banner_img"]["name"];

    if(isset($image_name) && strlen($image_name) > 0 ) {
	
	try {
				if(isset($this->user_session->video_image)){
				unlink(SYSTEM_PATH."/images/admin/strip-banner-images/original/".$result->banner_img);
				unlink(SYSTEM_PATH."/images/admin/strip-banner-images/200X200/".$result->banner_img);
				}
				 
			$banner_img = $_FILES['banner_img']['name'];
			$random = rand(10,10000);
			$time = time() + (7 * 24 * 60 * 60);
			$file_name = $time . $random . $banner_img;
			$formData["banner_img"] = $file_name;
	 
			move_uploaded_file($_FILES["banner_img"]['tmp_name'], SYSTEM_PATH."/images/admin/strip-banner-images/original/".$file_name);
			$thumb = new Application_Model_Thumbnail(SYSTEM_PATH."/images/admin/strip-banner-images/original/".$file_name);
			$thumb->resize(200,200); 
			$thumb->save(SYSTEM_PATH.'/images/admin/strip-banner-images/200X200/'.$file_name);
			
		}
		
	catch (Zend_File_Transfer_Exception $e)
		{
			throw new Exception('Bad data: '.$e->getMessage()); 
		}
}else{

$formData['banner_img']= $this->user_session->banner_img;

}
	
	$formData['sb_id']= $this->user_session->sb_id;

	$result = $this->stripBanner->editStripBanner($formData);
	$this->_redirect("/admin/strip-banner/strip-banner-list");
	}
	
	public function deleteStripBannerAction()
	{
		$id = $this->_request->getParam('id'); 
		
		$result = $this->stripBanner->getStripBanner($id);
		unlink(SYSTEM_PATH.'/images/admin/strip-banner-images/200X200/'.$result->banner_img);
		unlink(SYSTEM_PATH.'/images/admin/strip-banner-images/originals/'.$result->banner_img);
		
		$delete = $this->stripBanner->removeStripBanner($this->db, $id);
		$this->user_session->msg  = $delete; 
		$this->_redirect('/admin/strip-banner/strip-banner-list'); 
	}
	
	// Paginator action
	public function Paginator($results) {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
    }
}