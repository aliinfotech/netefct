<?php

class ErrorController extends Zend_Controller_Action
{

    public function errorAction()
    {
       $this->_redirect("index");
         $this->view->header = "header-index.phtml";
        $errors = $this->_getParam('error_handler');
        
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
        
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found' ;
               // print_r($errors);
                $step1 = substr($errors['exception'],93,100);
                $step2 = explode(')',$step1);
                $product_id = $this->getProduct($step2[0]);
//echo $product_id;


                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Application error';
                print_r($errors);
                break;
        }
        
//        // Log exception, if logger available
//        if ($log = $this->getLog()) {
//            $log->crit($this->view->message, $errors->exception);
//        }
//
//        // conditionally display exceptions
//        if ($this->getInvokeArg('displayExceptions') == true) {
//            $this->view->exception = $errors->exception;
//        }
//        
        $this->view->request   = $errors->request;
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasPluginResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }

}

