<?php

class Admin_PostsController extends Zend_Controller_Action
{
	    protected $user_session = null;
        private $db = null;
        private $baseurl = null;
        private $authAdapter = null;
		private $post = null;
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

	public function newPostAction()
	{
		$form = new Application_Form_PostForm();
		$this->view->form = $form;
		$results = $this->url->getUrls();
		$this->view->post_url= $results->post_url;
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
			$image = $_FILES['image']['name'];
			$random = rand(10,10000);
			$time = time() + (7 * 24 * 60 * 60);
			$file_name = $time . $random . $image;
			$formData["image"] = $file_name;

			move_uploaded_file($_FILES["image"]['tmp_name'], SYSTEM_PATH."/images/user/posts/".$file_name);
			$thumb = new Application_Model_Thumbnail(SYSTEM_PATH."/images/user/posts/".$file_name);
			$thumb->resize(500,500);
			$thumb->save(SYSTEM_PATH.'/images/user/posts/500X500/'.$file_name);
			$thumb->resize(200,200);
			$thumb->save(SYSTEM_PATH.'/images/user/posts/200X200/'.$file_name);

		}
		catch (Zend_File_Transfer_Exception $e)
		{
			throw new Exception('Bad data: '.$e->getMessage());
		}
        
		$formData['user_id']= $this->user_session->user_id;
		$formData['date_created']= date("Y-m-d H:i:s");
		$formData['date_published']= date("Y-m-d H:i:s");
		//$slug= $formData['url'];
		//$formData['url']= str_replace("-","", $slug);

		//check from database if the slug is already in db
		$data = array ("url"=>$formData['url']);
		$data["url"]=$formData['url'];

		 if($this->post->checkPostSlug($data)){
		 $this->view->msg =  "<div class='alert alert-danger'>Url Slug Is Already Exist. Please change to another.</div>";
		 return;
		 }

        if($formData['submit'] == "0" )
         {
            $formData['is_in_draft'] = 0;
            $formData['draft_content'] = $formData['description'];
         }
         else
         {
            $formData['is_in_draft'] = 1;
            $formData['draft_content'] = $formData['description'];
            $formData['description'] = "";
         }
         
        $result = $this->post->addPost($formData);		
		$this->view->msg = $result;

		//clear all form fields

	   $form->reset();
	}

	//for post list
	public function indexAction(){

	  //post list form
     // $form = new Application_Form_FilterPostsForm();
     // $this->view->form = $form;

	   // main condition
	/*  if($this->_request->isPost())
      {
        $query_string = $this->_request->getParam("query_string");

      $query_string = trim($query_string);

	    // Post and validation section
        if (!$this->_request->isPost())
            return;
        $formData = $this->_request->getPost();

	   $results = array();

	  if($query_string !=''){
	  if(is_string($query_string)){
      $results = $this->post->findPost($query_string);
	  }
	  }

       else if($formData['search_type']==1)
        {
            $results = $this->post->getAllPosts($this->db);
        }

       else if($formData['search_type']==2)
        {
            $results = $this->post->getAllDraftPosts($this->db);
        }
	}

	else{ */
        $results = $this->post->getAllPosts($this->db);
        $this->view->data = $results;
	/*}
       if (count($results) > 0) {
	   $this->Paginator($results);
       } else {
       $this->view->empty_rec = true;
     	}*/
   }

    // for edit post
   public function editPostAction(){

	$id = $this->_request->getParam('post_id');
	$form = new Application_Form_PostForm();
	$results = $this->url->getUrls();
	$this->view->post_url= $results->post_url;
	$this->view->post_id = $id;

if(isset($id)){
	$this->user_session->post_id = $id;
}

if(isset($id) || isset($this->user_session->post_id)){
  	$result = $this->post->getPostByID($this->user_session->post_id);

	//var_dump($result);
	//return;
	$this->view->post_id = $result->post_id;
	$form->heading->setValue($result->heading);
	$form->url->setValue($result->url);
    $form->image->setValue($result->image);
	$form->description->setValue($result->description);
    $this->view->save_description = $result->draft_content;
	//$form->categories->setValue($result->categories);
	$form->tags->setValue($result->tags);
	$form->submit->setLabel("Update");

    $this->view->url_slug = $result->url;
    $this->view->image = $result->image;
    $this->user_session->image = $result->image;

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
				unlink(SYSTEM_PATH."/images/user/posts/".$result->image);
				unlink(SYSTEM_PATH."/images/user/posts/500X500/".$result->image);
				unlink(SYSTEM_PATH.'/images/user/posts/800/'.$result->image);
				}

			$image = $_FILES['image']['name'];
			$random = rand(10,10000);
			$time = time() + (7 * 24 * 60 * 60);
			$file_name = $time . $random . $image;
			$formData["image"] = $file_name;

			move_uploaded_file($_FILES["image"]['tmp_name'], SYSTEM_PATH."/images/user/posts/".$file_name);
			$thumb = new Application_Model_Thumbnail(SYSTEM_PATH."/images/user/posts/".$file_name);
			$thumb->resize(500,500);
			$thumb->save(SYSTEM_PATH.'/images/user/posts/500X500/'.$file_name);
			$thumb->resize(200,200);
			$thumb->save(SYSTEM_PATH.'/images/user/posts/200/'.$file_name);

		}

	catch (Zend_File_Transfer_Exception $e)
		{
			throw new Exception('Bad data: '.$e->getMessage());
		}
}else{

$formData['image']= $this->user_session->image;

}

	$formData['post_id']= $this->user_session->post_id;

	//$slug= $formData['url'];
	//$formData['url']= str_replace("-","", $slug);

    $formData['date_published']= date("Y-m-d H:i:s");
		if($formData['submit'] == "0" )
        {
			$formData['is_in_draft'] = 0;
            $formData['draft_content'] = $formData['description'];
    	}
    	else
        {
    		$formData['is_in_draft'] = 1;
            $formData['draft_content'] = $formData['description'];
    	}
        
        $result = $this->post->updatePost($formData);
    	$this->view->msg = $result;
	}

	// delete post
	public function deletePostAction()
	{

	 $this->_helper->viewRenderer->setNoRender();
     $this->_helper->layout()->disableLayout();

		$id = $this->_request->getParam('id');
		$result = $this->post->getPostByID($id);
		unlink(SYSTEM_PATH.'/images/user/posts/500X500/'.$result->image);
		unlink(SYSTEM_PATH.'/images/user/posts/'.$result->image);
		unlink(SYSTEM_PATH.'/images/user/posts/200X200/'.$result->image);

		$this->post->removePost($this->db, $id);
		$this->_redirect('/admin/blog/index');
	}

	// Paginator action
	public function Paginator($results) {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
    }

	//this function is used for every function that recieves a ajax call
    public function ajaxed() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        if (!$this->_request->isXmlHttpRequest()){
		$this->_redirect('admin/index');
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