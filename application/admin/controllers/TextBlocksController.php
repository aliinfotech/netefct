 <?php

class Admin_TextBlocksController extends Zend_Controller_Action
{
	    protected $user_session = null;
        private $db = null;
        private $baseurl = null;
        private $authAdapter = null;
		private $textBlock = null;
		
	public function init(){ 
		Zend_Layout::startMvc(
		array('layoutPath'=>  APPLICATION_PATH . '/admin/layouts',  'layout' => 'layout'));
		$this->db = Zend_Db_Table::getDefaultAdapter();
        $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
		$this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
		$this->user_session = new Zend_Session_Namespace("user_session");
		
		ini_set("max_execution_time",(60*300));
		$this->textBlock = new Application_Model_TextBlocks();
		
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


	
	public function textBlocksListAction(){

	if(isset($this->user_session->msg)){
	$this->view->msg = $this->user_session->msg;
	unset($this->user_session->msg);
	}
	
	$results = $this->textBlock->getAllTextBlocks();
       if (count($results) > 0) {
		 $this->Paginator($results);
        } else {
        $this->view->empty_rec = true;
		}
}

	public function editTextBlockAction(){
	
	$id = $this->_request->getParam('tb_id');
	$form = new Application_Form_TextBlockForm();
	$this->view->tb_id = $id;
	
if(isset($id)){
	$this->user_session->tb_id = $id;
}
  
if(isset($id) || isset($this->user_session->tb_id)){
  	$result = $this->textBlock->getTextBlock($this->user_session->tb_id);	
	
	//var_dump($result);
	//return;
	$this->view->tb_id = $result->tb_id;
    $form->tb_name->setValue($result->tb_name);
	$form->tb_text->setValue($result->tb_text);
	//$form->message->setValue($result->message);
	
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

	
	$formData['tb_id']= $this->user_session->tb_id;
 
	$result = $this->textBlock->editTextBlock($formData);
	$this->view->msg = $result;
	$this->_redirect("/admin/text-blocks/text-blocks-list");
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