<?php

class Admin_PhotoCategoriesController extends Zend_Controller_Action
{
	    protected $user_session = null;
        private $db = null;
        private $baseurl = null;
        private $authAdapter = null;
		private $category = null;
		
	public function init(){  
		Zend_Layout::startMvc(
		array('layoutPath'=>  APPLICATION_PATH . '/admin/layouts',  'layout' => 'layout'));
		$this->db = Zend_Db_Table::getDefaultAdapter();
        $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
		$this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
		$this->user_session = new Zend_Session_Namespace("user_session");
				
		ini_set("max_execution_time",(60*300));
		$this->category = new Application_Model_PGCategory();
		
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

	$results = $this->category->getAllCategories();
       if (count($results) > 0) {
         $this->Paginator($results, 10);
        } else {
        $this->view->empty_rec = true;
        }
 } 
	//new for add new photo code
	
	public function newAction()   
	{
		$form = new Application_Form_PhotoCategoryForm();
		$this->view->form = $form;
		if($this->user_session->msg!=null)
		{
			$this->view->msg = $this->user_session->msg;
			$this->user_session->msg = null;
		}
		
		if (!$this->_request->isPost())return;
		$formData = $this->_request->getPost();
		
		if (!$form->isValid($formData)) return;
		
		//check from database if the name is already in record 
     	$data = array ("category"=>$formData["category_name"]);
		$data["category"]=$formData["category_name"];
	
     	if($this->category->checkCategoryName($data['category'])){
			$this->view->msg =  "<div class='alert alert-danger'>Name Is Already Exist</div>";
			return;
			} 

		//For Images
		$file_name = NULL;
		 try {
			$banner = $_FILES['banner']['name'];
			$random = rand(9,99999);
			$file_name = $random . $banner;
			$formData["banner"] = $file_name;
	 
			move_uploaded_file($_FILES["banner"]['tmp_name'], SYSTEM_PATH."/images/user/photo_gallery/categories/".$file_name);
			$thumb = new Application_Model_Thumbnail(SYSTEM_PATH."/images/user/photo_gallery/categories/".$file_name);
			$thumb->resize(500,500);
			$thumb->save(SYSTEM_PATH.'/images/user/photo_gallery/categories/500X500/'.$file_name);
			$thumb->resize(200,200);
			$thumb->save(SYSTEM_PATH.'/images/user/photo_gallery/categories/200X200/'.$file_name);
			
		} 
		 
		catch (Zend_File_Transfer_Exception $e)
		{
			throw new Exception('Bad data: '.$e->getMessage());
		}

 		$result = $this->category->addPhotoCategory($formData);
		$this->view->msg = $result;
		//clear all form fields 

	$form->reset();
	}
	
   // for edit category
   public function editAction(){
				
		$id = $this->_request->getParam('id');
		if(!isset($id)) $this->_redirect('admin/photo-categories/index');
		$form = new Application_Form_PhotoCategoryForm();
		$result = $this->category->getCategoryByID($id);
		$this->view->id = $result->pg_cat_id;
   		$form->banner->setValue($result->banner);
   		$this->view->banner = $result->banner; 
		$form->category_name->setValue($result->category_name);
		$this->view->name = $result->category_name;	
		$form->submit->setLabel("Update");	
		
		$this->view->form = $form;
		
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
    $image_name= $_FILES["banner"]["name"];

    if(isset($image_name) && strlen($image_name) > 0 ) {

    try {
               if(isset($result->banner)){

$image_file = SYSTEM_PATH."/images/user/photo_gallery/categories/500X500/".$result->banner;

if (file_exists($image_file)) {
           unlink(SYSTEM_PATH."/images/user/photo_gallery/categories/".$result->banner);
     }

if (file_exists($image_file)) {
           unlink(SYSTEM_PATH."/images/user/photo_gallery/categories/500X500/".$result->banner);
     }
if (file_exists($image_file)) {
           unlink(SYSTEM_PATH."/images/user/photo_gallery/categories/200X200/".$result->banner);
     }
 }
            $banner = $_FILES['banner']['name'];
            $random = rand(9,999999);
            $file_name = $random . $banner;
            $formData["banner"] = $file_name;

            move_uploaded_file($_FILES["banner"]['tmp_name'], SYSTEM_PATH."images/user/photo_gallery/categories/".$file_name);
            $thumb = new Application_Model_Thumbnail(SYSTEM_PATH."images/user/photo_gallery/categories/".$file_name);
            $thumb->resize(500,500);
            $thumb->save(SYSTEM_PATH."images/user/photo_gallery/categories/500X500/".$file_name);
            $thumb->resize(200,200);
            $thumb->save(SYSTEM_PATH."images/user/photo_gallery/categories/200X200/".$file_name);


        }

    catch (Zend_File_Transfer_Exception $e)
        {
            throw new Exception('Bad data: '.$e->getMessage());
        }
}else{

$formData['banner']=  $result->banner;
}

    $formData['pg_cat_id']= $id;

	$result = $this->category->editCategory($formData);
    $this->view->msg = $result;
    $this->_redirect("/admin/photo-categories/edit/id/".$id);
	}

	     // for delete category
		public function deleteCategoryAction(){
		$id = $this->_request->getParam('id');
		$result = $this->category->getCategoryByID($id);
		unlink(SYSTEM_PATH.'/images/user/photo_gallery/categories/200X200/'.$result->banner);
		unlink(SYSTEM_PATH.'/images/user/photo_gallery/categories/'.$result->banner);
		unlink(SYSTEM_PATH.'/images/user/photo_gallery/categories/500X500/'.$result->banner);
		
		$this->category->deleteCategory($this->db, $id);
		$this->_redirect('/admin/photo-categories');
		}
	
	 	
		// Paginator action
  	public function Paginator($results, $records) {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage($records);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
    }
	  
//this function is used for every function that recieves a ajax call
    public function ajaxed() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        if (!$this->_request->isXmlHttpRequest()){
		$this->_redirect('index');	
			return; // if not a ajax request leave function
		}
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