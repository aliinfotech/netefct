<?php
class AjaxImagesController extends Zend_Controller_Action{

private $authAdapter = null;
private $db = null;

public function init(){
$this->_helper->layout->setLayout('layout');
$this->db = Zend_Db_Table::getDefaultAdapter();
$this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
ini_set("max_execution_time", 0);//DO NOT EXPIRE SESSOIN
}

public function indexAction() {
$this->_redirect('index');
}

// Banner Image
public function ImageBlock1Action(){
$this->ajaxed();


/*
if (!headers_sent() )
{
    header('Content-type: application/json');
}
//echo  json_encode($result);
echo Zend_Json::encode($result);
*/
}

// Help to make a function Ajaxed
 public function ajaxed() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        if (!$this->_request->isXmlHttpRequest()
            )return; // if not a ajax request leave function

    }

// if user calls a wrong function
public function __call($method, $args) {
        if ('Action' == substr($method, -6)) {
            // If the action method was not found, forward to the
            // index action
            return $this->_redirect('index');
        }

        // all other methods throw an exception
        throw new Exception('Invalid method "'
                . $method
                . '" called',
                500);
    }

}//class ends