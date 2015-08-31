<?php
/**
 Author: Musavir Ifitkahr:
 kuala lumpur Malaysia
 */

class Admin_UserController extends Zend_Controller_Action
{
	
    protected $user_session = null;
    protected $db;
    protected $language_id = null;
    protected $filter = null;
    protected $user = null;
    protected $baseurl = '';

    public function init(){
          Zend_Layout::startMvc(
                        array('layoutPath' => APPLICATION_PATH . '/admin/layouts', 'layout' => 'layout')
        );
       
        $this->db = Zend_Db_Table::getDefaultAdapter();
		$this->user = new Application_Model_User();
        $this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
        $this->language_id = Zend_Registry::get('lang_id'); //get the instance of database adapter
        $this->user_session = new Zend_Session_Namespace("user_session"); // default namespace
        $this->filter = new Zend_Filter_StripTags;
		//Zend_Registry::set('lang_id',2);

        ini_set("max_execution_time", 0);
        $auth = Zend_Auth::getInstance();
        //if not loggedin redirect to login page
		if (!$auth->hasIdentity()){
			$this->_redirect('/admin/index/login');;
                }
				
/* if(isset($this->user_session->role_id)){
		
			$role = array('1' => 'Admin','2' => 'Payment Manager','3' => 'Content Manager','4' => 'Listing Manager', '3' => 'Deals Manager' );
				$this->view->user = array(
					'user_id' => $this->user_session->user_id,
					'email' => $this->user_session->email,
					'role_id' => $this->user_session->role_id,
					'role_name'	=> $role[$this->user_session->role_id],
					'user_name'	=>$this->user_session->firstname,
					);
} */

}


    // this is default output function
    public function indexAction() {

        }


public function newAction(){

//if not admin redirect to admin index dash board 
/* if ($this->user_session->role_id != 1){
	$this->_redirect('/admin/index');	
	} */
	
//show message if it is set true 
	if (isset($this->user_session->msg)){
	$this->view->msg = $this->user_session->msg;
	unset($this->user_session->msg);
	}
	
	//$this->adapter = new Zend_File_Transfer_Adapter_Http();
    
	$form = new Application_Form_UserForm();
      $this->view->form = $form;
     
	  if (!$this->_request->isPost()) {
	  	   //$form->role->setValue($this->user_session->selected);
	   	   $this->view->form = $form;
    	   return;
               }
	 $formData = $this->_request->getPost();
					
       if (!$form->isValid($formData)) {
                       $this->view->form = $form;
                       return;
               }
			   
			   $exist = $this->user->checkEmail($formData['email']);
			   if($exist == true){
					$this->view->msg = "<div class='errors'>Email (User ID) already exists. Please make another one .</div>";
					return;
			   }
			$formData['date_added'] = date("Y-m-d");
			$this->view->msg = $this->user->addUser($formData);
			//$this->user_session->selected = $formData['role'];
			$this->user_session->msg = $this->view->msg;
			//$this->_redirect('/admin/user/new');
			//clear all form fields 
			$form->reset();
					}

		
		
public function messagePageAction(){
			$this->view->msg = $this->user_session->msg;
			unset($this->user_session->country);
			unset($this->user_session->state);
			unset($this->user_session->add_more);
			} 


public function listAction(){
	
	$query_string = $this->_request->getParam("query_string");
		$results = null; 
      $query_string = trim($query_string);
	  if($query_string !=''){
		  if(is_string($query_string)){
     $results = $this->user->findUser($query_string);
   } 
   }
	else{
$results = $this->user->getUsers();
	}
       if (count($results) > 0) {
		 $this->Paginator($results);
        } else {
        $this->view->empty_rec = true;
		}
}

// for delete users
public function deleteUsersAction(){
    
	$user_id = $this->_request->getParam('id');
	$delete = $this->user->deleteUsers($user_id);
	$this->_redirect('/admin/user/list/');
		//var_dump($delete);
}


public function findUsersAction(){
	//$this->ajaxed();
	$email = $this->_request->getParam('email');
	$results = $this->user->findUser($email);
	 if (count($results) > 0) {
		 $this->Paginator($results);
        } else {
        $this->view->empty_rec = true;
		}

}
 
public function editAction(){
    
    $form = new Application_Form_UserForm();
    $user_id = $this->_request->getParam('user_id');

    $result = $this->user->getUser($user_id);
	  $form->removeElement("password");		
    $this->view->user_id = $result->user_id;
    $form->email->setValue($result->email);
    $form->user_name->setValue($result->user_name);
	//$form->role->setValue($result->role);
    $this->view->form =  $form;
 
     if (!$this->_request->isPost()) {
			$this->view->form = $form;
			return;
        }
        
        $formData = $this->_request->getPost();
        if (!$form->isValid($formData)) {
			$this->view->form = $form;
			return;
        }
$result = $this->user->updateUser($formData);
$this->_redirect('admin/user/list');
  }
  
       // for chNEGE password user
  		public function updatePasswordAction(){
		$user_id=$this->user_session->user_id;
		$this->view->user_id=$user_id;
	
		$form = new Application_Form_ChangePasswordForm();
		$this->view->form = $form;
		$this->view->msg = "";
		
		if (!$this->_request->isPost()) {
			return;
		}
		$formData = $this->_request->getPost();
		if (!$form->isValid($formData)) {
			return;
		}
		//All business logics will come here
		if(strcmp($formData['pwd_current'],$formData['pwd'] ) == 0){
		$this->view->msg = "<div class='alert alert-danger'>Old and New password are same</div>";
		 $this->view->form = $form;
      		return;	
			}
		
		if(strcmp($formData['pwd'],$formData['pwd_confirm'] ) != 0){
		$this->view->msg = "<div class='alert alert-danger'>Passwords are not matching</div>";
		 $this->view->form = $form;
      		return;	
			}
      //  var_dump($formData);
		if($this->user->passUpdate($user_id, $formData['pwd'])){
				$this->view->msg = "<div class='alert alert-success'>Password successfully Updated</div>";
		}else{
				$this->view->msg = "<div class='alert alert-danger'>Password Update Failed. Try again</div>";
			
			}
		
		}
  
  
  public function changePasswordAction(){
    
    $form = new Application_Form_UserForm();
   $form->removeElement("user_id");
   $form->removeElement("firstname");
   $form->removeElement("lastname");
   $form->removeElement("email");
   
                                
   
    $user = new Application_Model_User();
    $result = $user->getUser();
$form->password->setLabel("New Password");	
    $form->password->setValue($result->password);
    $this->view->form =  $form;
    $formData;
     if (!$this->_request->isPost()) {
			$this->view->form = $form;
			return;
        }
        
        $formData = $this->_request->getPost();
        if (!$form->isValid($formData)) {
			$this->view->form = $form;
			return;
        }
        
   /*if(!$user->currentPass($formData['current_password'])){
      $this->view->msg = "Wrong current password";
     return; 
       }*/  
	   else {
$result =   $user->updatePassword($formData);
$this->view->msg = "Password is updated";
           
       }
  }

 
  public function Paginator($results) {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage(20);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
    }


  /* 
  public function confirmDeleteAction(){
    $user_id = $this->_request->getParam('user_id');
    $firstname = $this->_request->getParam('firstname');
    
	$this->view->firstname = $firstname;
	$this->view->user_id = $user_id;
}
 */
/* public function deleteAction(){
    $user_id = $this->_request->getParam('user_id');
    $user_table = new Application_Model_User();
  
   $flag = $user_table->removeUser($user_id);
  if($flag){ 
   $this->view->user_remove_report = "user has been removed! Successfully";
  }
} */


public function ajaxed() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        if (!$this->_request->isXmlHttpRequest()
            )return; // if not a ajax request leave function

    }
	
      public function __call($method, $args) {
        if ('Action' == substr($method, -6)) {
            // If the action method was not found, forward to the
            // index action
            return $this->_redirect('admin/index');
        }

        // all other methods throw an exception
        throw new Exception('Invalid method "'
                . $method
                . '" called',
                500);
    }
}

