<?php

class Admin_CategoriesController extends Zend_Controller_Action
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
		$this->category = new Application_Model_Category();
		
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
	
	public function newCategoryAction() 
	{
		$form = new Application_Form_CategoryForm();
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
     	$data = array ("category"=>$formData["category"]);
		$data["category"]=$formData["category"];
	
     	if($this->category->checkCategoryName($data['category'])){
			$this->view->msg =  "<div class='alert alert-danger'>Name Is Already Exist</div>";
			return;
			} 
		$result = $this->category->addCategory($formData);
		$this->view->msg = $result;
		//clear all form fields 

	$form->reset();
	}
	
	
	// for show list
	public function listAction(){ 

	$results = $this->category->getAllCategories();
       if (count($results) > 0) {
		 $this->Paginator($results);
        } else {
        $this->view->empty_rec = true;
		}
   }
  
   // for edit category
   public function editAction(){
				
		$id = $this->_request->getParam('id');
		$form = new Application_Form_CategoryForm();
		$record = $this->category->getCategoryByID($id); 
		$form->category->setValue($record->category);	
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
//check from database if the name is already in record 
	$data = array ("category"=>$formData["category"]);
	$data["category"]=$formData["category"];
			
		 if($this->category->checkCategoryName($data)){
			$this->view->msg =  "<div class='alert alert-danger'>Category Name Is Already Exist</div>";
			return;
			} 
			$data["id"]=$id;
	$this->view->msg = $this->category->updateCategory($data);
	}

	     // for delete category
		public function deleteCategoryAction(){
		 $id = $this->_request->getParam('id');
		  // Because of following code we don't need a phtml file 
		  $this->_helper->viewRenderer->setNoRender();
		  $this->_helper->layout()->disableLayout();
	     if($this->category->deleteCategory($id)){
		 $this->_redirect("/admin/categories/list");					
				} 
		}
	
	 	
	public function Paginator($results) {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage(20);
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