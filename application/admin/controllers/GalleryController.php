<?php

class Admin_GalleryController extends Zend_Controller_Action
{
	
    protected $user_session = null;
    protected $db;
    protected $baseurl = '';
	protected $photos = null;

    public function init(){
          Zend_Layout::startMvc(
                        array('layoutPath' => APPLICATION_PATH . '/admin/layouts', 'layout' => 'layout')
        );
        $this->db = Zend_Db_Table::getDefaultAdapter();
	    $this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
        $this->user_session = new Zend_Session_Namespace("user_session"); // default namespace
		$this->photos = new Application_Model_Photos(); 
		$auth = Zend_Auth::getInstance();
        //if not loggedin redirect to login page
		if (!$auth->hasIdentity()){
			$this->_redirect('/admin/index/login');;
                }
				
}


    // this is default output function
    public function indexAction() {

    	$results = $this->photos->getAllPhotos();
       if (count($results) > 0) {
		 $this->Paginator($results);
        } else {
        $this->view->empty_rec = true;
		}
		
        }
		
	
	//new for add new photo code
	
	public function newPhotoAction() 
	{
		$form = new Application_Form_GalleryForm();
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
			$photo_name = $_FILES['photo_name']['name'];
			$random = rand(10,10000);
			$file_name = $random . $photo_name;
			$formData["photo_name"] = $file_name;
	 
			move_uploaded_file($_FILES["photo_name"]['tmp_name'], SYSTEM_PATH."/images/gallery-images/originals/".$file_name);
			$thumb = new Application_Model_Thumbnail(SYSTEM_PATH."/images/gallery-images/originals/".$file_name);
			$thumb->resize(500,500);
			$thumb->save(SYSTEM_PATH.'/images/gallery-images/500X500/'.$file_name);
			$thumb->resize(200,200);
			$thumb->save(SYSTEM_PATH.'/images/gallery-images/200X200/'.$file_name);
			
		} 
		 
		catch (Zend_File_Transfer_Exception $e)
		{
			throw new Exception('Bad data: '.$e->getMessage());
		}

 		$result = $this->photos->addPhoto($formData);
		$this->view->msg = $result;
		//clear all form fields 

	$form->reset();

	}
	
	public function photoListAction(){

	$results = $this->photos->getAllPhotos();
       if (count($results) > 0) {
		 $this->Paginator($results);
        } else {
        $this->view->empty_rec = true;
		}
}
	
	
	// edit photo gallery 
	public function editPhotoAction(){
	 
	$id = $this->_request->getParam('photo_id');
	$form = new Application_Form_GalleryForm();
	$this->view->photo_id = $id;
	
if(isset($id)){
	$this->user_session->photo_id = $id;
}
  
if(isset($id) || isset($this->user_session->photo_id)){
  	$result = $this->photos->getPhoto($this->user_session->photo_id);	
	
	//var_dump($result);
	//return;
	$this->view->photo_id = $result->photo_id;
	$this->view->photo_name = $result->photo_name;
    $form->photo_name->setValue($result->photo_name);
	
    $this->user_session->photo_name = $result->photo_name; 

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
	
$photo_name= $_FILES["photo_name"]["name"];

    if(isset($photo_name) && strlen($photo_name) > 0 ) {
	
	try {
				if(isset($this->user_session->photo_name)){
				unlink(SYSTEM_PATH."/images/gallery-images/originals/".$result->photo_name);
				unlink(SYSTEM_PATH."/images/gallery-images/200X200/".$result->photo_name);
				unlink(SYSTEM_PATH."/images/gallery-images/500X500/".$result->photo_name);
				}
				 
			$photo_name = $_FILES['photo_name']['name'];
			$random = rand(10,10000);
			$time = time() + (7 * 24 * 60 * 60);
			$file_name = $time . $random . $photo_name;
			$formData["photo_name"] = $file_name;
	 
			move_uploaded_file($_FILES["photo_name"]['tmp_name'], SYSTEM_PATH."/images/gallery-images/originals/".$file_name);
			$thumb = new Application_Model_Thumbnail(SYSTEM_PATH."/images/gallery-images/originals/".$file_name);
			$thumb->resize(500,500); 
			$thumb->save(SYSTEM_PATH.'/images/gallery-images/500X500/'.$file_name);
			$thumb->resize(200,200);
			$thumb->save(SYSTEM_PATH.'/images/gallery-images/200X200/'.$file_name);
		}
		
	catch (Zend_File_Transfer_Exception $e) 
		{
			throw new Exception('Bad data: '.$e->getMessage());
		}
}else{

$formData['photo_name']= $this->user_session->photo_name;

}
	
	$formData['photo_id']= $this->user_session->photo_id;

	$result = $this->photos->editPhoto($formData);
	$this->_redirect("/admin/gallery/photo-list");
	}
	
	//delete photo image
	public function deletePhotoAction()
	{
		$photo_id = $this->_request->getParam('id'); 
		 
		$result = $this->photos->getPhoto($photo_id);
		unlink(SYSTEM_PATH.'/images/gallery-images/200X200/'.$result->photo_name);
		unlink(SYSTEM_PATH.'/images/gallery-images/500X500/'.$result->photo_name);
		unlink(SYSTEM_PATH.'/images/gallery-images/originals/'.$result->photo_name);
		
		$delete = $this->photos->removeImage($photo_id);
		$this->user_session->msg  = $delete; 
		$this->_redirect("/admin/gallery/photo-list"); 
	}

		
			// Paginator action
	public function Paginator($results) {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage(100);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
    }
		
		 public function __call($method, $args) {
        if ('Action' == substr($method, -6)) {
            // If the action method was not found, forward to the
            // index action
            return $this->_forward('index');
        }

        // all other methods throw an exception
        throw new Exception('Invalid method "'
                . $method
                . '" called',
                500); 
    }
		
}
?>