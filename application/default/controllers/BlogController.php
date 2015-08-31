<?php 


class BlogController extends Zend_Controller_Action {
    private $baseurl = '';
	var $user_session = null;
	private $db = null;
    private $results = null;
	private $post = null;
	private $social = null;
	private $comment = null;
	 
    public function init() { 
		$this->_helper->layout->setLayout('layout');
        $this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $this->db = Zend_Db_Table::getDefaultAdapter();
		$this->post = new Application_Model_Posts();
		$this->social = new Application_Model_SocialLinks();
		$this->comment = new Application_Model_PostComments();
		}
		
	public function indexAction(){
	
		//for social links
		$links =  new Application_Model_SocialLinks();
		$this->view->links = $links->getSocialLinks();
			$results= array();
		$query_string = $this->_request->getParam("query_string");
		$query_string = trim($query_string);
		
	   if($query_string !=''){
    	   if(is_string($query_string)){
                $results = $this->post->findPost($query_string);
    	   } 
	   }
	   else
       {
	       $results= $this->post->getAllPosts($this->db);
		      $post_comment= new Application_Model_PostComments();
			   $this->view->comments= $post_comment->getApprovedComments($this->db);
		  } 
		
		if (count($results) > 0) {
		$this->Paginator($results);
        } else {
        $this->view->empty_rec = true;
          }
        	$this->view->list= $this->post->getRecentPosts();
		$this->view->i = 0;
	}
	
	public function postAction(){
		        
        //for social links
		$links =  new Application_Model_SocialLinks();
		$this->view->links = $links->getSocialLinks();
	
		
		$id = $this->_request->getParam('id');
		//$this->view->id = $id;		
		$result = $this->post->getPostByID($id);
		$this->view->id = $result->post_id;
		//var_dump($result);
		$this->view->list = $result;
        
		if($result->is_comment== 0)
        {
    		$results = $this->comment->getCommentsByPost($result->post_id);
    		$this->view->comment = $results; 
    		
            $form = new Application_Form_CommentForm();
    		$this->view->form = $form;
        }
               
			
		//if (!$this->_request->isPost())return;
		//$formData = $this->_request->getPost();
		
		//if (!$form->isValid($formData)) return;
	}
	
    public function savePostCommentsAction()
    {
        $this->ajaxed();
        
       $name = $this->getRequest()->getParam('name');
       $email = $this->getRequest()->getParam('email');
       $comment = $this->getRequest()->getParam('comment');
       $post_id = $this->getRequest()->getParam('post_id');
        
        $data = array('name' => $name,
				'email' => $email,
				'comment' => $comment,
				'post_id' => $post_id,
				'comment_date' => date('Y-m-d h:i:sa')
				); 

		$result = $this->comment->addComment($data);
        
        if($result)
        {
            echo 'success';
            //$this->view->msg = "<div class='alert alert-success'>Comments Added Successfully!</div>" ;    
        }
        else{
            echo 'error';
            //$this->view->msg = "<div class='alert alert-danger'>Some error in saving record</div>";
        }       
    }
    
	  // post not found page
	public function postNotFoundAction(){
		//for social links
		$links =  new Application_Model_SocialLinks();
		$this->view->links = $links->getSocialLinks();
	}
	
	public function Paginator($results) {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage(3);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
    }
	
	public function pageAction(){
		
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
    
	public function __call($method,$args)
	{
		//for social links
		$links =  new Application_Model_SocialLinks();
		$this->view->links = $links->getSocialLinks();
 
  if ('Action' == substr($method, -6)) {
            // If the action method was not found, render the error
            // template
            $page =  substr($method,0, -6);
            //is this page name is present in any url slug

		$results = $this->post->getPostByUrl($page);
		
           if($results == true){

		$this->view->page_data = $results;
		if($results->is_comment== 0){
		$result = $this->comment->getCommentsByPost($results->post_id);
		$this->view->comment = $result;
		$form = new Application_Form_CommentForm();
		$this->view->form = $form;
		}
		
        return $this->render('page');
	}else{
            return $this->render('post-not-found');
        }
        }

        // all other methods throw an exception
        throw new Exception('Invalid method "'
                            . $method
                            . '" called',
                            500);
}

	
	/*public function __call($method, $args) {
        if ('Action' == substr($method, -6)) {
            // If the action method was not found, forward to the
            // index action
            return $this->_forward('post-not-found');
        }

        // all other methods throw an exception
        throw new Exception('Invalid method "'
                . $method
                . '" called',
                500);
    }*/
		
		
		
		
		}
		