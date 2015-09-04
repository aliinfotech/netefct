<?php
class AjaxImagesController extends Zend_Controller_Action{
    
var $member_session = null;
private $authAdapter = null;
private $db = null;
 private $categories = null;
public function init(){
//     $this->_helper->layout()->disableLayout();
		$this->_helper->layout->setLayout('vednor');
              //  $this->view->header = "vendor-header.phtml";	
		$this->member_session = new Zend_Session_Namespace("member_session");
                $this->db = Zend_Db_Table::getDefaultAdapter();
                $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
                ini_set("max_execution_time", 0);//DO NOT EXPIRE SESSOIN
                $this->category_session = new Zend_Session_Namespace("category_session");
				}

	 public function indexAction() {
    
        }
    

}//class ends 