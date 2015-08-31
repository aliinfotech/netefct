<?php

class Admin_BannerController extends Zend_Controller_Action
{
	    protected $user_session = null;
        private $db = null;
        private $baseurl = null;
        private $authAdapter = null;
		private $banner = null;
		
	public function init(){
		Zend_Layout::startMvc(
		array('layoutPath'=>  APPLICATION_PATH . '/admin/layouts',  'layout' => 'layout'));
		$this->db = Zend_Db_Table::getDefaultAdapter();
        $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
		$this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
		$this->user_session = new Zend_Session_Namespace("user_session");
				
		ini_set("max_execution_time",(60*300));
		$this->banner = new Application_Model_Banner();
		
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

public function newBannerAction() 
	{
		$form = new Application_Form_AddMainBannerForm();
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
	 
			move_uploaded_file($_FILES["banner_img"]['tmp_name'], SYSTEM_PATH."/images/banners/original/".$file_name);
			$thumb = new Application_Model_Thumbnail(SYSTEM_PATH."/images/banners/original/".$file_name);
			$thumb->resize(200,200);
			$thumb->save(SYSTEM_PATH.'/images/banners/200X200/'.$file_name);
			$thumb->resize(1600,1600);
			$thumb->save(SYSTEM_PATH.'/images/banners/1600/'.$file_name);
			
		}
		 
		catch (Zend_File_Transfer_Exception $e)
		{
			throw new Exception('Bad data: '.$e->getMessage());
		}

 		$result = $this->banner->addBanner($formData);
		$this->view->msg = $result;
		//clear all form fields 

	$form->reset();

	}
	
	public function bannerListAction(){

	$results = $this->banner->getAllBanners();
       if (count($results) > 0) {
		 $this->Paginator($results);
        } else {
        $this->view->empty_rec = true;
		}
}

	public function editBannerAction(){
	
	$id = $this->_request->getParam('banner_id');
	$form = new Application_Form_AddMainBannerForm();
	$this->view->banner_id = $id;
	
if(isset($id)){
	$this->user_session->banner_id = $id;
}
  
if(isset($id) || isset($this->user_session->banner_id)){
  	$result = $this->banner->getBanner($this->user_session->banner_id);	
	
	//var_dump($result);
	//return;
	$this->view->banner_id = $result->banner_id;
    $form->banner_img->setValue($result->banner_img);
	$form->target_url->setValue($result->target_url);
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
				unlink(SYSTEM_PATH."/images/banners/original/".$result->banner_img);
				unlink(SYSTEM_PATH."/images/banners/200X200/".$result->banner_img);
				unlink(SYSTEM_PATH.'/images/banners/1600/'.$result->banner_img);
				}
				 
			$banner_img = $_FILES['banner_img']['name'];
			$random = rand(10,10000);
			$time = time() + (7 * 24 * 60 * 60);
			$file_name = $time . $random . $banner_img;
			$formData["banner_img"] = $file_name;
	 
			move_uploaded_file($_FILES["banner_img"]['tmp_name'], SYSTEM_PATH."/images/banners/original/".$file_name);
			$thumb = new Application_Model_Thumbnail(SYSTEM_PATH."/images/banners/original/".$file_name);
			$thumb->resize(200,200); 
			$thumb->save(SYSTEM_PATH.'/images/banners/200X200/'.$file_name);
			$thumb->resize(1600,1600);
			$thumb->save(SYSTEM_PATH.'/images/banners/1600/'.$file_name);
		}
		
	catch (Zend_File_Transfer_Exception $e)
		{
			throw new Exception('Bad data: '.$e->getMessage());
		}
}else{

$formData['banner_img']= $this->user_session->banner_img;

}
	
	$formData['banner_id']= $this->user_session->banner_id;

	$result = $this->banner->editBanner($formData);
	$this->_redirect("/admin/banner/banner-list");
	}
	
	public function deleteMainBannerAction()
	{
		
	 $this->_helper->viewRenderer->setNoRender();
     $this->_helper->layout()->disableLayout();
  
		$id = $this->_request->getParam('id');
		$result = $this->banner->getBanner($id);
		unlink(SYSTEM_PATH.'/images/banners/200X200/'.$result->banner_img);
		unlink(SYSTEM_PATH.'/images/banners/originals/'.$result->banner_img);
		unlink(SYSTEM_PATH.'/images/banners/1600/'.$result->banner_img);
		
		$this->banner->removeBanner($this->db, $id);
		$this->_redirect('/admin/banner/banner-list');
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
?>