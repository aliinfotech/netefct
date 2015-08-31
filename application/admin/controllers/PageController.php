<?php

class Admin_PageController extends Zend_Controller_Action
{
	    protected $user_session = null;
        private $db = null;
        private $baseurl = null;
        private $authAdapter = null;
		private $post = null;
		private $page = null;
		private $url = null;
		
	public function init(){
		Zend_Layout::startMvc(
		array('layoutPath'=>  APPLICATION_PATH . '/admin/layouts',  'layout' => 'layout'));
		$this->db = Zend_Db_Table::getDefaultAdapter();
        $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
		$this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
		$this->user_session = new Zend_Session_Namespace("user_session");
				
		ini_set("max_execution_time",(60*300));
		$this->post = new Application_Model_Posts();
		$this->page = new Application_Model_Pages();
		$this->url = new Application_Model_Urls();
		
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

	
	//for new page
	
	public function newPageAction() 
	{
		$form = new Application_Form_NewPageForm();
		$this->view->form = $form;
		$results = $this->url->getUrls();
		$this->view->page_url= $results->page_url;
		if($this->user_session->msg!=null)
		{
			$this->view->msg = $this->user_session->msg;
			$this->user_session->msg = null;
		}
		
		if (!$this->_request->isPost())return;
		$formData = $this->_request->getPost();
		
		if (!$form->isValid($formData)) return;
		
		$formData['is_in_draft'] = $formData['submit'];
		 
		if($formData['submit'] == "1" ){ 
			 
			$formData['is_in_draft'] = 1;
		
		//For Images
		$file_name = NULL;
		 try {
			$image = $_FILES['image']['name'];
			$random = rand(10,10000);
			$time = time() + (7 * 24 * 60 * 60);
			$file_name = $time . $random . $image;
			$formData["image"] = $file_name;
	 
			move_uploaded_file($_FILES["image"]['tmp_name'], SYSTEM_PATH."/images/pages/original/".$file_name);
			$thumb = new Application_Model_Thumbnail(SYSTEM_PATH."/images/pages/original/".$file_name);
			$thumb->resize(500,500);
			$thumb->save(SYSTEM_PATH.'/images/pages/500X500/'.$file_name);
			$thumb->resize(800,800);
			$thumb->save(SYSTEM_PATH.'/images/pages/800/'.$file_name);
			
		} 
		 
		catch (Zend_File_Transfer_Exception $e)
		{
			throw new Exception('Bad data: '.$e->getMessage());
		}
		
		$formData['user_id']= $this->user_session->user_id;
		$formData['date_created']= date("Y-m-d h:i:sa");
		$formData['date_published']= date("Y-m-d h:i:sa");
		
		$slug= $formData['url_slug'];
		$formData['url_slug']= str_replace("-","", $slug);
		
		//check from database if the slug is already in db  
		$data = array ("url"=>$formData['url_slug']);
		$data["url"]=$formData['url_slug'];
			
		 if($this->page->checkPageSlug($data)){
		 $this->view->msg =  "<div class='alert alert-danger'>Url Slug Is Already Exist. Please change to another.</div>";
		 return;
		 }
		
 		$result = $this->page->addDraftPage($formData);
		$this->view->msg = $result;
		}
		
		elseif($formData['submit'] == "0" ){
			$formData['is_in_draft'] = 0;
			//For Images
		$file_name = NULL;
		 try {  
			$image = $_FILES['image']['name'];
			$random = rand(10,10000);
			$time = time() + (7 * 24 * 60 * 60);
			$file_name = $time . $random . $image;
			$formData["image"] = $file_name;
	 
			move_uploaded_file($_FILES["image"]['tmp_name'], SYSTEM_PATH."/images/pages/original/".$file_name);
			$thumb = new Application_Model_Thumbnail(SYSTEM_PATH."/images/pages/original/".$file_name);
			$thumb->resize(500,500);
			$thumb->save(SYSTEM_PATH.'/images/pages/500X500/'.$file_name);
			$thumb->resize(800,800);
			$thumb->save(SYSTEM_PATH.'/images/pages/800/'.$file_name);
			
		}
		 
		catch (Zend_File_Transfer_Exception $e)
		{
			throw new Exception('Bad data: '.$e->getMessage());
		}
		
		$formData['user_id']= $this->user_session->user_id;
		$formData['date_created']= date("Y-m-d h:i:sa");
		$formData['date_published']= date("Y-m-d h:i:sa");
		$slug= $formData['url_slug'];
		$formData['url_slug']= str_replace("-","", $slug);
		
		//check from database if the slug is already in db  
		$data = array ("url"=>$formData['url_slug']);
		$data["url"]=$formData['url_slug'];
			
		 if($this->page->checkPageSlug($data)){
			$this->view->msg =  "<div class='alert alert-danger'>Url Slug Is Already Exist. Please change to another.</div>";
			return;
			} 
		
 		$result = $this->page->addPage($formData);
		$this->view->msg = $result;
		}
		
		//clear all form fields 

	$form->reset();

	}
	
	//for post list
	public function listsAction(){ 
   
	  //page list form
      $form = new Application_Form_FilterPagesForm();
      $this->view->form = $form;
	   
	   // main condition
	  if($this->_request->isPost()){$query_string = $this->_request->getParam("query_string");
	   
      $query_string = trim($query_string);
	  	  
	    // Post and validation section
        if (!$this->_request->isPost())
            return;
        $formData = $this->_request->getPost();
        
	   $results = array();	
	   
	  if($query_string !=''){
	  if(is_string($query_string)){
      $results = $this->page->findPage($query_string);
	  } 
	  }
	 
       else if($formData['search_type']==1)
        {
            $results = $this->page->getAllPages($this->db);    
        }
        
       else if($formData['search_type']==2)
        {
            $results = $this->page->getAllDraftPages($this->db);    
        }
	}
	
	else{
    $results = $this->page->getAllPages($this->db);
	}		
       if (count($results) > 0) {
	   $this->Paginator($results);
       } else {
       $this->view->empty_rec = true;
     	}
   }
	
	// for edit post
   public function editAction(){
				
	$id = $this->_request->getParam('page_id');
	$form = new Application_Form_NewPageForm();
		$results = $this->url->getUrls();
		$this->view->page_url= $results->page_url;
	$this->view->page_id = $id;
	
if(isset($id)){
	$this->user_session->page_id = $id;
}
  
if(isset($id) || isset($this->user_session->page_id)){
  	$result = $this->page->getPageByID($this->user_session->page_id);	
	
	//var_dump($result);
	//return;
	$this->view->page_id = $result->page_id;
	$form->title->setValue($result->title);
	$form->url_slug->setValue($result->url_slug);
    $form->image->setValue($result->image);
	$form->description->setValue($result->description);
	$form->submit->setLabel("Update");
	
	
    $this->view->url_slug = $result->url_slug;
    $this->user_session->image = $result->image; 
    $this->view->image = $result->image; 

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
	
    $image_name= $_FILES["image"]["name"];

    if(isset($image_name) && strlen($image_name) > 0 ) {
	
	try {
				if(isset($this->user_session->image)){
				unlink(SYSTEM_PATH."/images/pages/original/".$result->image);
				unlink(SYSTEM_PATH."/images/pages/500X500/".$result->image);
				unlink(SYSTEM_PATH.'/images/pages/800/'.$result->image);
				}
				 
			$image = $_FILES['image']['name'];
			$random = rand(10,10000);
			$time = time() + (7 * 24 * 60 * 60);
			$file_name = $time . $random . $image;
			$formData["image"] = $file_name;
	 
			move_uploaded_file($_FILES["image"]['tmp_name'], SYSTEM_PATH."/images/pages/original/".$file_name);
			$thumb = new Application_Model_Thumbnail(SYSTEM_PATH."/images/pages/original/".$file_name);
			$thumb->resize(500,500);
			$thumb->save(SYSTEM_PATH.'/images/pages/500X500/'.$file_name);
			$thumb->resize(800,800);
			$thumb->save(SYSTEM_PATH.'/images/posts/800/'.$file_name);
			
		}
		
	catch (Zend_File_Transfer_Exception $e)
		{
			throw new Exception('Bad data: '.$e->getMessage());
		}
}else{

$formData['image']= $this->user_session->image;

}
	
	$formData['page_id']= $this->user_session->page_id;
	
	$formData['is_in_draft'] = $formData['submit'];
	
	$slug= $formData['url_slug'];
	$formData['url_slug']= str_replace("-","", $slug);
	
	/*check from database if the slug is already in db  
	$data = array ("url"=>$formData["url_slug"]);
	$data["url"]=$formData["url_slug"];
			
	 if($this->page->checkPageSlug($data)){
	 $this->view->msg =  "<div class='alert alert-danger'>Url Slug Is Already Exist. Please change to another.</div>";
		 return;
		} */
 			 
		if($formData['submit'] == "0" ){ 
			 
			$formData['is_in_draft'] = 0;
	
	$result = $this->page->updatePage($formData);
	$this->view->msg = $result;
	//$this->_redirect("/admin/page/lists");
	}
	else if($formData['submit'] == "1"){
		$formData['is_in_draft'] = 1;
    $result = $this->page->updateDraftPage($formData);
	$this->view->msg = $result;
	//$this->_redirect("/admin/page/lists");
	}
	}
		
	// delete post
	public function deletePageAction()
	{
		
	 $this->_helper->viewRenderer->setNoRender();
     $this->_helper->layout()->disableLayout();
  
		$id = $this->_request->getParam('id');
		$result = $this->page->getPageByID($id);
		unlink(SYSTEM_PATH.'/images/pages/500X500/'.$result->image);
		unlink(SYSTEM_PATH.'/images/pages/original/'.$result->image);
		unlink(SYSTEM_PATH.'/images/pages/800/'.$result->image);
		
		$this->post->removePost($this->db, $id);
		$this->_redirect('/admin/page/pages-list');
	}
	
	
	// Paginator action
	public function Paginator($results) {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
    }
	
	 public function __call($method, $args) {
        if ('Action' == substr($method, -6)) {
            // If the action method was not found, forward to the
            // index action
            return $this->_forward('admin/index');
        }

        // all other methods throw an exception
        throw new Exception('Invalid method "'
                . $method
                . '" called',
                500);
    }
	
}
?>