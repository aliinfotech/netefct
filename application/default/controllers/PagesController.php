<?php 


class PagesController extends Zend_Controller_Action {
    private $baseurl = '';
	var $user_session = null;
	private $db = null;
    private $results = null;
	private $page = null;
	private $social = null;
	private $comment = null;
	 
    public function init() { 
		$this->_helper->layout->setLayout('layout');
        $this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $this->db = Zend_Db_Table::getDefaultAdapter();
		$this->comment = new Application_Model_PageComments();
		$this->page = new Application_Model_Pages();
		$this->social = new Application_Model_SocialLinks();
		}
		
	public function indexAction(){
		
	}
		
	public function pageAction(){
		
		//for social links
	$links =  new Application_Model_SocialLinks();
	$this->view->links = $links->getSocialLinks();
		
		$results= $this->page->getLastInsertedRecord();
		$result = $this->comment->getCommentsByPage($results->page_id);
		$this->view->list = $results;
		$this->view->comment = $result; 
		//var_dump($result);
		
		$form = new Application_Form_CommentForm();
		$this->view->form = $form;
				
		
		
		
		/*if (count($results) > 0) {
		$this->Paginator($results);
        } else {
        $this->view->empty_rec = true;
  }
	$this->view->list= $this->post->getRecentPosts();
		$this->view->i = 0;*/
  
		//var_dump($this->post->getAllPosts());
	}
	
	public function savePageCommentsAction()
    {
        $this->ajaxed();
        
       $name = $this->getRequest()->getParam('name');
       $email = $this->getRequest()->getParam('email');
       $comment = $this->getRequest()->getParam('comment');
       $page_id = $this->getRequest()->getParam('page_id');
        
        $data = array('name' => $name,
				'email' => $email,
				'comment' => $comment,
				'page_id' => $page_id,
				'comment_date' => date('Y-m-d h:i:sa')
				); 

		$result = $this->comment->addComment($data);
        
        if($result)
        {
            echo 'success';
        }
        else{
            echo 'error';
        }       
    }
	
	
		  // page not found page
	public function pageNotFoundAction(){
		//for social links
		$links =  new Application_Model_SocialLinks();
		$this->view->links = $links->getSocialLinks();
	}
	
		public function pageBySlugAction(){
			}
	
	 public function ajaxed() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        
        if (!$this->_request->isXmlHttpRequest()){
		  $this->_redirect('index');	
			return; // if not a ajax request leave function
		}
    }
	
	public function Paginator($results) {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage(3);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
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

		$results = $this->page->getPageByUrl($page);
		
           if($results == true){

		$this->view->page_data = $results;
		$result = $this->comment->getCommentsByPage($results->page_id);
		$this->view->comment = $result;
		$form = new Application_Form_CommentForm();
		$this->view->form = $form;

        return $this->render('page-by-slug');
	}else{
            return $this->render('page-not-found');
        }
        }

        // all other methods throw an exception
        throw new Exception('Invalid method "'
                            . $method
                            . '" called',
                            500);
}
		
		
		
		
		}
		