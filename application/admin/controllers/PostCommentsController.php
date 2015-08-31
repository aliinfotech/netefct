<?php

class Admin_PostCommentsController extends Zend_Controller_Action
{
	    protected $user_session = null;
        private $db = null;
        private $baseurl = null;
        private $authAdapter = null;
		private $post = null;
		private $comments = null;
		
	public function init(){
		Zend_Layout::startMvc(
		array('layoutPath'=>  APPLICATION_PATH . '/admin/layouts',  'layout' => 'layout'));
		$this->db = Zend_Db_Table::getDefaultAdapter();
        $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
		$this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
		$this->user_session = new Zend_Session_Namespace("user_session");
				
		ini_set("max_execution_time",(60*300));
		$this->post = new Application_Model_Posts();
		$this->comments = new Application_Model_PostComments();
		
		if(!isset($this->user_session->user_id)){
			$this->_redirect("/admin/login/admin-login");			
		}
		$auth = Zend_Auth::getInstance();
		//if not loggedin redirect to login page
		if (!$auth->hasIdentity()){
		$this->_redirect('/admin/login/admin-login');
        }
	}

	
	public function indexAction(){
}

	public function postListAction(){
		$form = new Application_Form_FilterCommentForm();
      $this->view->form = $form;
	 
	 if($this->_request->isPost()){
		  // Post and validation section
        if (!$this->_request->isPost())
            return;
        $formData = $this->_request->getPost();
        
	   $results = array();	
	   
	 
     if($formData['search_type']==1)
        {
            $results = $this->comments->getPendingComments($this->db);   
        }
        
       else if($formData['search_type']==2)
        {
            $results = $this->comments->getApprovedComments($this->db);    
        }
		else if($formData['search_type']==3)
        {
			$results = $this->comments->getRejectedComments($this->db);
        }
	}
	
	else{
   		$results = $this->comments->getAllComments($this->db);
	}
		
	
       if (count($results) > 0) {
		 $this->Paginator($results);
        } else {
        $this->view->empty_rec = true;
		}
		
	}
	
	public function deleteCommentAction(){
		 $id = $this->_request->getParam('id');
		  // Because of following code we don't need a phtml file 
		  $this->_helper->viewRenderer->setNoRender();
		  $this->_helper->layout()->disableLayout();
	     if($this->comments->deleteComment($id)){
		 $this->_redirect("/admin/post-comments/post-list");					
				} 
		}
		
	public function approveCommentAction(){
		 $id = $this->_request->getParam('id');
		  // Because of following code we don't need a phtml file 
		  $this->_helper->viewRenderer->setNoRender();
		  $this->_helper->layout()->disableLayout();
	     if($this->comments->approveComment($this->db, $id)){
		 $this->_redirect("/admin/post-comments/post-list");					
				} 
		}
		
	public function rejectCommentAction(){
		 $id = $this->_request->getParam('id');
		  // Because of following code we don't need a phtml file 
		  $this->_helper->viewRenderer->setNoRender();
		  $this->_helper->layout()->disableLayout();
	     if($this->comments->rejectComment($this->db, $id)){
		 $this->_redirect("/admin/post-comments/post-list");					
				} 
		}
		
	public function editCommentAction(){
		
		$form = new Application_Form_CommentForm();
		$id = $this->_request->getParam('id');
		$result = $this->comments->getComment($id);
		
		
		$this->view->pc_id = $result->pc_id;
		$form->name->setValue($result->name);
		$form->email->setValue($result->email);
		$form->comment->setValue($result->comment);
		$this->view->form = $form; 
		
		if (!$this->_request->isPost())return;
		$formData = $this->_request->getPost();
		
		if (!$form->isValid($formData)) return;
		
		$results = $this->comments->updateComment($this->db, $formData);
		$this->view->msg = $results;
	}

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