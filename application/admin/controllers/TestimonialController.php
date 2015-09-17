 <?php
 
class Admin_TestimonialController extends Zend_Controller_Action
{
	    protected $user_session = null;
        private $db = null;
        private $baseurl = null;
        private $authAdapter = null;
		private $testimonial = null;

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
		$this->testimonial = new Application_Model_Testimonials(); 
		
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
	 if(isset($this->user_session->msg)){
	 $this->view->msg = $this->user_session->msg;
	 unset($this->user_session->msg);
	 }
	 $results = $this->testimonial->getAllTestimonials();
        if (count($results) > 0) {
         $this->Paginator($results, 10);
        } else {
        $this->view->empty_rec = true;
        }
	}	
	
	public function newAction()
	{
		$form = new Application_Form_TestimonialForm();
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
			$test_img = $_FILES['image1']['name'];
			$random = rand(9,99999);
			$file_name = $random . $test_img;
			$formData["image1"] = $file_name;
	 
			move_uploaded_file($_FILES["image1"]['tmp_name'], SYSTEM_PATH."/images/user/testimonials/".$file_name);
			$thumb = new Application_Model_Thumbnail(SYSTEM_PATH."/images/user/testimonials/".$file_name);
			$thumb->resize(500,500);
            $thumb->save(SYSTEM_PATH."images/user/testimonials/500X500/".$file_name);
			$thumb->resize(200,200);
			$thumb->save(SYSTEM_PATH.'/images/user/testimonials/200X200/'.$file_name);
		}
		 
		catch (Zend_File_Transfer_Exception $e)
		{
			throw new Exception('Bad data: '.$e->getMessage());
		}

 		$result = $this->testimonial->addTestimonial($formData);
		$this->view->msg = $result;
		//clear all form fields 

	$form->reset();
	}
	
	
	public function editAction(){
	
	$id = $this->_request->getParam('id');
    if(!isset($id)) $this->_redirect('admin/testimonial/index');
    $form = new Application_Form_TestimonialForm();
   // get testimonial data from testimonial table
    $result = $this->testimonial->getTestimonial($id);
   // var_dump($result);//
    $this->view->id = $result->test_id;
    $form->image1->setValue($result->image1);
    $this->view->image = $result->image1;
    $form->first_name->setValue($result->first_name);
    $this->view->name = $result->first_name;
    $form->last_name->setValue($result->last_name);
    $form->email->setValue($result->email);
    $form->short_description->setValue($result->short_description);
    $form->is_featured->setValue($result->is_featured);
    $form->submit->setLabel("Update");
    $this->view->form = $form;
             if (!$this->_request->isPost()) return;
              $formData = $this->_request->getPost();
             if (!$form->isValid($formData)) return;

             //For Image upload
    $file_name = NULL;
    $image_name= $_FILES["image1"]["name"];

    if(isset($image_name) && strlen($image_name) > 0 ) {

    try {
               if(isset($result->image1)){

$image_file = SYSTEM_PATH."/images/user/testimonials/500X500/".$result->image1;

if (file_exists($image_file)) {
           unlink(SYSTEM_PATH."/images/user/testimonials/".$result->image1);
     }

if (file_exists($image_file)) {
           unlink(SYSTEM_PATH."/images/user/testimonials/200X200/".$result->image1);
     }
if (file_exists($image_file)) {
           unlink(SYSTEM_PATH."/images/user/testimonials/500X500/".$result->image1);
     }
 }

            $image1 = $_FILES['image1']['name'];
            $random = rand(9,999999);
            $file_name = $random . $image1;
            $formData["image1"] = $file_name;

            move_uploaded_file($_FILES["image1"]['tmp_name'], SYSTEM_PATH."images/user/testimonials/".$file_name);
            $thumb = new Application_Model_Thumbnail(SYSTEM_PATH."images/user/testimonials/".$file_name);

            $thumb->resize(500,500);
            $thumb->save(SYSTEM_PATH."images/user/testimonials/500X500/".$file_name);

            $thumb->resize(200,200);
            $thumb->save(SYSTEM_PATH."images/user/testimonials/200X200/".$file_name);


        }

    catch (Zend_File_Transfer_Exception $e)
        {
            throw new Exception('Bad data: '.$e->getMessage());
        }
}else{

$formData['image1']=  $result->image1;
}

    $formData['test_id']= $id;
    $result = $this->testimonial->edit($formData);
    $this->view->msg = $result;
    $this->_redirect("/admin/testimonial/edit/id/".$id);

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
		unlink(SYSTEM_PATH.'/images/user/testimonials/200X200/'.$result->image1);
		unlink(SYSTEM_PATH.'/images/user/testimonials/500X500/'.$result->image1);
		unlink(SYSTEM_PATH.'/images/user/testimonials/'.$result->image1);
		
		$delete = $this->testimonial->removeTestimonial($this->db, $id);
		$this->user_session->msg  = $delete;
		$this->_redirect('/admin/testimonial');
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