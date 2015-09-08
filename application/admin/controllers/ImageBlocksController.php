<?php

class Admin_ImageBlocksController extends Zend_Controller_Action
{
    protected $user_session = null;
     private $db = null;
       private $baseurl = null;
        private $authAdapter = null;
    private $image_blocks = null;

	public function init(){
		Zend_Layout::startMvc(
		array('layoutPath'=>  APPLICATION_PATH . '/admin/layouts',  'layout' => 'image-block'));

                        $this->db = Zend_Db_Table::getDefaultAdapter();
                        $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
		$this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
		$this->user_session = new Zend_Session_Namespace("user_session");

		ini_set("max_execution_time",(60*300));
		$this->image_blocks = new Application_Model_ImageBlocks();

		if(!isset($this->user_session->user_id)){
			$this->_redirect("/admin/login/admin-login");
		}
		$auth = Zend_Auth::getInstance();
		//if not loggedin redirect to login page
		if (!$auth->hasIdentity()){
		$this->_redirect('/admin/login/admin-login');
        }
	}


  public function indexAction()
{
    if(isset($this->user_session->msg)){
    $this->view->msg = $this->user_session->msg;
    unset($this->user_session->msg);
    }

    $results = $this->image_blocks->getAllImageBlocks();
       if (count($results) > 0) {
         $this->Paginator($results, 10);
        } else {
        $this->view->empty_rec = true;
        }
}

  public function editImageBlockAction(){

    $id = $this->_request->getParam('id');
    if(!isset($id)) $this->_redirect('admin/image-blocks/index');
    $form = new Application_Form_ImageBlockForm();
   // get image block data from image_blocks table
    $result = $this->image_blocks->getImageBlockByID($id);
   // var_dump($result);//
    $this->view->id = $result->ib_id;
    $form->block->setValue($result->block);
    $this->view->block = $result->block;
    $form->name->setValue($result->name);
    $this->view->name = $result->name;
    $form->link->setValue($result->link);
    $form->caption->setValue($result->caption);
    $form->disable_link->setValue($result->disable_link);
    $this->view->form = $form;
             if (!$this->_request->isPost()) return;
              $formData = $this->_request->getPost();
             if (!$form->isValid($formData)) return;

             //For Image upload
    $file_name = NULL;
    $image_name= $_FILES["block"]["name"];

    if(isset($image_name) && strlen($image_name) > 0 ) {
    
    try {
                if(isset($result->block)){
                unlink(SYSTEM_PATH."/images/user/image-blocks/".$result->block);
                unlink(SYSTEM_PATH."/images/user/image-blocks/200X200/".$result->block);
                unlink(SYSTEM_PATH."/images/user/image-blocks/500X500/".$result->block);
                }
                 
            $block = $_FILES['block']['name'];
            $random = rand(10,10000);
            $time = time() + (7 * 24 * 60 * 60);
            $file_name = $time . $random . $block;
            $formData["block"] = $file_name;
     
            move_uploaded_file($_FILES["block"]['tmp_name'], SYSTEM_PATH."/images/user/image-blocks/".$file_name);
            $thumb = new Application_Model_Thumbnail(SYSTEM_PATH."/images/admin/user/image-blocks/".$file_name);
            $thumb->resize(200,200); 
            $thumb->save(SYSTEM_PATH."/images/admin/user/image-blocks/200X200/".$file_name);
            $thumb->resize(500,500);
            $thumb->save(SYSTEM_PATH."/images/admin/user/image-blocks/500X500/".$file_name);
            var_dump($thumb);
        }
        
    catch (Zend_File_Transfer_Exception $e)
        {
            throw new Exception('Bad data: '.$e->getMessage());
        }
}else{

$formData['block']= $file_name;
}
var_dump($formData);
return;
    $formData['ib_id']= $id;
    $result = $this->image_blocks->editBlockImage($formData);
    $this->view->msg = $result;
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

 public function ajaxed() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        if (!$this->_request->isXmlHttpRequest()
            )return; // if not a ajax request leave function

    }


	// Paginator action
  public function Paginator($results, $records) {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage($records);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
    }

/* Do not delete we created this for ajax base system.
public function indexAjaxedAction()
{
  $result = $this->image_blocks->getBlocks();
// var_dump($result);
$this->view->block1 = $result['block1'];
$this->view->caption1 = $result['caption1'];
  $this->view->link1 = $result['link1'];
}
*/

}