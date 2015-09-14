 <?php
 
class Admin_VideosController extends Zend_Controller_Action
{
	    protected $user_session = null;
        private $db = null;
        private $baseurl = null;
        private $authAdapter = null;
		private $video = null;
	

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
		$this->video = new Application_Model_Video();
		
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
	 $results = $this->video->getAllVideos();
        if (count($results) > 0) {
         $this->Paginator($results, 10);
        } else {
        $this->view->empty_rec = true;
        }
	}	
	
	
public function newAction() 
	{
		$form = new Application_Form_VideoLinkForm();
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
			$file_name = $random . $video_img;
			$formData["video_img"] = $file_name;
	 
			move_uploaded_file($_FILES["video_img"]['tmp_name'], SYSTEM_PATH."/images/user/videos/".$file_name);
			$thumb = new Application_Model_Thumbnail(SYSTEM_PATH."/images/user/videos/".$file_name);
			$thumb->resize(500,500);
			$thumb->save(SYSTEM_PATH.'/images/user/videos/500X500/'.$file_name);
			$thumb->resize(200,200);
			$thumb->save(SYSTEM_PATH.'/images/user/videos/200X200/'.$file_name);
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

	public function editAction(){
	
	$id = $this->_request->getParam('id');
    if(!isset($id)) $this->_redirect('admin/videos/index');
    $form = new Application_Form_VideoLinkForm();
   // get video data from video table
    $result = $this->video->getVideo($id);
   // var_dump($result);//
   	$this->view->id = $result->v_id;
    $form->title->setValue($result->title);
    $this->view->title = $result->title;
	$form->url_video->setValue($result->url_video);
	$form->short_description->setValue($result->short_description);
	$form->is_featured->setValue($result->is_featured);
	$form->is_main->setValue($result->is_main);
    //$form->video_img = $result->video_img; 
    $this->view->image = $result->video_img;
    $form->submit->setLabel("Update");
   
    $this->view->form = $form;
             if (!$this->_request->isPost()) return;
              $formData = $this->_request->getPost();
             if (!$form->isValid($formData)) return;

             //For Image upload
    $file_name = NULL;
    $image_name= $_FILES["video_img"]["name"];

    if(isset($image_name) && strlen($image_name) > 0 ) {

    try {
               if(isset($result->video_img)){

	$image_file = SYSTEM_PATH."/images/user/videos/500X500/".$result->video_img;

	if (file_exists($image_file)) {
           unlink(SYSTEM_PATH."/images/user/videos/".$result->video_img);
   	  }

	if (file_exists($image_file)) {
  	         unlink(SYSTEM_PATH."/images/user/videos/200X200/".$result->video_img);
     }
	if (file_exists($image_file)) {
           unlink(SYSTEM_PATH."/images/user/videos/500X500/".$result->video_img);
     }
 }

            $video_img = $_FILES['video_img']['name'];
            $random = rand(9,999999);
            $file_name = $random . $video_img;
            $formData["video_img"] = $file_name;

            move_uploaded_file($_FILES["video_img"]['tmp_name'], SYSTEM_PATH."images/user/videos/".$file_name);
            $thumb = new Application_Model_Thumbnail(SYSTEM_PATH."images/user/videos/".$file_name);
            $thumb->resize(500,500);
            $thumb->save(SYSTEM_PATH."images/user/videos/500X500/".$file_name);
            $thumb->resize(200,200);
            $thumb->save(SYSTEM_PATH."images/user/videos/200X200/".$file_name);
        }

    catch (Zend_File_Transfer_Exception $e)
        {
            throw new Exception('Bad data: '.$e->getMessage());
        }
}else{

$formData['video_img']=  $result->video_img;
}

    $formData['v_id']= $id;
    $result = $this->video->updateVideo($formData);
    $this->view->msg = $result;
    $this->_redirect("/admin/videos/edit/id/".$id);

	}
	
	
	// delete video data
	public function deleteVideoAction()
	{
		$id = $this->_request->getParam('id');
		$result = $this->video->getVideo($id);
		unlink(SYSTEM_PATH.'/images/user/videos/200X200/'.$result->video_img);
		unlink(SYSTEM_PATH.'/images/user/videos/500X500/'.$result->video_img);
		unlink(SYSTEM_PATH.'/images/user/videos/'.$result->video_img);
		
		$this->video->removeVideo($this->db, $id);
		$this->_redirect('/admin/videos');
	}
	
    
		// Paginator action
  	public function Paginator($results, $records) {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage($records);
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