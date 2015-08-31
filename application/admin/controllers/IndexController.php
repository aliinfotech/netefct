<?php
/**
 Author: Musavir Ifitkahr:
 Date: May 2015
 kuala lumpur Malaysia
 */
 
class Admin_IndexController extends Zend_Controller_Action
{
	    protected $user_session = null;
        private $db = null;
        private $baseurl = null;
        private $authAdapter = null;
		private $applicant = null;
		private $user = null;
		private $video = null;
		private $testimonial = null;
		private $thumb = null;

	public function init(){
		Zend_Layout::startMvc(
		array('layoutPath'=>  APPLICATION_PATH . '/admin/layouts',  'layout' => 'layout'));
		$this->db = Zend_Db_Table::getDefaultAdapter();
        $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
		$this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
		if(!isset($this->user_session->user_id)){
		$this->user_session = new Zend_Session_Namespace("user_session");
		}
		ini_set("max_execution_time",(60*300));
		$this->user = new Application_Model_User();
		$this->video = new Application_Model_Video();
		$this->testimonial = new Application_Model_Testimonials(); 
		//$this->thumb = new Application_Model_Thumbnail();
		
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
	
	public function homePageTextAction()
{
		if($this->user_session->msg!=null)
		{
			$this->view->msg = $this->user_session->msg;
			$this->user_session->msg = null;
		}
		$form = new Application_Form_MpTextForm();
		$mptext = new Application_Model_Mpr();
		$form->row_text->setValue($mptext->getText());
		$this->view->form = $form;
		if($this->user_session->msg!=null)
		{
			$this->view->msg = $this->user_session->msg;
			$this->user_session->msg = null;
		}
		
		if (!$this->_request->isPost())return;
		$formData = $this->_request->getPost();
		if (!$form->isValid($formData)) return;
		
		$mptext->updateText($formData['row_text']);
		$this->user_session->msg = "<div class='alert alert-success'> Text Updated </div>";
	$this->_redirect('/admin/index/home-page-text');
}		
public function newVideolinkAction() 
	{
		$form = new Application_Form_AddVideoLinkForm();
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
			$video_img = $_FILES['video_img']['name'];
			$random = rand(10,10000);
			$time = time() + (7 * 24 * 60 * 60);
			$file_name = $time . $random . $video_img;
			$formData["video_img"] = $file_name;
	 
			move_uploaded_file($_FILES["video_img"]['tmp_name'], SYSTEM_PATH."/images/video/originals/".$file_name);
			$thumb = new Application_Model_Thumbnail(SYSTEM_PATH."/images/video/originals/".$file_name);
			$thumb->resize(200,200);
			$thumb->save(SYSTEM_PATH.'/images/video/200X200/'.$file_name);
		}
		 
		catch (Zend_File_Transfer_Exception $e)
		{
			throw new Exception('Bad data: '.$e->getMessage());
		}

 		$result = $this->video->addVideo($formData);
		$this->view->msg = $result;
		//clear all form fields 

	$form->reset();

	}
	
	public function videoListAction(){

	$results = $this->video->getAllVideos();
       if (count($results) > 0) {
		 $this->Paginator($results);
        } else {
        $this->view->empty_rec = true;
		}
}

	public function editVideoAction(){
	
	$id = $this->_request->getParam('id');
	$form = new Application_Form_AddVideoLinkForm();
	$this->view->video_id = $id;
	
if(isset($id)){
	$this->user_session->v_id = $id;
}
  
if(isset($id) || isset($this->user_session->v_id)){
  	$result = $this->video->getVideo($this->user_session->v_id);	
	
	//var_dump($result);
	//return;
	$this->view->id = $result->v_id;
    $form->title->setValue($result->title);
	$form->url_video->setValue($result->url_video);
	$form->short_description->setValue($result->short_description);
	$form->is_featured->setValue($result->is_featured);
	$form->is_main->setValue($result->is_main);
	$this->view->video_image = $result->video_img;
    $this->user_session->video_image = $result->video_img; 

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
	
$image_name= $_FILES["video_img"]["name"];

    if(isset($image_name) && strlen($image_name) > 0 ) {
	
	try {
				if(isset($this->user_session->video_image)){
				unlink(SYSTEM_PATH."/images/video/originals/".$result->video_img);
				unlink(SYSTEM_PATH."/images/video/200X200/".$result->video_img);
				}
				 
			$video_img = $_FILES['video_img']['name'];
			$random = rand(10,10000);
			$time = time() + (7 * 24 * 60 * 60);
			$file_name = $time . $random . $video_img;
			$formData["video_img"] = $file_name;
	 
			move_uploaded_file($_FILES["video_img"]['tmp_name'], SYSTEM_PATH."/images/video/originals/".$file_name);
			$thumb = new Application_Model_Thumbnail(SYSTEM_PATH."/images/video/originals/".$file_name);
			$thumb->resize(200,200); 
			$thumb->save(SYSTEM_PATH.'/images/video/200X200/'.$file_name);
			
		}
		
	catch (Zend_File_Transfer_Exception $e)
		{
			throw new Exception('Bad data: '.$e->getMessage());
		}
}else{

$formData['video_img']= $this->user_session->video_image;

}
	
	$formData['video_id']= $this->user_session->v_id;
//var_dump($formData);
//return;
	$result = $this->video->updateVideo($formData);
	$this->_redirect("/admin/index/video-list");
	}
	
	
	// delete video data
	public function deleteVideoAction()
	{
		$id = $this->_request->getParam('id');
		$result = $this->video->getVideo($id);
		unlink(SYSTEM_PATH.'/images/video/200X200/'.$result->video_img);
		unlink(SYSTEM_PATH.'/images/video/originals/'.$result->video_img);
		
		$this->video->removeVideo($this->db, $id);
		$this->_redirect('/admin/index/video-list');
	}
	
	
	public function newTestimonialAction()
	{
		$form = new Application_Form_AddTestimonialForm();
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
			$testimonial_img = $_FILES['image1']['name'];
			$random = rand(10,10000);
			$time = time() + (7 * 24 * 60 * 60);
			$file_name = $time . $random . $testimonial_img;
			$formData["image1"] = $file_name;
	 
			move_uploaded_file($_FILES["image1"]['tmp_name'], SYSTEM_PATH."/images/testimonial-images/originals/".$file_name);
			$thumb = new Application_Model_Thumbnail(SYSTEM_PATH."/images/testimonial-images/originals/".$file_name);
			$thumb->resize(200,200);
			$thumb->save(SYSTEM_PATH.'/images/testimonial-images/200X200/'.$file_name);
		}
		 
		catch (Zend_File_Transfer_Exception $e)
		{
			throw new Exception('Bad data: '.$e->getMessage());
		}

 		$result = $this->testimonial->addTestimonials($formData);
		$this->view->msg = $result;
		//clear all form fields 

	$form->reset();
	}
	
	
	public function editTestimonialAction(){
	
	$id = $this->_request->getParam('id');
	$form = new Application_Form_AddTestimonialForm();
	$this->view->t_id = $id;
	
if(isset($id)){
	$this->user_session->test_id = $id;
}
  
if(isset($id) || isset($this->user_session->test_id)){
  	$result = $this->testimonial->getTestimonial($this->user_session->test_id);	
    //var_dump($result);
	$this->view->id = $result->test_id;
    $form->email->setValue($result->email);
    $form->first_name->setValue($result->first_name);
	$form->last_name->setValue($result->last_name);
	$form->short_description->setValue($result->short_description);
	$form->is_featured->setValue($result->is_featured); 
    $this->view->image = $result->image1;
    $this->user_session->image = $result->image1; 

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
	
$image_name= $_FILES["image1"]["name"];

    if(isset($image_name) && strlen($image_name) > 0 ) {

   // var_dump("herer");
  //  return;	
	try {
				if(isset($this->user_session->image)){
				unlink(SYSTEM_PATH."/images/testimonial-images/originals/".$result->image1);
				unlink(SYSTEM_PATH."/images/testimonial-images/200X200/".$result->image1);
				}
				 
			$image1 = $_FILES['image1']['name'];
			$random = rand(10,10000);
			$time = time() + (7 * 24 * 60 * 60);
			$file_name = $time . $random . $image1;
			$formData["image1"] = $file_name;
	 
			move_uploaded_file($_FILES["image1"]['tmp_name'], SYSTEM_PATH."/images/testimonial-images/originals/".$file_name);
			$thumb = new Application_Model_Thumbnail(SYSTEM_PATH."/images/testimonial-images/originals/".$file_name);
			$thumb->resize(200,200);
			$thumb->save(SYSTEM_PATH.'/images/testimonial-images/200X200/'.$file_name);
			
		}
		
	catch (Zend_File_Transfer_Exception $e)
		{
			throw new Exception('Bad data: '.$e->getMessage());
		}
}else{

$formData['image1']= $this->user_session->image;

}
	
	$formData['t_id']= $this->user_session->test_id;
/*var_dump($formData);
return;*/
	$result = $this->testimonial->editTestimonial($formData);
	$this->_redirect('/admin/index/testimonial-list');
	}
		
	
	public function testimonialListAction(){

	if(isset($this->user_session->msg)){
	$this->view->msg = $this->user_session->msg;
	unset($this->user_session->msg);
	}
	$results = $this->testimonial->getAllTestimonials();
       if (count($results) > 0) {
		 $this->Paginator($results);
        } else {
        $this->view->empty_rec = true;
		}
	}

	// delete testimonial
	public function deleteTestimonialAction()
	{
		$id = $this->_request->getParam('id');
		$result = $this->testimonial->getTestimonial($id);
		unlink(SYSTEM_PATH.'/images/testimonial-images/200X200/'.$result->image1);
		unlink(SYSTEM_PATH.'/images/testimonial-images/originals/'.$result->image1);
		
		$delete = $this->testimonial->removeTestimonial($this->db, $id);
		$this->user_session->msg  = $delete;
		$this->_redirect('/admin/index/testimonial-list');
	}
	
	public function updateUrlsAction(){
		if($this->user_session->msg!=null)
		{
			$this->view->msg = $this->user_session->msg;
			$this->user_session->msg = null;
		}
		$form = new Application_Form_UrlsForm();
		$urls = new Application_Model_Urls();
		$result = $urls->getUrls();
		$form->page_url->setValue($result->page_url);
		$form->post_url->setValue($result->post_url);
		$this->view->form = $form;
		
		if (!$this->_request->isPost())return;
		$formData = $this->_request->getPost();
		if (!$form->isValid($formData)) return;
		
		$urls->updateUrls($formData);
		$this->user_session->msg = "<div class='alert alert-success'> Urls Updated </div>";
	$this->_redirect('/admin/index/update-urls');
		
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