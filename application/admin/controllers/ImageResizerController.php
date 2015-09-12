<?php

class Admin_ImageBlocksController extends Zend_Controller_Action
{
   protected $user_session = null;
   private $db = null;
   private $baseurl = null;
   private $authAdapter = null;

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